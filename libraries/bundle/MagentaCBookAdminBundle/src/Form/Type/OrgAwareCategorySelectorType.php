<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Magenta\Bundle\CBookAdminBundle\Form\Type;

use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\ClassificationBundle\Form\ChoiceList\CategoryChoiceLoader;
use Sonata\ClassificationBundle\Form\Type\CategorySelectorType;
use Sonata\ClassificationBundle\Model\CategoryInterface;
use Sonata\ClassificationBundle\Model\CategoryManagerInterface;
use Sonata\ClassificationBundle\Serializer\CategorySerializerHandler;
use Sonata\CoreBundle\Model\ManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Select a category.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class OrgAwareCategorySelectorType extends CategorySelectorType {
	public function configureOptions(OptionsResolver $resolver) {
		parent::configureOptions($resolver);
		$resolver->setDefault('organisation', null);
	}
	
	/**
	 * @param Options $options
	 *
	 * @return array
	 */
	public function getChoices(Options $options) {
		if( ! $options['category'] instanceof CategoryInterface) {
			return [];
		}
		$organisation = $options['organisation'];
		if(null === $options['context']) {
			$categories = $this->manager->getAllRootCategories(true, $organisation);
		} else {
			$categories = $this->manager->getRootCategoriesForContext($options['context'], $organisation);
		}
		
		$choices = [];
		
		foreach($categories as $category) {
			$choices[ $category->getId() ] = sprintf('%s (%s)', $category->getName(), $category->getContext()->getId());
			
			$this->childWalker($category, $options, $choices);
		}
		
		return $choices;
	}
	
	/**
	 * copied from parent
	 *
	 * @param CategoryInterface $category
	 * @param Options           $options
	 * @param array             $choices
	 * @param int               $level
	 */
	private function childWalker(CategoryInterface $category, Options $options, array &$choices, $level = 2) {
		if(null === $category->getChildren()) {
			return;
		}
		
		foreach($category->getChildren() as $child) {
			if($options['category'] && $options['category']->getId() == $child->getId()) {
				continue;
			}
			
			$choices[ $child->getId() ] = sprintf('%s %s', str_repeat('-', 1 * $level), $child);
			
			$this->childWalker($child, $options, $choices, $level + 1);
		}
	}
}
