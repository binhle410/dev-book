<?php

namespace Bean\Bundle\ThingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {
	public function getConfigTreeBuilder() {
		$treeBuilder = new TreeBuilder();
		$rootNode    = $treeBuilder->root('bean_thing');
		
		// @formatter:off
		$rootNode
			->children()
				->arrayNode('persistence')
					->addDefaultsIfNotSet()
					->validate()
						->ifTrue(function ($v) {
							return count(array_filter($v, function ($persistence) {
									return $persistence['enabled'];
								})) > 1;
						})
						->thenInvalid('Only one persistence layer can be enabled at the same time.')
					->end() // validate / addDefaultsIfNotSet
					->children()
						->arrayNode('phpcr')
							->addDefaultsIfNotSet()
							->canBeEnabled()
							->fixXmlConfig('route_basepath')
							->children()
								->scalarNode('manager_name')->defaultNull()->end()
								->arrayNode('route_basepaths')
									->beforeNormalization()
									->ifString()
									->then(function ($v) {
										return [$v];
										})
									->end()
									->prototype('scalar')->end()
									->defaultValue(['/bean/thing'])
								->end() // creative-works basepaths
								->booleanNode('enable_initializer')
									->defaultValue(true)
								->end()
							->end()
						->end() // phpcr
						->arrayNode('orm')
							->addDefaultsIfNotSet()
							->canBeEnabled()
							->children()
								->scalarNode('manager_name')->defaultNull()->end()
								->scalarNode('inheritance_strategy')->defaultValue('single')->end()
//								->arrayNode('classes')
//									->scalarNode('creative_work')->defaultNull()->end()
//								->end()
							->end()
						->end() // orm
					->end() // children
				->end() // persistence
			->end(); // children of root
		// @formatter:on
		
		return $treeBuilder;
	}
}