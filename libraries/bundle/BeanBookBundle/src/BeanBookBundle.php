<?php

namespace Bean\Bundle\BookBundle;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\JsonLoginFactory;
use Symfony\Component\Console\Application;
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
class BeanBookBundle extends Bundle {
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
		$container->addCompilerPass(
			DoctrineOrmMappingsPass::createXmlMappingDriver(
				[
					realpath(__DIR__ . '/Resources/config/doctrine-model/orm-superclass') => 'Bean\Component\Book\Model',
					realpath(__DIR__ . '/Resources/config/doctrine-orm')                  => 'Bean\Bundle\BookBundle\Doctrine\Orm',
				],
				[ 'bean_book.persistence.orm.manager_name' ],
				'bean_book.backend_type_orm_default.superclass',
				[ 'BeanBookBundle' => 'Bean\Component\Book\Doctrine\Orm' ]
			)
		);
	}
}
