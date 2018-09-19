<?php

namespace Magenta\Bundle\CBookAdminBundle\Service\Organisation;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookAdminBundle\Admin\Organisation\OrganisationAdmin;
use Magenta\Bundle\CBookModelBundle\Service\ServiceContext;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Service\BaseService;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class OrganisationService extends BaseService {
	
	public function getCurrentOrganisation(ServiceContext $context, $required = true) {
		if( ! empty($orgId = $this->getRequest()->query->getInt('organisation', 0))) {
			$org = $this->container->get('doctrine')->getRepository(Organisation::class)->find($orgId);
		} else {
			if($context->getType() === ServiceContext::TYPE_ADMIN_CLASS) {
				$org = $this->getCurrentOrganisationFromAncestors($context->getAttribute('parent'));
			}
		}
		
		if(empty($org)) {
			$user = $this->container->get(UserService::class)->getUser();
			
			if(empty($org = $user->getAdminOrganisations()->first())) {
				if( ! empty($person = $user->getPerson())) {
					/** @var IndividualMember $m */
					$m = $person->getIndividualMembers()->first();
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
	
	public function getCurrentOrganisationFromAncestors(BaseAdmin $parent = null) {
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
}
