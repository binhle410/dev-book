<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\AccessControl\ACEntry;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\System\DecisionMakingInterface;
use Magenta\Bundle\CBookModelBundle\Entity\System\SystemModule;

/**
 * @ORM\Entity(repositoryClass="Magenta\Bundle\CBookModelBundle\Repository\User\UserRepository")
 * @ORM\Table(name="user__user")
 */
class User extends AbstractUser {
	/**
	 * @var int|null
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @return int|null
	 */
	public function getId(): ?int {
		return $this->id;
	}
	public function isAdmin(): bool {
		foreach($this->roles as $role) {
			if(in_array($role, [ User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN ])) {
				return true;
			}
		}
		
		return false;
	}
	
	public static function generateCharacterCode($code = null, $x = 4) {
		if(empty($code)) {
			$maxRange36 = '';
			for($i = 0; $i < $x; $i ++) {
				$maxRange36 .= 'Z';
			}
			
			$maxRange = intval(base_convert($maxRange36, 36, 10));
			$code     = base_convert(rand(0, $maxRange), 10, 36);
		}
		
		for($i = 0; $i < $x - strlen($code);) {
			$code = '0' . $code;
		}
		
		return strtoupper($code);
	}
	
	public function isGranted($permission = 'ALL', $object = null, $class = null, IndividualMember $member = null, Organisation $org = null) {
		$permission = strtoupper($permission);
		
		if($permission === 'EXPORT') {
			return true;
		}
		
		if($object instanceof DecisionMakingInterface) {
			if($permission === 'DECISION_' . DecisionMakingInterface::DECISION_APPROVE) {
				//return $object->getDecisionStatus() === null || $object->getDecisionStatus() === DecisionMakingInterface::STATUS_NEW;
				return $object->getDecisionStatus() !== DecisionMakingInterface::STATUS_APPROVED;
			} elseif($permission === 'DECISION_' . DecisionMakingInterface::DECISION_REJECT) {
				return $object->getDecisionStatus() !== DecisionMakingInterface::STATUS_REJECTED;
				//return $object->getDecisionStatus() === null || $object->getDecisionStatus() === DecisionMakingInterface::STATUS_NEW;
			}
			// TODO handle these cases
			if(in_array($permission, [
				'DECIDE',
				'DECIDE_ALL',
				'DECISION_APPROVE',
				'DECISION_REJECT',
				'DECISION_RESET',
			])) {
				return true;
			}
		}
		if( ! empty($org)) {
			switch($class) {
				case Organisation::class:
					return $this->isAdmin();
					break;
			}
			
//			if($this->adminOrganisation === $org) {
//				return true;
//			}
		}
		if( ! empty($member)) {
			$_permission = $permission;
			if($permission === 'LIST') {
				$_permission = ACEntry::PERMISSION_READ;
			}
			if($permission === 'DELETE') {
				$_permission = ACEntry::PERMISSION_DELETE;
			}
			if($permission === 'EDIT') {
				$_permission = ACEntry::PERMISSION_UPDATE;
			}
			if($permission === 'CREATE') {
				$_permission = ACEntry::PERMISSION_CREATE;
			}
			if($permission === 'VIEW') {
				$_permission = ACEntry::PERMISSION_READ;
			}
			
			/** @var Organisation $org */
			$org    = $member->getOrganization();
			$system = $org->getSystem();
			
			$modules = $system->getModules();
			
			/** @var SystemModule $module */
			foreach($modules as $module) {
				if($module->isUserGranted($member, $_permission, $object, $class)) {
					return true;
				}
			}
		}
		
		return false;
	}
	
	/**
	 * @var Person|null
	 * @ORM\OneToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Person\Person", cascade={"persist", "merge"}, inversedBy="user")
	 * @ORM\JoinColumn(name="id_person", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $person;
	
	/**
	 * @return Person|null
	 */
	public function getPerson(): ?Person {
		return $this->person;
	}
	
	/**
	 * @param Person|null $person
	 */
	public function setPerson(?Person $person): void {
		$this->person = $person;
	}
	
}
