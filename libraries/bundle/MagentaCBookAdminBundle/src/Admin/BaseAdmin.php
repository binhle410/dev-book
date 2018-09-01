<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin;

use Magenta\Bundle\CBookAdminBundle\Admin\Organisation\OrganisationAdmin;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class BaseAdmin extends AbstractAdmin {
	
	const AUTO_CONFIG = true;
	const ENTITY = null;
	const CONTROLLER = null;
	
	/**
	 * sample array [ OrgBookAdmin::class => 'organisation' ];
	 */
	const CHILDREN = null;
	
	protected $ivoryCkeditor = array();
	
	protected $translationDomain = 'MagentaCBookAdminBundle'; // default is 'messages'
	
	protected $action = '';
	protected $actionParams = [];
	
	
	private $isAdmin;
	
	protected function getCurrentOrganisationFromAncestors(BaseAdmin $parent = null) {
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
	
	protected function getCurrentOrganisationMember($required = false) {
		$user   = $this->getLoggedInUser();
		$person = $user->getPerson();
		if(empty($person)) {
			return null;
		}
		
		return $person->getMemberOfOrganisation($this->getCurrentOrganisation());
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
			if(empty($org = $user->getAdminOrganisation())) {
				if( ! empty($person = $user->getPerson())) {
					/** @var OrganisationMember $m */
					$m = $person->getMembers()->first();
					if( ! empty($m)) {
						$org = $m->getOrganization();
					}
				}
			}
		}
		
		if(empty($org)) {
			if($required) {
				throw new UnauthorizedHttpException('Unauthorised access');
			}
		}
		
		return $org;
	}
	
	protected
	function getLoggedInUser() {
		if($this->user === null) {
			$this->user = $this->getConfigurationPool()->getContainer()->get(UserService::class)->getUser();
		}
		
		return $this->user;
	}
	
	protected function isAdmin() {
		if($this->isAdmin === null) {
			$this->isAdmin = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
		}
		
		return $this->isAdmin;
	}

	public function getTemplate($name) {
		return $this->getTemplateRegistry()->getTemplate($name);
	}
	
	protected function getChildrenConst() {
		return self::CHILDREN;
	}
	
	/**
	 * @param int $namHoc
	 */
	public function setNamHoc($namHoc) {
		$this->namHoc = $namHoc;
	}
	
	
	public function getAction() {
		if(empty($this->action)) {
			$request = $this->getRequest();
			if( ! empty($action = $request->query->get('action'))) {
				
				$this->action = $action;
				
			}
		}
		
		return $this->action;
	}
	
	public function getActionParam($key) {
		if(array_key_exists($key, $this->actionParams)) {
			return $this->actionParams[ $key ];
		}
		
		return null;
	}
	
	/**
	 * @return array
	 */
	public function getActionParams() {
		return $this->actionParams;
	}
	
	/**
	 * @param array $actionParams
	 */
	public function setActionParams($actionParams) {
		$this->actionParams = $actionParams;
	}
	
	public function setAction($action) {
		$this->action = $action;
	}
	
	public function toString($object) {
		if(method_exists($object, 'getTitle')) {
			return $object->getTitle();
		} elseif(method_exists($object, 'getName')) {
			return $object->getName();
		}
		
		if($object instanceof PhanBo) {
			return $object->getThanhVien()->getName();
		}
		
		if($object instanceof DoiNhomGiaoLy) {
			$str = 'Đội của ';
			$str .= $object->getTenCacTruongPhuTrach();
			
			return $str;
		}
		
		return parent::toString($object);
	}
	
	protected function getUserThanhVien() {
		if(empty($this->thanhVien)) {
			$container       = $this->getConfigurationPool()->getContainer();
			$user            = $container->get(UserService::class)->getUser();
			$this->thanhVien = $user->getThanhVien();
		}
		
		return $this->thanhVien;
	}
	
	protected function getUserChiDoan() {
		
		return $this->getUserThanhVien()->getChiDoan();
		
	}
	
	public function getRequest() {
		if( ! $this->request) {
//            throw new \RuntimeException('The Request object has not been set');
			$this->request = $this->getConfigurationPool()->getContainer()->get('request_stack')->getCurrentRequest();
		}
		
		return $this->request;
	}
	
	/**
	 * @param ProxyQuery $query
	 *
	 * @return ProxyQuery
	 */
	protected function clearResults(ProxyQuery $query) {
		/** @var Expr $expr */
		$expr = $query->getQueryBuilder()->expr();
		$query->andWhere($expr->eq($expr->literal(true), $expr->literal(false)));
		
		return $query;
	}
	
	protected function verifyDirectParent($parent) {
	}
	
	protected function isDirectParentAccess($parentClass, $subjectAdminCodes = array()) {
		$parentAdmin          = $this->getParent();
		$isDirectParentAccess = false;
		if( ! empty($parentAdmin)) {
			$_parentClass         = $parentAdmin->getClass();
			$isDirectParentAccess = $parentClass === $_parentClass;
			if( ! empty($subjectAdminCodes)) {
				$isDirectParentAccess = $isDirectParentAccess && (in_array($this->getCode(), $subjectAdminCodes));
			}
		}
		
		return $isDirectParentAccess;
	}
	
	protected function isAppendFormElement() {
		$request = $this->getRequest();
		
		return $request->attributes->get('_route') === 'sonata_admin_append_form_element';
	}
	
	protected function filterByParentClass(ProxyQuery $query, $parentClass, $subjectAdminCodes = array()) {
		$pool      = $this->getConfigurationPool();
		$request   = $this->getRequest();
		$container = $pool->getContainer();
		/** @var Expr $expr */
		$expr = $query->getQueryBuilder()->expr();
		
		$isDirectParentAccess = $this->isDirectParentAccess($parentClass, $subjectAdminCodes);
		$parentAdmin          = $this->getParent();
		$rootAlias            = $query->getRootAliases()[0];
		if($isDirectParentAccess) {
			if($this->verifyDirectParent($parentAdmin->getSubject())) {
				$query->andWhere($expr->eq($rootAlias . '.' . $this->getParentAssociationMapping(), $parentAdmin->getSubject()->getId()));
				
				return $query;
			};
		} else {
			if($this->isAppendFormElement()) {
				// Indirect Parent
				$code     = $request->query->get('code');
				$objectId = $request->query->get('objectId');
				
				if(in_array($code, $subjectAdminCodes)) {
					/** @var AdminInterface $childAdmin */
					$childAdmin                       = $pool->getAdminByAdminCode($code);
					$child                            = $this->getModelManager()->find($childAdmin->getClass(), $objectId);
					$indirectParentAssociationMapping = $childAdmin->getParentAssociationMapping();
					$parentGetter                     = 'get' . ucfirst($indirectParentAssociationMapping);
					$indirectParent                   = $child->{$parentGetter}();
//                    definitely null =>
//                    $indirectParentAdmin = $childAdmin->getParent();
					if(get_class($indirectParent) === $parentClass) {
						$query->andWhere($expr->eq($rootAlias . '.' . $childAdmin->getParentAssociationMapping(), $indirectParent->getId()));
						
						return $query;
					}
				}
				
			} else {
				// Indirect Parent but with direct access NOT Ajax call
				$childAdmin = $pool->getAdminByAdminCode($request->attributes->get('_sonata_admin'));
				$query->andWhere($expr->eq($rootAlias . '.' . $childAdmin->getParentAssociationMapping(), $request->attributes->get('id')));
				
				return $query;
			}
		}
		
		return $this->clearResults($query);
	}
	
	/**
	 * @return array
	 */
	public function getIvoryCkeditor() {
		return $this->ivoryCkeditor;
	}
	
	/**
	 * @param array $ivoryCkeditor
	 */
	public function setIvoryCkeditor($ivoryCkeditor) {
		$this->ivoryCkeditor = $ivoryCkeditor;
	}
}