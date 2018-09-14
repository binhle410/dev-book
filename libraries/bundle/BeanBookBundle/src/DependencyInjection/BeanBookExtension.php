<?php

namespace Bean\Bundle\BookBundle\DependencyInjection;

use ProxyManager\FileLocator\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class BeanBookExtension extends ConfigurableExtension {
	
	// note that this method is called loadInternal and not load
	protected function loadInternal(array $mergedConfig, ContainerBuilder $container) {
//		$loader = new XmlFileLoader($container, new \Symfony\Component\Config\FileLocator(__DIR__ . '/../Resources/config'));

		$container->setParameter('bean_book.backend_type_orm', true);
//		$container->setParameter('bean_book.backend_type_orm_default.base', true);

//		$container->setParameter('bean_book.backend_type_orm_default.inheritance_class', true); // This should be default
		$container->setParameter('bean_book.backend_type_orm_custom.inheritance_superclass', true);

//		$container->setParameter('bean_book.persistence.orm.manager_name', null);
		
		$bookClass = $mergedConfig['class']['book'];
		$pageClass = $mergedConfig['class']['book_page'];
		$container->setParameter('bean_book.book_class', $bookClass);
		$container->setParameter('bean_book.book_page_class', $pageClass);
		
		
	}
}
