<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Media;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdminTrait;
use Magenta\Bundle\CBookAdminBundle\Form\Type\OrgAwareCategorySelectorType;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Context;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Service\Classification\CategoryManager;
use Sonata\AdminBundle\Form\FormMapper;

class MediaAdmin extends \Sonata\MediaBundle\Admin\ORM\MediaAdmin {
	use BaseAdminTrait {
		getOrganisationFieldName as protected getOrganisationFieldNameTrait;
		getPersistentParameters as protected getPersistentParametersTrait;
//		configureRoutes as protected configureRoutesTrait;
//		configureFormFields as protected configureFormFieldsTrait;
	}
	
	/** @var CategoryManager $categoryManager */
	protected $categoryManager;
	
	public function getPersistentParameters() {
		$this->categoryManager->setOrganisation($this->getCurrentOrganisation());
		
		return $this->getPersistentParametersTrait();
	}
	
	protected function getOrganisationFieldName($class) {
		return 'organisation';
	}
	
	protected function configureFormFields(FormMapper $formMapper) {
		parent::configureFormFields($formMapper);
		$formMapper->remove('category');
		
		$container = $this->getConfigurationPool()->getContainer();
		$registry  = $container->get('doctrine');
		
		/**
		 * @var Media $media
		 */
		if(empty($media = $this->getSubject())) {
			$contextStr = $this->getRequest()->get('context', Context::DEFAULT_CONTEXT);
		} else {
			$contextStr = $media->getContext();
		}
		/** @var Context $defaultContext */
		$defaultContext      = $registry->getRepository(Context::class)->find($contextStr);
		$rootCategory        = $registry->getRepository(Category::class)->findOneBy([
			'context' => $defaultContext,
			'parent'  => null
		]);
		$currentOrganisation = $this->getCurrentOrganisation();
		$formMapper->add('category', OrgAwareCategorySelectorType::class, [
			'label'         => 'form.label_name',
			'organisation'  => $currentOrganisation,
			'category'      => $rootCategory,
			'model_manager' => $container->get('sonata.classification.admin.category')->getModelManager(),
			'class'         => Category::class,
			'required'      => true,
			'context'       => $defaultContext,
			'btn_add'       => false
		]);
	}
}
