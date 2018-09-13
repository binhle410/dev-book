<?php

namespace Bean\Bundle\OrganizationBundle\DependencyInjection;

use ProxyManager\FileLocator\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class BeanOrganizationExtension extends ConfigurableExtension {
	
	// note that this method is called loadInternal and not load
	protected function loadInternal(array $mergedConfig, ContainerBuilder $container) {
//		$loader = new XmlFileLoader($container, new \Symfony\Component\Config\FileLocator(__DIR__ . '/../Resources/config'));

		$container->setParameter('bean_organization.backend_type_orm', true);
//		$container->setParameter('bean_organization.backend_type_orm_default.base', true);

//		$container->setParameter('bean_organization.backend_type_orm_default.inheritance_class', true); // This should be default
		$container->setParameter('bean_organization.backend_type_orm_custom.inheritance_superclass', true);

//		$container->setParameter('bean_organization.persistence.orm.manager_name', null);
		
		$organizationClass = $mergedConfig['class']['organization'];
		$individualMemberClass = $mergedConfig['class']['individual_member'];
		$container->setParameter('bean_organization.organization_class', $organizationClass);
		$container->setParameter('bean_organization.individual_member_class', $individualMemberClass);
		
		
	}
}
