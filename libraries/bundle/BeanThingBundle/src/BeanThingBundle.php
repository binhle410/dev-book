<?php

namespace Bean\Bundle\ThingBundle;

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
class BeanThingBundle extends Bundle {
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
					realpath(__DIR__ . '/Resources/config/doctrine-model/orm-superclass') => 'Bean\Component\Thing\Model',
				],
				[ 'bean_thing.persistence.orm.manager_name' ],
				'bean_thing.backend_type_orm_default.superclass',
				[ 'BeanThingBundle' => 'Bean\Component\Thing\Model' ]
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
			[ 'Symfony\Component\Routing' ],
			[ sprintf('cmf_routing.dynamic.persistence.%s.manager_name', $type) ],
			sprintf('cmf_routing.backend_type_%s', $type)
		);
	}
}
