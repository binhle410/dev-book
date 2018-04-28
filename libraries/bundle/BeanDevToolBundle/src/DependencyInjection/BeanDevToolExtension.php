<?php

namespace Bean\Bundle\DevToolBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class BeanDevToolExtension extends ConfigurableExtension {
	
	// note that this method is called loadInternal and not load
	protected function loadInternal(array $mergedConfig, ContainerBuilder $container) {
		$loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
		
		$loader->load('services.xml');
		
		$container->setParameter('bean_dev_tool.library_source', $mergedConfig['library_source']);
		$container->setParameter('bean_dev_tool.library_workspace', $mergedConfig['library_workspace']);
		$container->setParameter('bean_dev_tool.bundles', []);
		$container->setParameter('bean_dev_tool.components', []);
		$container->setParameter('bean_dev_tool.sites', []);
		
		if(array_key_exists('bundles', $mergedConfig)) {
			$container->setParameter('bean_dev_tool.bundles', $mergedConfig['bundles']);
		}
		if(array_key_exists('components', $mergedConfig)) {
			$container->setParameter('bean_dev_tool.components', $mergedConfig['components']);
		}
		if(array_key_exists('sites', $mergedConfig)) {
			$container->setParameter('bean_dev_tool.sites', $mergedConfig['sites']);
		}

//		$definition = $container->getDefinition('bean_dev_tool.library_src');
//		echo $definition;
//		$definition->replaceArgument(0, $config['twitter']['client_id']);
//		$definition->replaceArgument(1, $config['twitter']['client_secret']);
	}
}