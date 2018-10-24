<?php

namespace Magenta\Bundle\CBookAdminBundle\Controller;

use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


class BookPWAController extends Controller
{
    public function manifestAction($orgSlug, Request $request)
    {
        $orgRepo = $this->getDoctrine()->getRepository(Organisation::class);

        $org = $orgRepo->findOneBy(['slug' => $orgSlug]);
        if (empty($org)) {
            throw new NotFoundHttpException();
        }
        return $this->render('@MagentaCBookAdmin/Book/ProgressWebApp/manifest.html.twig', [
            'org' => $org,
            'orgSlug' => $orgSlug
        ]);
    }

    public function serviceWorkerAction($orgSlug, Request $request)
    {
        $orgRepo = $this->getDoctrine()->getRepository(Organisation::class);

        $org = $orgRepo->findOneBy(['slug' => $orgSlug]);

        if (empty($org)) {
            throw new NotFoundHttpException();
        }
        return $this->render('@MagentaCBookAdmin/Book/ProgressWebApp/service-worker-app.html.twig');
    }
}