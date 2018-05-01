<?php

namespace Bean\Bundle\CreativeWorkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\Loader\LoaderInterface;

class BeanCreativeWorkExtension extends ConfigurableExtension {
	
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
			throw new InvalidConfigurationException('you need to either enable one of the persistence layers for BeanCreativeWorkBundle or remove the bundles altogether.');
		}
	}
	
	private function loadOrmProvider(array $config, LoaderInterface $loader, ContainerBuilder $container) {
//		$loader->load('provider-orm.xml');
		$container->setParameter('bean_creativework.backend_type_orm', true);
		$container->setParameter('bean_creativework.persistence.orm.manager_name', $config['manager_name']);
		if(empty($inheritanceStrategy = $config['inheritance_strategy'])) {
			$inheritanceStrategy = 'class';
		}
		if(in_array($inheritanceStrategy, [
			'superclass',
			'class',
			'single'
		])) {
			$container->setParameter(sprintf('bean_creativework.backend_type_orm_default.inheritance_%s', $inheritanceStrategy), true);
		} else {
			$container->setParameter('bean_creativework.backend_type_orm_custom', true);
		}
//		$container->setParameter('', true);
//		$container->setParameter('', null);
	
	}
	
	private function loadPhpcrProvider(array $config, LoaderInterface $loader, ContainerBuilder $container) {
//		$loader->load('provider-phpcr.xml');
		$container->setParameter('cmf_routing.backend_type_phpcr', true);
		$container->setParameter('cmf_routing.dynamic.persistence.phpcr.route_basepaths', array_values(array_unique($config['route_basepaths'])));
		$container->setParameter('cmf_routing.dynamic.persistence.phpcr.manager_name', $config['manager_name']);
		
		if(true === $config['enable_initializer']) {
			$this->loadInitializer($loader, $container);
		}
	}
}