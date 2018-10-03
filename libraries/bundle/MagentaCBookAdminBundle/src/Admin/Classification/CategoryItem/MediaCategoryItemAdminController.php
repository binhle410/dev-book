<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Classification\CategoryItem;

use Bean\Component\Thing\Model\Thing;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminControllerTrait;
use Magenta\Bundle\CBookAdminBundle\Admin\Classification\CategoryItemAdminController;
use Magenta\Bundle\CBookAdminBundle\Service\Organisation\OrganisationService;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\MediaCategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Service\ServiceContext;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\CategoryItemContainerInterface;

class MediaCategoryItemAdminController extends CategoryItemAdminController {
	/** @var MediaCategoryItemAdmin $admin */
	protected $admin;
	
	public function createItem(): CategoryItemContainerInterface {
		$mediaFormBuilder = $this->createFormBuilder();
		$mediaFormBuilder->add('media', MediaType::class, array(
			'provider' => 'sonata.media.provider.file',
			'context'  => 'default'
		));
		
		$mediaForm = $mediaFormBuilder->getForm();
		
		$request = $this->getRequest();
		$mediaForm->handleRequest($request);
		$data  = $mediaForm->getData();
		$media = $data['media'];
		
		return $media;
	}
	
	public function createCategoryItem(): CategoryItem {
		return new MediaCategoryItem();
	}
	
	public function createResponse(CategoryItem $item): Response {
		return new RedirectResponse($this->get('sonata.classification.admin.category')->generateUrl('tree', [ 'parent' => $item->getCategory()->getId() ]));
	}
	
}
