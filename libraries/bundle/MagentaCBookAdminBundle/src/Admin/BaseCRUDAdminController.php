<?php
namespace Magenta\Bundle\CBookAdminBundle\Admin;

use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Symfony\Component\Form\FormView;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;

class BaseCRUDAdminController extends CRUDController {
	
	protected function getRefererParams() {
		$request = $this->getRequest();
		$referer = $request->headers->get('referer');
		$baseUrl = $request->getBaseUrl();
		if(empty($baseUrl)) {
			return null;
		}
		$lastPath = substr($referer, strpos($referer, $baseUrl) + strlen($baseUrl));
		
		return $this->get('router')->match($lastPath);
//		getMatcher()
	}
	
	protected function isAdmin() {
		return $this->get(UserService::class)->getUser()->isAdmin();
	}
	
	/**
	 * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
	 *
	 * @param FormView $formView
	 * @param string   $theme
	 */
	protected function setFormTheme(FormView $formView, $theme) {
		$twig = $this->get('twig');
		
		try {
			$twig
				->getRuntime('Symfony\Bridge\Twig\Form\TwigRenderer')
				->setTheme($formView, $theme);
		} catch(\Twig_Error_Runtime $e) {
			// BC for Symfony < 3.2 where this runtime not exists
			$twig
				->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')
				->renderer
				->setTheme($formView, $theme);
		}
	}
	
}