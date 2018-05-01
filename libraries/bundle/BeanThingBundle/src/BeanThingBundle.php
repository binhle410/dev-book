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
				'bean_thing.backend_type_orm_default.inheritance_superclass',
				[ 'BeanThingBundle' => 'Bean\Component\Thing\Model' ]
			)
		);
		
		$container->addCompilerPass(
			DoctrineOrmMappingsPass::createXmlMappingDriver(
				[
					realpath(__DIR__ . '/Resources/config/doctrine-model/orm-class') => 'Bean\Component\Thing\Model',
				],
				[ 'bean_thing.persistence.orm.manager_name' ],
				'bean_thing.backend_type_orm_default.inheritance_class',
				[ 'BeanThingBundle' => 'Bean\Component\Thing\Model' ]
			)
		);
		
		$container->addCompilerPass(
			DoctrineOrmMappingsPass::createXmlMappingDriver(
				[
					realpath(__DIR__ . '/Resources/config/doctrine-model/orm-single') => 'Bean\Component\Thing\Model',
				],
				[ 'bean_thing.persistence.orm.manager_name' ],
				'bean_thing.backend_type_orm_default.inheritance_single',
				[ 'BeanThingBundle' => 'Bean\Component\Thing\Model' ]
			)
		);
	}
}
