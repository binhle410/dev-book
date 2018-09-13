<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Classification;

use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\MemberGroup;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\ClassificationBundle\Admin\CategoryAdmin as SonataCategoryAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\ClassificationBundle\Form\Type\CategorySelectorType;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Valid;

class CategoryAdmin extends SonataCategoryAdmin {
	protected function configureFormFields(FormMapper $formMapper) {
		parent::configureFormFields($formMapper);
		/** @var Category $subject */
		$subject  = $this->getSubject();
		$position = $this->hasSubject() && null !== $this->getSubject()->getPosition() ? $this->getSubject()->getPosition() : 0;
		$formMapper->with('Options', [])
		           ->add('enabled', CheckboxType::class, [
			           'required' => false,
		           ])
		           ->add('accessGrantedGroups', ModelType::class, [
			           'btn_add'  => false,
			           'required' => false,
			           'property' => 'name',
			           'multiple' => true
		
		           ])
		           ->end();
	}
}
