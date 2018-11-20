<?php

namespace Magenta\Bundle\CBookModelBundle\Service\Organisation;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use JMS\Serializer\Tests\Fixtures\DoctrinePHPCR\BlogPost;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\Message;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\MessageDelivery;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\System\DataProcessing\DPJob;
use Magenta\Bundle\CBookModelBundle\Entity\System\DataProcessing\DPLog;
use Magenta\Bundle\CBookModelBundle\Entity\System\ProgressiveWebApp\Subscription;
use Magenta\Bundle\CBookModelBundle\Service\BaseService;
use Minishlink\WebPush\WebPush;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class IndividualMemberService extends BaseService
{
    protected $registry;
    protected $spreadsheetService;
    protected $personService;
    protected $manager;
    protected $members = [];
    
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->manager = $container->get('doctrine.orm.default_entity_manager');
        $this->registry = $container->get('doctrine');
        $this->spreadsheetService = $container->get('magenta_book.spreadsheet_service');
        $this->personService = $container->get('magenta_book.person_service');
    }
    
    public function checkAccess($accessCode, $employeeCode, $orgSlug = null)
    {
        $member = $this->getMemberByPinCodeEmployeeCode($accessCode, $employeeCode);
        if (empty($member) || !$member->isEnabled() || !$member->getOrganization()->isEnabled()) {
            throw new UnauthorizedHttpException('Cannot access book reader. Invalid access code');
        }
        if (!empty($orgSlug)) {
            if ($member->getOrganization()->getSlug() !== $orgSlug) {
                throw new UnauthorizedHttpException('Cannot access book reader. Invalid org code');
            }
        }
    }
    
    public function getMemberByPinCodeEmployeeCode($accessCode, $employeeCode): ?IndividualMember
    {
        $arrayKey = $accessCode . ':' . $employeeCode;
        if (!is_array($this->members) || !array_key_exists($arrayKey, $this->members)) {
            $registry = $this->getDoctrine();
            $memberRepo = $registry->getRepository(IndividualMember::class);
            if (!is_array($this->members)) {
                $this->members = [];
            }
            $this->members[$arrayKey] = $memberRepo->findOneByPinCodeEmployeeCode($accessCode, $employeeCode);
        }
        return $this->members[$arrayKey];
    }
    
    public function notifyOneOrganisationIndividualMembers(DPJob $dp)
    {
        if ($dp->getType() !== DPJob::TYPE_PWA_PUSH_ORG_INDIVIDUAL || $dp->getStatus() !== DPJob::STATUS_PENDING) {
            return;
        }
        $row = 0;
        
        try {
            if (empty($dp->getOwnerId())) {
                throw new \InvalidArgumentException('empty ownerId');
            }
            
            $memberRepo = $this->registry->getRepository(IndividualMember::class);
            /** @var Message $message */
            $message = $this->registry->getRepository(Message::class)->find((int)$dp->getResourceName());
            
            if ($dp->getStatus() === DPJob::STATUS_PENDING) {
                
                
                $members = $memberRepo->findHavingOrganisationSubscriptions((int)$dp->getOwnerId());
                
                if (count($members) > 0) {
                    $dp->setStatus(DPJob::STATUS_LOCKED);
                    $this->manager->persist($dp);
                    $this->manager->flush();
                }
                
                $path = $this->container->getParameter('PWA_PUBLIC_KEY_PATH');
                $pwaPublicKey = trim(file_get_contents($path));
                $path = $this->container->getParameter('PWA_PRIVATE_KEY_PATH');
                $pwaPrivateKey = trim(file_get_contents($path));
                $auth = array(
                    'VAPID' => array(
                        'subject' => 'mailto:peter@magenta-wellness.com',
                        'publicKey' => $pwaPublicKey,
                        'privateKey' => $pwaPrivateKey, // in the real world, this would be in a secret file
                    ),
                );
                $webPush = new WebPush($auth);
//                $multipleRun = false;
                /**
                 * @var IndividualMember $member
                 */
                foreach ($members as $member) {
                    if ($member->isMessageDelivered($message)) {
                        continue;
                    }
                    $row++;
//                    if ($row > 1000) {
//                        $multipleRun = true;
//                        break;
//                    }
                    
                    $subscriptions = $member->getSubscriptions();
                    
                    $preparedSubscriptions = [];
                    /**
                     * @var Subscription $_sub
                     */
                    foreach ($subscriptions as $_sub) {
                        $preparedSub = Subscription::create(
                            [
                                'endpoint' => $_sub->getEndpoint(),
                                'publicKey' => $_sub->getP256dhKey(),
                                'authToken' => $_sub->getAuthToken(),
                                'contentEncoding' => $_sub->getContentEncoding(), // one of PushManager.supportedContentEncodings
                            ]
                        );
                        $preparedSubscriptions[] = $preparedSub;
                        
                        $webPush->sendNotification(
                            $preparedSub,
                            json_encode(['sender-name' => $message->getSender()->getName(),
                                'message-id' => $message->getId(),
                                'message-name' => $message->getName(),
                                'subscription-id' => $_sub->getId()]),
                            false
                        );
                    }
                    
                    $recipient = $member;
                    $delivery = MessageDelivery::createInstance($message, $recipient);
                    $this->manager->persist($delivery);
                }
                
                $res = $webPush->flush();
                $dp->setStatus(DPJob::STATUS_PENDING);
                $this->manager->persist($dp);
                $this->manager->flush();
            } else {
                return;
            }
            
            $dp->setStatus(DPJob::STATUS_SUCCESSFUL);
            $this->manager->persist($dp);
            echo 'try flusing 111  ';
            
            
            if (!$this->manager->isOpen()) {
                throw new \Exception('EM is closed before flushed ' . $row);
            } else {
                echo $row . "rows are still ok before flushing .........  ";
//                /**
//                 * @var IndividualMember $member
//                 */
//                foreach ($importedMembers as $k => $member) {
//                    echo ' member ' . $k . ': ' . $member->getPerson()->getEmail();
//                }
            }
            
            $this->manager->flush();
        } catch (OptimisticLockException $ope) {
            $error = new DPLog();
            $error->setName('OptimisticLockException: ' . $ope->getFile());
            $error->setLevel(DPLog::LEVEL_ERROR);
            $error->setIndex($row);
            $error->setJob($dp);
            $error->setCode($ope->getCode());
            $error->setTrace($ope->getTrace());
            $error->setMessage($ope->getMessage());
//
            if (!$this->manager->isOpen()) {
                $this->manager = $this->manager->create(
                    $this->manager->getConnection(),
                    $this->manager->getConfiguration(),
                    $this->manager->getEventManager()
                );
            }
            $this->manager->persist($error);
            $this->manager->flush();
        } catch (ORMException $orme) {
            $error = new DPLog();
            $error->setName('ORMException: ' . $orme->getFile());
            $error->setLevel(DPLog::LEVEL_ERROR);
            $error->setIndex($row);
            $error->setJob($dp);
            $error->setCode($orme->getCode());
            $error->setTrace($orme->getTrace());
            $error->setMessage($orme->getMessage());
            if (!$this->manager->isOpen()) {
                $this->manager = $this->manager->create(
                    $this->manager->getConnection(),
                    $this->manager->getConfiguration(),
                    $this->manager->getEventManager()
                );
            }
            $this->manager->persist($error);
            $this->manager->flush();
        } catch (\Exception $e) {
            $error = new DPLog();
            $error->setName('ORMException: ' . $e->getFile());
            $error->setLevel(DPLog::LEVEL_ERROR);
//            $error->setIndex($row);
            $error->setJob($dp);
            $error->setCode($e->getCode());
            $error->setTrace($e->getTrace());
            $error->setMessage($e->getMessage());
            if (!$this->manager->isOpen()) {
                $this->manager = $this->manager->create(
                    $this->manager->getConnection(),
                    $this->manager->getConfiguration(),
                    $this->manager->getEventManager()
                );
            }
            $this->manager->persist($error);
            $this->manager->flush();
            throw $e;
        }
    }
    
    public function importMembers(DPJob $dp)
    {
        if ($dp->getType() !== DPJob::TYPE_MEMBER_IMPORT || $dp->getIndex() > 0) {
            return;
        }
        
        $dp->setStatus(DPJob::STATUS_LOCKED);
        $this->manager->persist($dp);
        $this->manager->flush();
        
        $resourceName = $dp->getResourceName();
        $reader = $this->spreadsheetService->createReader($filePath = $this->spreadsheetService->getMemberImportFolder() . $resourceName);
        $spreadsheet = $reader->load($filePath);
        $ws = $spreadsheet->getActiveSheet();
        
        /** @var Organisation $org */
        $org = $this->registry->getRepository(Organisation::class)->find($dp->getOwnerId());
        
        $row = 1;
        $importedMembers = [];
        while (true) {
            if (!$this->manager->isOpen()) {
                throw new \Exception('EM is closeddddddddddddddd ' . $row);
            }
            $row++;
            $_serialNumber = $ws->getCell('A' . $row)->getValue();
            $_fname = $ws->getCell('B' . $row)->getValue();
            if (empty($_fname) || empty($_serialNumber)) {
                break;
            }
            
            $_lname = $ws->getCell('C' . $row)->getValue();
            $_idNumber = trim($ws->getCell('D' . $row)->getValue());
            
            $_dobCell = $ws->getCell('E' . $row);
            $_dobString = $_dobCell->getValue();
            if (Date::isDateTime($_dobCell)) {
                $_dob = new \DateTime('@' . Date::excelToDateTimeObject($_dobString));
            } elseif (!empty($_dobString)) {
                $_dobString = trim($_dobString);
                $_dob = \DateTime::createFromFormat('d/m/Y', $_dobString);
            } else {
                $_dob = null;
            }
            $_gender = trim($ws->getCell('F' . $row)->getValue());
            $_email = trim($ws->getCell('G' . $row)->getValue());
            $_password = trim($ws->getCell('H' . $row)->getValue());
            $_nationality = trim($ws->getCell('I' . $row)->getValue());
            
            /** @var Person $person */
            $person = $this->registry->getRepository(Person::class)->findOnePersonByIdNumberOrEmail($_idNumber, $_email);
            if (!empty($person)) {
                if (empty($person->getEmail())) {
                    $person->setEmail($_email);
                }
                if (empty($person->getIdNumber())) {
                    $person->setIdNumber($_idNumber);
                }
                
                $person->setEnabled(true);
                
                if (!empty($_nationality)) {
                    $person->setNationalityString(trim($_nationality));
                }
                if (!empty($_gender)) {
                    $person->setGender(trim($_gender));
                }
                
                /** @var IndividualMember $member */
                $member = $org->getIndividualMemberFromPerson($person);
                if (!empty($member)) {
                    $member->setEnabled(true);
                    continue;
                } else {
                    $member = IndividualMember::createInstance($org, $person, $_email);
                }
                $this->manager->persist($member);
                $this->manager->persist($person);
            } else {
                $person = Person::createInstance($_idNumber, $_dob, $_fname, $_lname, $_email);
                if (!empty($_nationality)) {
                    $person->setNationalityString(trim($_nationality));
                }
                if (!empty($_gender)) {
                    $person->setGender(trim($_gender));
                }
                $this->manager->persist($person);
                $member = IndividualMember::createInstance($org, $person, $_email);
                $this->manager->persist($member);
            }
            if (!empty($_email)) {
                if (!empty($_password)) {
                    $user = $this->personService->initiateUser($person);
                    $user->setPlainPassword($_password);
                    $this->manager->persist($user);
                }
            }
            $importedMembers[] = $member;
        }
        
        $dp->setStatus(DPJob::STATUS_SUCCESSFUL);
        $this->manager->persist($dp);
        echo 'try flusing 111  ';
        try {
            
            if (!$this->manager->isOpen()) {
                throw new \Exception('EM is closed before flushed ' . $row);
            } else {
                echo $row . "rows are still ok before flushing .........  ";
                /**
                 * @var IndividualMember $member
                 */
                foreach ($importedMembers as $k => $member) {
                    echo ' member ' . $k . ': ' . $member->getPerson()->getEmail();
                }
            }
            
            $this->manager->flush();
        } catch (OptimisticLockException $ope) {
            $error = new DPLog();
            $error->setName('OptimisticLockException: ' . $ope->getFile());
            $error->setLevel(DPLog::LEVEL_ERROR);
            $error->setIndex($row);
            $error->setJob($dp);
            $error->setCode($ope->getCode());
            $error->setTrace($ope->getTrace());
            $error->setMessage($ope->getMessage());
//
            if (!$this->manager->isOpen()) {
                $this->manager = $this->manager->create(
                    $this->manager->getConnection(),
                    $this->manager->getConfiguration(),
                    $this->manager->getEventManager()
                );
            }
            $this->manager->persist($error);
            $this->manager->flush();
        } catch (ORMException $orme) {
            $error = new DPLog();
            $error->setName('ORMException: ' . $orme->getFile());
            $error->setLevel(DPLog::LEVEL_ERROR);
            $error->setIndex($row);
            $error->setJob($dp);
            $error->setCode($orme->getCode());
            $error->setTrace($orme->getTrace());
            $error->setMessage($orme->getMessage());
            if (!$this->manager->isOpen()) {
                $this->manager = $this->manager->create(
                    $this->manager->getConnection(),
                    $this->manager->getConfiguration(),
                    $this->manager->getEventManager()
                );
            }
            $this->manager->persist($error);
            $this->manager->flush();
        }
    }
    
}