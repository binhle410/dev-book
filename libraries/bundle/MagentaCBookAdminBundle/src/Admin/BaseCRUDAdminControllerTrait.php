<?php
namespace Magenta\Bundle\CBookAdminBundle\Admin;

use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Sonata\AdminBundle\Templating\TemplateRegistryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Bridge\Twig\Command\DebugCommand;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\HttpFoundation\Response;

trait BaseCRUDAdminControllerTrait {
	
	protected $templateRegistry;
	
	protected function getTemplateRegistry() {
		$this->templateRegistry = $this->container->get($this->admin->getCode() . '.template_registry');
		if( ! $this->templateRegistry instanceof TemplateRegistryInterface) {
			throw new \RuntimeException(sprintf(
				'Unable to find the template registry related to the current admin (%s)',
				$this->admin->getCode()
			));
		}
		
		return $this->templateRegistry;
	}
	
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
		
		// BC for Symfony < 3.2 where this runtime does not exists
		if (!method_exists(AppVariable::class, 'getToken')) {
			$twig->getExtension(FormExtension::class)
				->renderer->setTheme($formView, $theme);
			
			return;
		}
		
		// BC for Symfony < 3.4 where runtime should be TwigRenderer
		if (!method_exists(DebugCommand::class, 'getLoaderPaths')) {
			$twig->getRuntime(TwigRenderer::class)->setTheme($formView, $theme);
			
			return;
		}
		$twig->getRuntime(FormRenderer::class)->setTheme($formView, $theme);

	}
}
