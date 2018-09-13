<?php

namespace Bean\Bundle\OrganizationBundle;

use Doctrine\Common\Persistence\Mapping\Driver\DefaultFileLocator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\JsonLoginFactory;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Compiler\AddSecurityVotersPass;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Compiler\AddSessionDomainConstraintPass;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginLdapFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\HttpBasicFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\HttpBasicLdapFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\RememberMeFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\X509Factory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\RemoteUserFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SimplePreAuthenticationFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SimpleFormFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\InMemoryFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\GuardAuthenticationFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\LdapFactory;

// CompilePasses
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\ORM\Version as ORMVersion;
use Doctrine\ORM\Mapping\Driver\XmlDriver as ORMXmlDriver;
use Doctrine\ODM\PHPCR\Mapping\Driver\XmlDriver as PHPCRXmlDriver;
use Doctrine\ODM\PHPCR\Version as PHPCRVersion;

/**
 * Bundle.
 *
 * @author Binh
 */
class BeanOrganizationBundle extends Bundle {
	public function build(ContainerBuilder $container) {
		parent::build($container);
		$this->buildOrmCompilerPass($container);
		
	}
	
	public function registerCommands(Application $application) {
		// noop
	}
	
	/**
	 * Creates and registers compiler passes for ORM mappings if both doctrine
	 * ORM and a suitable compiler pass implementation are available.
	 *
	 * @param ContainerBuilder $container
	 */
	private function buildOrmCompilerPass(ContainerBuilder $container) {
		if( ! class_exists(ORMVersion::class)) {
			return;
		}

//		$container->addCompilerPass(
//			$this->buildBaseCompilerPass(DoctrineOrmMappingsPass::class, ORMXmlDriver::class, 'orm')
//		);
		
		$container->addCompilerPass(
			DoctrineOrmMappingsPass::createXmlMappingDriver(
				[
//					realpath(__DIR__ . '/Resources/config/doctrine-model/orm-superclass') => 'Bean\Component\Book\Model',
					realpath(__DIR__ . '/Resources/config/doctrine-orm/class') => 'Bean\Bundle\OrganizationBundle\Doctrine\Orm',
				],
				[ 'bean_book.persistence.orm.manager_name' ],
				'bean_book.backend_type_orm_default.inheritance_class',
				[ 'BeanBookBundle' => 'Bean\Bundle\BookBundle\Doctrine\Orm' ]
			)
		);
		
		$container->addCompilerPass(
			DoctrineOrmMappingsPass::createXmlMappingDriver(
				[
//					realpath(__DIR__ . '/Resources/config/doctrine-model/orm-superclass') => 'Bean\Component\Book\Model',
					realpath(__DIR__ . '/Resources/config/doctrine-orm/superclass') => 'Bean\Bundle\OrganizationBundle\Doctrine\Orm',
				],
				[ 'bean_book.persistence.orm.manager_name' ],
				'bean_book.backend_type_orm_default.inheritance_superclass',
				[ 'BeanBookBundle' => 'Bean\Bundle\BookBundle\Doctrine\Orm' ]
			)
		);
		
//		$bookClass = $container->getParameter('bean_book.book_class');
		$container->addCompilerPass(
			DoctrineOrmMappingsPass::createXmlMappingDriver(
				[
					realpath(__DIR__ . '/Resources/config/doctrine-model/orm-superclass') => 'Bean\Component\Organization\Model'
				],
				[ 'bean_organization.persistence.orm.manager_name' ],
				'bean_organization.backend_type_orm_custom.inheritance_superclass',
				[ 'BeanOrganizationBundle' => '%bean_organization.organization_class%' ]
			)
		);
	}
	
	/**
	 * Builds the compiler pass for the symfony core routing component. The
	 * compiler pass factory method uses the SymfonyFileLocator which does
	 * magic with the namespace and thus does not work here.
	 *
	 * @param string $compilerClass the compiler class to instantiate
	 * @param string $driverClass the xml driver class for this backend
	 * @param string $type the backend type name
	 *
	 * @return CompilerPassInterface
	 */
	private function buildBaseCompilerPass($compilerClass, $driverClass, $type) {
		$arguments = [ [ realpath(__DIR__ . '/Resources/config/doctrine-base') ], sprintf('.%s.xml', $type) ];
		$locator   = new Definition(DefaultFileLocator::class, $arguments);
		$driver    = new Definition($driverClass, [ $locator ]);
		
		return new $compilerClass(
			$driver,
			[ 'Bean\Component\Book\Model' ],
			[ sprintf('bean_book.persistence.%s.manager_name', $type) ],
			sprintf('bean_book.backend_type_%s', $type)
		);
	}
}
