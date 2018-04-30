<?php

namespace Bean\Bundle\CreativeWorkBundle;

use Doctrine\Common\Persistence\Mapping\Driver\DefaultFileLocator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\JsonLoginFactory;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
class BeanCreativeWorkBundle extends Bundle {
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
					realpath(__DIR__ . '/Resources/config/doctrine-model/orm-superclass') => 'Bean\Component\CreativeWork\Model',
				],
				[ 'bean_creativework.persistence.orm.manager_name' ],
				'bean_creativework.backend_type_orm_default.inheritance_superclass',
				[ 'BeanCreativeWorkBundle' => 'Bean\Component\CreativeWork\Model' ]
			)
		);
		
		$container->addCompilerPass(
			DoctrineOrmMappingsPass::createXmlMappingDriver(
				[
					realpath(__DIR__ . '/Resources/config/doctrine-model/orm-class') => 'Bean\Component\CreativeWork\Model',
				],
				[ 'bean_creativework.persistence.orm.manager_name' ],
				'bean_creativework.backend_type_orm_default.inheritance_class',
				[ 'BeanCreativeWorkBundle' => 'Bean\Component\CreativeWork\Model' ]
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
			[ 'Bean\Component\CreativeWork\Model' ],
			[ sprintf('bean_creativework.persistence.%s.manager_name', $type) ],
			sprintf('bean_creativework.backend_type_%s', $type)
		);
	}
	
}
