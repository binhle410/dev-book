<?php

namespace Bean\Bundle\ThingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\Loader\LoaderInterface;

class BeanThingExtension extends ConfigurableExtension {
	
	// note that this method is called loadInternal and not load
	protected function loadInternal(array $mergedConfig, ContainerBuilder $container) {
		$loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		// TODO: Take it or drop it
		$hasProvider          = false;
		$hasContentRepository = false;
		if($mergedConfig['persistence']['phpcr']['enabled']) {
			$this->loadPhpcrProvider($mergedConfig['persistence']['phpcr'], $loader, $container);
			$hasProvider = $hasContentRepository = true;
		}
		if($mergedConfig['persistence']['orm']['enabled']) {
			$this->loadOrmProvider($mergedConfig['persistence']['orm'], $loader, $container);
			$hasProvider = $hasContentRepository = true;
		}
		if( ! $hasProvider) {
			throw new InvalidConfigurationException('you need to either enable one of the persistence layers for BeanThingBundle or remove the bundles altogether.');
		}
	}
	
	private function loadOrmProvider(array $config, LoaderInterface $loader, ContainerBuilder $container) {
//		$loader->load('provider-orm.xml');
		$container->setParameter('bean_thing.backend_type_orm', true);
		$container->setParameter('bean_thing.persistence.orm.manager_name', $config['manager_name']);
		if(empty($inheritanceStrategy = $config['inheritance_strategy'])) {
			$inheritanceStrategy = 'single';
		}
		if(in_array($inheritanceStrategy, [
			'superclass',
			'class',
			'single'
		])) {
			$container->setParameter(sprintf('bean_thing.backend_type_orm_default.inheritance_%s', $inheritanceStrategy), true);
		} else {
			$container->setParameter('bean_thing.backend_type_orm_custom', true);
		}
		
	}
	
	private function loadPhpcrProvider(array $config, LoaderInterface $loader, ContainerBuilder $container) {
	}
}