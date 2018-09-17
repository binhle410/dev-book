<?php

namespace Bean\Bundle\PersonBundle\DependencyInjection;

use ProxyManager\FileLocator\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class BeanPersonExtension extends ConfigurableExtension {
	
	// note that this method is called loadInternal and not load
	protected function loadInternal(array $mergedConfig, ContainerBuilder $container) {
//		$loader = new XmlFileLoader($container, new \Symfony\Component\Config\FileLocator(__DIR__ . '/../Resources/config'));

		$container->setParameter('bean_person.backend_type_orm', true);
//		$container->setParameter('bean_person.backend_type_orm_default.base', true);

//		$container->setParameter('bean_person.backend_type_orm_default.inheritance_class', true); // This should be default
		$container->setParameter('bean_person.backend_type_orm_custom.inheritance_superclass', true);

//		$container->setParameter('bean_person.persistence.orm.manager_name', null);
		
		$personClass = $mergedConfig['class']['person'];
		$container->setParameter('bean_person.person_class', $personClass);
		
	}
}
