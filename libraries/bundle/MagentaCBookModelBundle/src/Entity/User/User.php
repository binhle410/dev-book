<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
	
	const ROLE_ADMIN = 'ROLE_ADMIN';
	const ROLE_POWER_USER = 'ROLE_POWER_USER';
	
	/**
	 * @var int|null
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	public function __construct() {
		parent::__construct();
		$this->adminOrganisations = new ArrayCollection();
	}


    public static function generateTimestampBasedCode(\DateTime $date = null) {
        if($date === null) {
            $timestamp = base_convert((integer) date_timestamp_get(new \DateTime()), 10, 36);
        } else {
            $timestamp = base_convert($date->getTimestamp(), 10, 36);
        }
        for($i = 0; $i < 8 - strlen($timestamp);) {
            $timestamp = '0' . $timestamp;
        }

        $tsStr = substr(chunk_split($timestamp, 4, "-"), 0, - 1);

        return strtoupper($tsStr);
    }

    public static function generate4DigitCode($code = null) {
        if(empty($code)) {
            $code = base_convert(rand(0, 1679615), 10, 36);
        }
        for($i = 0; $i < 4 - strlen($code);) {
            $code = '0' . $code;
        }

        return strtoupper($code);
    }

    public static function generateXDigitCode($code = null, $x) {
        if(empty($code)) {
            $maxBase36 = '';
            for($i = 0; $i < $x; $i ++) {
                $maxBase36 .= 'z';
            }

            $maxBase10 = base_convert($maxBase36, 36, 10);

            $code = base_convert(rand(0, $maxBase10), 10, 36);
        }

        for($i = 0; $i < $x - strlen($code);) {
            $code = '0' . $code;
        }

        return strtoupper($code);
    }
	
	public function isAdminOfOrganisation(Organisation $org) {
		/** @var Organisation $org */
		foreach($this->adminOrganisations as $org) {
			if($org === $org) {
				return true;
			}
		}
		
		return false;
	}
	
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
			
			if($this->isAdminOfOrganisation($org)) {
				return true;
			}
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

//	For UserAdmin
	
	/**
	 * @return array
	 */
	public function getRealRoles() {
		return $this->roles;
	}
	
	/**
	 * @param array $roles
	 *
	 * @return User
	 */
	public function setRealRoles(array $roles) {
		$this->setRoles($roles);
		
		return $this;
	}
	
	/**
	 * @var Collection $adminOrganisations
	 * @ORM\ManyToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="adminUsers")
	 * @ORM\JoinTable(name="organisation__organisation__organisations_admin_users",
	 *      joinColumns={@ORM\JoinColumn(name="id_user", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="id_organisation", referencedColumnName="id")}
	 *      )
	 */
	protected $adminOrganisations;
	
	public function addAdminOrganisation(Organisation $org) {
		$this->adminOrganisations->add($org);
	}
	
	public function removeAdminOrganisation(Organisation $org) {
		$this->adminOrganisations->removeElement($org);
	}
	
	/**
	 * @var Person|null
	 * @ORM\OneToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Person\Person", inversedBy="user")
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
	
	/**
	 * @return Collection
	 */
	public function getAdminOrganisations(): Collection {
		return $this->adminOrganisations;
	}
	
	/**
	 * @param Collection $adminOrganisations
	 */
	public function setAdminOrganisations(Collection $adminOrganisations): void {
		$this->adminOrganisations = $adminOrganisations;
	}
}
