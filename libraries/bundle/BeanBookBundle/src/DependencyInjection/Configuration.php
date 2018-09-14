<?php

namespace Bean\Bundle\BookBundle\DependencyInjection;

use Bean\Bundle\BookBundle\Doctrine\Orm\Book;
use Bean\Bundle\BookBundle\Doctrine\Orm\BookPage;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {
	public function getConfigTreeBuilder() {
		$treeBuilder = new TreeBuilder();
		$rootNode    = $treeBuilder->root('bean_book');
		
		$rootNode
			->children()
				->arrayNode('class')
					->children()
						->scalarNode('book')->defaultValue(Book::class)
						->end()
						->scalarNode('book_page')->defaultValue(BookPage::class)
						->end()
					->end()
				->end()
			->end()
		->end();
		return $treeBuilder;
	}
}
