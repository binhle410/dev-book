<?php
namespace Magenta\Bundle\CBookModelBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BaseService
{
    /** @var ContainerInterface $container */
    protected $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function get($service)
    {
        return $this->container->get($service);
    }

    protected function getParameter($parameter)
    {
        return $this->container->getParameter($parameter);
    }
	
	/**
	 * Shortcut to return the Doctrine Registry service.
	 *
	 * @return Registry
	 *
	 * @throws \LogicException If DoctrineBundle is not available
	 */
	protected function getDoctrine()
	{
		if (!$this->container->has('doctrine')) {
			throw new \LogicException('The DoctrineBundle is not registered in your application.');
		}
		
		return $this->container->get('doctrine');
	}
	
    public function getRequest()
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param string $route The name of the route
     * @param mixed $parameters An array of parameters
     * @param int $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     *
     * @see UrlGeneratorInterface
     */
    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }
}