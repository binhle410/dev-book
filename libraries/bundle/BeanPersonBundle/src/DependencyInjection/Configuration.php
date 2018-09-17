<?php

namespace Bean\Bundle\PersonBundle\DependencyInjection;

use Bean\Bundle\PersonBundle\Doctrine\Orm\Person;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {
	public function getConfigTreeBuilder() {
		$treeBuilder = new TreeBuilder();
		$rootNode    = $treeBuilder->root('bean_person');
		
		$rootNode
			->children()
				->arrayNode('class')
					->children()
						->scalarNode('person')->defaultValue(Person::class)
						->end()
					->end()
				->end()
			->end()
		->end();
		return $treeBuilder;
	}
}
