<?php

namespace Magenta\Bundle\CBookAdminBundle;

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
class MagentaCBookAdminBundle extends Bundle {
	public function build(ContainerBuilder $container) {
		parent::build($container);
	}
	
	public function registerCommands(Application $application) {
		// noop
	}
	
}
