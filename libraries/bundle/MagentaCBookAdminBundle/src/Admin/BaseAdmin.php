<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin;

use Bean\Component\Organization\IoC\OrganizationAwareInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr;
use Magenta\Bundle\CBookAdminBundle\Admin\Organisation\OrganisationAdmin;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualGroup;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\System\DecisionMakingInterface;
use Magenta\Bundle\CBookModelBundle\Entity\System\FullTextSearchInterface;
use Magenta\Bundle\CBookModelBundle\Entity\System\SystemModule;
use Bean\Component\Thing;

use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Admin\FieldDescription;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BaseAdmin extends AbstractAdmin {
	const AUTO_CONFIG = true;
	const ENTITY = null;
	const CONTROLLER = null;
	const CHILDREN = null;
	const ADMIN_CODE = null;
	
	protected
		$translationDomain = 'MagentaCBookAdminBundle'; // default is 'messages'
	
	use BaseAdminTrait;
}
