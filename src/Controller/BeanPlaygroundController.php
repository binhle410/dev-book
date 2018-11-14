<?php

namespace App\Controller;

use Bean\Component\CreativeWork\Model\CreativeWork;
use Bean\Component\Messaging\Model\ConversationInterface;
use Bean\Component\Thing\Model\Thing;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use function GuzzleHttp\Psr7\copy_to_string;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Book\BookPage;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\Conversation;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\Message;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\MessageDelivery;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\System\DataProcessing\DPJob;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\VAPID;
use Minishlink\WebPush\WebPush;
use Sonata\MediaBundle\Form\Type\ApiMediaType;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BeanPlaygroundController extends Controller
{

    public function __invoke(Book $data)
    {
        /** @var ArrayCollection $chapters */
        $chapters = $data->getChapters();

        $criteria = Criteria::create();
        $expr = Criteria::expr();

        $criteria->where(
            $expr->eq('partOf', $data)
        );

        return $chapters->matching($criteria);
    }

    /**
     * @Route("/bean/playground", name="bean_playground")
     */
    public function index(Request $request)
    {
//        $dp = $this->getDoctrine()->getRepository(DPJob::class)->find(3);
//        $this->get('magenta_book.individual_service')->importMembers($dp);
//        return new JsonResponse(['link' => 'https://picsum.photos/1600/900','key'=>(VAPID::createVapidKeys())]);
// here I'll get the subscription endpoint in the POST parameters
// but in reality, you'll get this information in your database
// because you already stored it (cf. push_subscription.php)
        $subscription = Subscription::create(
            [
                'endpoint' => 'https://fcm.googleapis.com/fcm/send/eD9IUBJ7Zbg:APA91bHcexZWw3y88F6JXCibSMUhu2wxsAVQjcLsexh-2SdgNEd6-t0vL-a8wAZvRPGHAvBvMBs1VwgP56rZ3CZQTdQ0PpuK0gG9Wvl26iHW7sIlLf150CcWaTr4UnEQgt2sZmFPJJ7E',
                'publicKey' => 'BP4AZpl+UGj37CB/3a2cCOYKeQ83SEj6jJIDK7sNAqMe3EL3ROfsF32v2clcDeHIoefbKR5PgAhYc4q7nH8J3Q0=',
                'authToken' => 'YTopEigDsuQ3eCLlOuaFSg==',
                'contentEncoding' => 'aes128gcm', // one of PushManager.supportedContentEncodings
            ]
        );
        $auth = array(
            'VAPID' => array(
                'subject' => 'https://github.com/Minishlink/web-push-php-example/',
                'publicKey' => 'BOaSVoq5ShMGa_acdk_D_u368jY-dt4cCt1UfdpCd1418azBciTeWDy3ahIhL2G847tQ6T0taG3-CUqoA9E_ZBY',
                'privateKey' => 'oFN6rOOFJD05hR2E_pNxO5iFY_19lv62OLjEFeiuT5Q', // in the real world, this would be in a secret file
            ),
        );
//        $webPush = new WebPush($auth);
//        $res = $webPush->sendNotification(
//            $subscription,
//            "Hello! Sorry for pushing you a lot.",
//            true
//        );

        $m = $this->get('doctrine.orm.default_entity_manager');
        $id = 32036;

        $conversation = $this->getDoctrine()->getRepository(CreativeWork::class)->find($id);
        if (!$conversation instanceof ConversationInterface) {
            throw new NotFoundHttpException('con of ConInterface not found');
        }

        $msg = $this->getDoctrine()->getRepository(Message::class)->find(32039);
        $recipient = $this->getDoctrine()->getRepository(IndividualMember::class)->find(32029);
        $msg->setAbout('about text 32039');

        $delivery = $this->getDoctrine()->getRepository(MessageDelivery::class)->find(1);
        $deliveries = $recipient->getMessageDeliveries();
        $d = [];
        /** @var MessageDelivery $de */
        foreach ($deliveries as $de) {
            $dr = '';
            if (!empty($de->getDateRead())) {
                $dr = $de->getDateRead()->format('d-m-Y');
            }
            $d[] = $de->getId() . ' - ' . $dr;
        }

        $delivered = $recipient->isMessageDelivered($msg);
//        $m->persist($delivery);
//        $m->flush();
        return new JsonResponse([$id, $d, $delivered]);
    }
}
