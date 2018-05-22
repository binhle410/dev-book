<?php

namespace Magenta\Bundle\CBookModelBundle\DependencyInjection;

use ProxyManager\FileLocator\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class MagentaCBookModelExtension extends ConfigurableExtension {
	/**
	 * @var array
	 */
	private static $doctrineDrivers = array(
		'orm'     => array(
			'registry' => 'doctrine',
			'tag'      => 'doctrine.event_subscriber',
		),
		'mongodb' => array(
			'registry' => 'doctrine_mongodb',
			'tag'      => 'doctrine_mongodb.odm.event_subscriber',
		),
		'couchdb' => array(
			'registry'       => 'doctrine_couchdb',
			'tag'            => 'doctrine_couchdb.event_subscriber',
			'listener_class' => 'FOS\UserBundle\Doctrine\CouchDB\UserListener',
		),
	);
	
	// note that this method is called loadInternal and not load
	protected function loadInternal(array $mergedConfig, ContainerBuilder $container) {
		$loader = new YamlFileLoader($container, new \Symfony\Component\Config\FileLocator(__DIR__ . '/../Resources/config'));
		
		$container->setAlias('magenta_user.doctrine_registry', new Alias(self::$doctrineDrivers['orm']['registry'], false));
		
		$loader->load('user.yaml');
		
		$definition = $container->getDefinition('magenta_user.object_manager');
		$definition->setFactory(array( new Reference('magenta_user.doctrine_registry'), 'getManager' ));
		
	}
}