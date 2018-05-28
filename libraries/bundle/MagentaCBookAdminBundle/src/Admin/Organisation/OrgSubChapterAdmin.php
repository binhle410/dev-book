<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Organisation;

use Bean\Component\Book\Model\Chapter;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\OrgChapter;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Magenta\Bundle\CBookModelBundle\Service\User\UserManager;
use Magenta\Bundle\CBookModelBundle\Service\User\UserManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrgSubChapterAdmin extends OrgChapterAdmin {
	
	protected $baseRouteName = 'subchapter';
	
	protected $baseRoutePattern = 'sub-chapter';
	const ENTITY = OrgChapter::class;
	
	const CHILDREN = null;
	
	public function toString($object) {
		return $object instanceof Chapter
			? $object->getName()
			: 'Sub-Section'; // shown in the breadcrumb on the create view
	}
	
}