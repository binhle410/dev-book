<?php

namespace Magenta\Bundle\CBookAdminBundle\Controller;

use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\System\ProgressiveWebApp\Subscription;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookPWAController extends Controller
{
    public function savePushSubscriptionAction($orgSlug, $accessCode, $employeeCode, Request $request)
    {
        $this->get('magenta_book.individual_service')->checkAccess($accessCode, $employeeCode, $orgSlug);
        $member = $this->get('magenta_book.individual_service')->getMemberByPinCodeEmployeeCode($accessCode, $employeeCode);

        /**
         * {"endpoint":"https://fcm.googleapis.com/fcm/send/e1vbWsIR4ew:APA91bEyuhRU3gVr93sd-A5Kyls6RD4OhY4wxwfK-hPisTP_zSr21X33XiLHNcPOYPZDJE2tswNvksaCFDBALIdeyWp5WncOID5-QAcqUiHhoh25Xyi6phuvAjke1uJxc7Ys1S9fTsYc","expirationTime":null,"keys":{"p256dh":"BIT1e4n6h29d0NLBjLOrtBDJ69mOXGke3aq9rjRH3OG9cuihV2mvB_8_ATcaYt6pKcVjv8TR5kwfCzSNA1WeAOo","auth":"OOhi3n0xQ9LI-a5k7AsCDg"}}.
         */
        $params = [];
        $content = $request->getContent();
        if (!empty($content)) {
            $params = json_decode($content, true); // 2nd param to get as array
            $endpoint = $params['endpoint'];
            $expirationTime = $params['expiration-time'];
            $p256dhKey = $params['p256dh'];
            $authToken = $params['auth'];
            $contentEncoding = $params['content-encoding'];
        } else {
            $endpoint = $request->request->get('endpoint');
            $expirationTime = $request->request->get('expiration-time');
            $p256dhKey = $request->request->get('p256dh');
            $authToken = $request->request->get('auth');
            $contentEncoding = $params['content-encoding'];
        }

        if (empty(endpoint) || empty($expirationTime) || empty($p256dhKey) || empty($authToken) || empty($contentEncoding)) {
            return new JsonResponse('EMPTY INPUT');
        }
        $sub = Subscription::createInstance($endpoint, $expirationTime, $p256dhKey, $authToken, $contentEncoding);
        $manager = $this->get('doctrine.orm.default_entity_manager');
        $sub->setIndividualMember($member);
        $manager->persist($sub);
        $manager->flush($sub);
        
        return new JsonResponse('OK');
    }

    public function manifestAction($orgSlug, $accessCode, $employeeCode, Request $request)
    {
        $orgRepo = $this->getDoctrine()->getRepository(Organisation::class);

        $org = $orgRepo->findOneBy(['slug' => $orgSlug]);
        if (empty($org)) {
            throw new NotFoundHttpException();
        }
        $response = $this->render('@MagentaCBookAdmin/App/ProgressiveWebApp/manifest.js.twig', [
            'org' => $org,
            'orgSlug' => $orgSlug,
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode,
        ]);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function serviceWorkerAction($orgSlug, Request $request)
    {
        $orgRepo = $this->getDoctrine()->getRepository(Organisation::class);

        $org = $orgRepo->findOneBy(['slug' => $orgSlug]);

        if (empty($org)) {
            throw new NotFoundHttpException();
        }
        $employeeCode = $request->get('employeeCode');
        $accessCode = $request->get('accessCode');

        $response = $this->render('@MagentaCBookAdmin/App/ProgressiveWebApp/service-worker-app.js.twig', [
            'orgSlug' => $orgSlug,
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode,
        ]);
        $response->headers->set('Content-Type', 'application/javascript');

        return $response;
    }
}
