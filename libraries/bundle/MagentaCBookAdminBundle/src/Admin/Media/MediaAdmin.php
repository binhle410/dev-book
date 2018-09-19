<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Media;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdminTrait;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Service\Classification\CategoryManager;

class MediaAdmin extends \Sonata\MediaBundle\Admin\ORM\MediaAdmin {
	use BaseAdminTrait {
		getOrganisationFieldName as protected getOrganisationFieldNameTrait;
		getPersistentParameters as protected getPersistentParametersTrait;
//		configureRoutes as protected configureRoutesTrait;
//		configureFormFields as protected configureFormFieldsTrait;
	}
	
	/** @var \Sonata\MediaBundle\Model\CategoryManager $categoryManager */
	protected $categoryManager;
	
	public function getPersistentParameters() {
		$this->categoryManager->setOrganisation($this->getCurrentOrganisation());
		
		return $this->getPersistentParametersTrait();
	}
	
	protected function getOrganisationFieldName($class) {
		return 'organisation';
	}
	
}
