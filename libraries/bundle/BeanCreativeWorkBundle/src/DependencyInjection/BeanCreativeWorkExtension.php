<?php

namespace Bean\Bundle\CreativeWorkBundle\DependencyInjection;

use ProxyManager\FileLocator\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\Routing\Loader\XmlFileLoader;

class BeanCreativeWorkExtension extends ConfigurableExtension {
	
	// note that this method is called loadInternal and not load
	protected function loadInternal(array $mergedConfig, ContainerBuilder $container) {
		$container->setParameter('bean_creativework.backend_type_orm', true);
		$container->setParameter('bean_creativework.backend_type_orm_default.superclass', true);
		$container->setParameter('bean_creativework.persistence.orm.manager_name', null);
		
		
	}
}