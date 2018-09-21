<?php

namespace Magenta\Bundle\CBookModelBundle\DependencyInjection;

use Bean\Component\Thing\Model\Thing;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Chapter;
use ProxyManager\FileLocator\FileLocator;
use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class MagentaCBookModelExtension extends ConfigurableExtension implements CompilerPassInterface {
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
		$loader->load('app.yaml');
//		$loader->load('parameters.yaml');
		
		$loader->load('doctrine.yaml');
		
		$definition = $container->getDefinition('magenta_user.object_manager');
		$definition->setFactory(array( new Reference('magenta_user.doctrine_registry'), 'getManager' ));
	}
	
	/**
	 * You can modify the container here before it is dumped to PHP code.
	 *
	 * @param ContainerBuilder $container
	 */
	public function process(ContainerBuilder $container) {
//        sonata.media.provider.image
		$definition = $container->getDefinition('sonata.classification.manager.category');
		$definition->addArgument(new Reference('service_container'));
		
		$definition = $container->getDefinition('sonata.media.manager.media');
		$definition->addMethodCall('setContainer', [ new Reference('service_container') ]);
	}
}
