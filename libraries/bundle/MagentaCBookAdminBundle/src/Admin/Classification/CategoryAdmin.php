<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Classification;

use Bean\Component\Organization\IoC\OrganizationAwareInterface;
use Doctrine\ORM\Query\Expr;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\ClassificationBundle\Admin\CategoryAdmin as SonataCategoryAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\ClassificationBundle\Form\Type\CategorySelectorType;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Valid;

class CategoryAdmin extends SonataCategoryAdmin {
	private $isAdmin;
	private $user;
	
	public function generateUrl($name, array $parameters = array(), $absolute = UrlGeneratorInterface::ABSOLUTE_PATH) {
		$c = $this->getConfigurationPool()->getContainer();
		if( ! empty($orgId = $c->get('request_stack')->getCurrentRequest()->query->get('organisation', 0))) {
			$org = $c->get('doctrine')->getRepository(Organisation::class)->find($orgId);
			if( ! empty($org) && ! array_key_exists('organisation', $parameters)) {
				$parameters['organisation'] = $orgId;
			}
		}

//
		return parent::generateUrl($name, $parameters, $absolute);
	}
	
	
	public function getPersistentParameters() {
		$parameters = parent::getPersistentParameters();
		if( ! $this->hasRequest()) {
			return $parameters;
		}
		if( ! empty($org = $this->getRequest()->get('organisation'))) {
			return array_merge($parameters, array(
				'organisation' => $org
			));
		}
		
		return $parameters;
	}
	
	public
	function createQuery(
		$context = 'list'
	) {
		$query    = parent::createQuery($context);
		$parentFD = $this->getParentFieldDescription();
		if($this->isAdmin()) {
//			if($this->getRequest()->attributes->get('_route') !== 'sonata_admin_retrieve_autocomplete_items') {
			// admin should see everything except in embeded forms
			if(in_array($this->getClass(), [
					Organisation::class,
					User::class
				]) || ! empty($parentFD) && $parentFD->getType() !== ModelAutocompleteType::class) {
				return $query;
			}
		}
		
		$organisation = $this->getCurrentOrganisation();
		
		if( ! empty($organisation)) { // && ! empty($organisation)
			if(in_array(OrganizationAwareInterface::class, class_implements($this->getClass()))) {
				$this->filterQueryByOrganisation($query, $organisation);
			}
		} else {
			// TODO: change this so that 1 person can manage multiple organisations
			$this->clearResults($query);
		}
		
		return $query;
//        $query->andWhere()
	}
	
	/**
	 * @param ProxyQuery $query
	 *
	 * @return ProxyQuery
	 */
	protected
	function clearResults(
		ProxyQuery $query
	) {
		/** @var Expr $expr */
		$expr = $query->getQueryBuilder()->expr();
		$query->andWhere($expr->eq($expr->literal(true), $expr->literal(false)));
		
		return $query;
	}
	
	/**
	 * @return Organisation|null
	 */
	protected
	function getCurrentOrganisation(
		$required = true
	) {
		if( ! empty($orgId = $this->getRequest()->query->getInt('organisation', 0))) {
			$org = $this->getConfigurationPool()->getContainer()->get('doctrine')->getRepository(Organisation::class)->find($orgId);
		} else {
			$org = $this->getCurrentOrganisationFromAncestors($this->getParent());
		}
		
		if(empty($org)) {
			$user = $this->getLoggedInUser();
//			if(empty($org = $user->getAdminOrganisation())) {
//				if( ! empty($person = $user->getPerson())) {
//					/** @var IndividualMember $m */
//					$m = $person->getMembers()->first();
//					if( ! empty($m)) {
//						$org = $m->getOrganization();
//					}
//				}
//			}
		}
		
		if(empty($org)) {
			if($required) {
				throw new UnauthorizedHttpException('Unauthorised access');
			}
		}
		
		return $org;
	}
	
	protected function getCurrentOrganisationFromAncestors(AdminInterface $parent = null) {
		if(empty($parent)) {
			return null;
		}
		if($parent instanceof OrganisationAdmin) {
			return $parent->getSubject();
		}
		$grandpa = $parent->getParent();
		if($grandpa instanceof OrganisationAdmin) {
			return $grandpa->getSubject();
		} else {
			return $this->getCurrentOrganisationFromAncestors($grandpa);
		}
	}
	
	protected
	function getLoggedInUser() {
		if($this->user === null) {
			$this->user = $this->getConfigurationPool()->getContainer()->get(UserService::class)->getUser();
		}
		
		return $this->user;
	}
	
	protected
	function isAdmin() {
		if($this->isAdmin === null) {
			$this->isAdmin = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
		}
		
		return $this->isAdmin;
	}
	
	protected
	function filterQueryByOrganisation(
		ProxyQuery $query, Organisation $organisation
	) {
		$pool      = $this->getConfigurationPool();
		$request   = $this->getRequest();
		$container = $pool->getContainer();
		/** @var Expr $expr */
		$expr         = $query->getQueryBuilder()->expr();
		$orgFieldName = $this->getOrganisationFieldName($this->getClass());
		
		return $query->andWhere($expr->eq('o.' . $orgFieldName, $organisation->getId()));
	}
	
	protected function getOrganisationFieldName($class) {
		return 'organisation';
	}
	
	protected function configureFormFields(FormMapper $formMapper) {
		parent::configureFormFields($formMapper);
		$formMapper->remove('parent');
		$formMapper->remove('media');
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
		           ->add('accessDeniedGroups', ModelType::class, [
			           'btn_add'  => false,
			           'required' => false,
			           'property' => 'name',
			           'multiple' => true
		
		           ])
		           ->end();
		
		$formMapper
			->add('parent', CategorySelectorType::class, [
				'category'      => $this->getSubject() ?: null,
				'model_manager' => $this->getModelManager(),
				'class'         => $this->getClass(),
				'required'      => true,
				'context'       => $this->getSubject()->getContext(),
				'btn_add'       => false
			]);
		
		$keys = $formMapper->keys();
		$key  = array_pop($keys);
		array_unshift($keys, $key);
		$formMapper->reorder($keys);
	}
}
