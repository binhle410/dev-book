<?php

namespace Magenta\Bundle\CBookMediaApiBundle\Form\Type;

use Magenta\Bundle\CBookMediaApiBundle\Form\DataTransformer\OrganisationDataTransformer;
use Sonata\MediaBundle\Form\Type\ApiMediaType as SonataApiMediaType;

use Sonata\MediaBundle\Form\DataTransformer\ProviderDataTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @deprecated
 * Class ApiMediaType
 * @package Magenta\Bundle\CBookMediaApiBundle\Form\Type
 */
class ApiMediaType extends SonataApiMediaType {
	
	/** @var OrganisationDataTransformer $orgTransformer */
	private $orgTransformer;
	
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		parent::buildForm($builder, $options);
		$builder->get('organisation')->addModelTransformer($this->orgTransformer);
	}
	
	/**
	 * @param OrganisationDataTransformer $orgTransformer
	 */
	public function setOrgTransformer(OrganisationDataTransformer $orgTransformer): void {
		$this->orgTransformer = $orgTransformer;
	}
}
