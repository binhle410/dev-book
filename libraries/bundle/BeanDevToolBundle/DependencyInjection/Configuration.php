<?php

namespace Bean\Bundle\DevToolBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {
	public function getConfigTreeBuilder() {
		$treeBuilder = new TreeBuilder();
		$rootNode    = $treeBuilder->root('bean_dev_tool');
		
		$rootNode
			->children()
//			->arrayNode('book')
			//			->children()
				//			->arrayNode('classes')->end()
			//			->end()
//			->end()// book
			->scalarNode('library_source')->end()
			->scalarNode('library_workspace')->end()
			->end();
		
		return $treeBuilder;
	}
}