<?php

namespace Magenta\Bundle\CBookJWTBundle\DependencyInjection;

use ProxyManager\FileLocator\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class MagentaCBookJWTExtension extends ConfigurableExtension {
	// note that this method is called loadInternal and not load
	protected function loadInternal(array $mergedConfig, ContainerBuilder $container) {
		$loader = new YamlFileLoader($container, new \Symfony\Component\Config\FileLocator(__DIR__ . '/../Resources/config'));

//		$container->registerForAutoconfiguration(BaseCRUDAdminController::class)
//		          ->addTag('controller.service_arguments');
		
		$loader->load('api.yaml');
		
	}
}