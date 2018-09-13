<?php

namespace Bean\Bundle\OrganizationBundle\DependencyInjection;

use Bean\Bundle\OrganizationBundle\Doctrine\Orm\IndividualMember;
use Bean\Bundle\OrganizationBundle\Doctrine\Orm\Organization;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {
	public function getConfigTreeBuilder() {
		$treeBuilder = new TreeBuilder();
		$rootNode    = $treeBuilder->root('bean_organization');
		
		$rootNode
			->children()
				->arrayNode('class')
					->children()
						->scalarNode('organization')
							->defaultValue(Organization::class)
						->end()
					
						->scalarNode('individual_member')
							->defaultValue(IndividualMember::class)
						->end()
					->end() // end children of arrayNode class
				->end()
			->end()
		->end() // end rootNode
		
		
		;
		
		return $treeBuilder;
	}
}
