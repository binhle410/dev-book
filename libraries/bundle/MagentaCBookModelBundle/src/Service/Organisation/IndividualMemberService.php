<?php

namespace Magenta\Bundle\CBookModelBundle\Service\Organisation;

use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\System\DataProcessing;
use Magenta\Bundle\CBookModelBundle\Service\BaseService;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IndividualMemberService extends BaseService
{
    protected $registry;
    protected $spreadsheetService;
    protected $manager;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->manager = $container->get('doctrine.orm.default_entity_manager');
        $this->registry = $container->get('doctrine');
        $this->spreadsheetService = $container->get('magenta_book.spreadsheet_service');
    }

    public function importMembers(DataProcessing $dp)
    {
        if ($dp->getType() !== DataProcessing::TYPE_MEMBER_IMPORT || $dp->getIndex() > 0) {
            return;
        }

        $resourceName = $dp->getResourceName();
        $reader = $this->spreadsheetService->createReader($filePath = $this->spreadsheetService->getMemberImportFolder() . $resourceName);
        $spreadsheet = $reader->load($filePath);
        $ws = $spreadsheet->getActiveSheet();

        /** @var Organisation $org */
        $org = $this->registry->getRepository(Organisation::class)->find($dp->getOwnerId());

        $row = 2;
        while (true) {
            $_serialNumber = $ws->getCell('A' . $row)->getValue();
            $_fname = $ws->getCell('B' . $row)->getValue();
            if (empty($_fname) && empty($_serialNumber)) {
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
            $_email = trim($ws->getCell('F' . $row)->getValue());
            $_password = trim($ws->getCell('G' . $row)->getValue());

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

                /** @var IndividualMember $member */
                $member = $org->getIndividualMemberFromPerson($person);
                if (!empty($member)) {
                    $member->setEnabled(true);
                    continue;
                } else {
                    $member = new IndividualMember();
                    $member->setEnabled(true);
                    $member->setOrganization($org);
                    $member->setPerson($person);
                    $member->setEmail($_email);
                }
                $this->manager->persist($member);
                $this->manager->persist($person);
            } else {

            }


        }
    }

}