<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Organisation;

use Bean\Component\Organization\Model\Organization as OrganizationModel;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\System\System;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organisation__organisation")
 */
class Organisation extends OrganizationModel {
	
	/**
	 * @var int|null
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	function __construct() {
		parent::__construct();
		$this->books        = new ArrayCollection();
		$this->categories   = new ArrayCollection();
		$this->memberGroups = new ArrayCollection();
		$this->adminUsers   = new ArrayCollection();
		$this->members      = new ArrayCollection();
	}
	
	/**
	 * @ORM\ManyToMany(
	 *     targetEntity="Magenta\Bundle\CBookModelBundle\Entity\User\User",
	 *     mappedBy="adminOrganisations", cascade={"persist","merge"}
	 * )
	 *
	 * @var Collection $adminUsers ;
	 */
	protected $adminUsers;
	
	public function addAdminUser(User $user) {
		$this->adminUsers->add($user);
		$user->addAdminOrganisation($this);
	}
	
	public function removeAdminUser(User $user) {
		$this->adminUsers->removeElement($user);
		$user->removeAdminOrganisation($this);
	}
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl\ACRole", mappedBy="organisation", cascade={"persist","merge"}, orphanRemoval=true)
	 */
	protected $roles;
	
	/**
	 * @ORM\OneToMany(
	 *     targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualGroup",
	 *     mappedBy="organization", cascade={"persist"}, orphanRemoval=true
	 * )
	 * @ORM\OrderBy({"position"="ASC"})
	 *
	 * @var Collection $memberGroups ;
	 */
	protected $memberGroups;
	
	public function addMemberGroup(IndividualGroup $group) {
		$this->memberGroups->add($group);
		$group->setOrganization($this);
	}
	
	public function removeMemberGroup(IndividualGroup $group) {
		$this->memberGroups->removeElement($group);
		$group->setOrganization(null);
	}
	
	/**
	 * @ORM\OneToMany(
	 *     targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Classification\Category",
	 *     mappedBy="organisation", cascade={"persist"}, orphanRemoval=true
	 * )
	 * @ORM\OrderBy({"position"="ASC"})
	 *
	 * @var Collection $categories ;
	 */
	protected $categories;
	
	public function addCategory(Category $category) {
		$this->categories->add($category);
		$category->setOrganisation($this);
	}
	
	public function removeCategory(Category $category) {
		$this->categories->removeElement($category);
		$category->setOrganisation(null);
	}
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Book\Book", cascade={"persist","merge"}, orphanRemoval=true, mappedBy="organisation")
	 */
	protected $books;
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember", mappedBy="organization")
	 */
	protected $members;
	
	/**
	 * @var System|null
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\System\System", inversedBy="organisations", cascade={"persist","merge"})
	 * @ORM\JoinColumn(name="id_system", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $system;
	
	/**
	 * @var Media
	 * @ORM\OneToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Media\Media", mappedBy="logoOrganisation", cascade={"persist","merge"})
	 */
	protected $logo;
	
	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $code;
	
	/**
	 * @return Media
	 */
	public function getLogo(): Media {
		return $this->logo;
	}
	
	/**
	 * @param Media $logo
	 */
	public function setLogo(Media $logo): void {
		$this->logo = $logo;
	}
	
	/**
	 * @return Collection
	 */
	public function getBooks(): Collection {
		return $this->books;
	}
	
	/**
	 * @param Collection $books
	 */
	public function setBooks(Collection $books): void {
		$this->books = $books;
	}
	
	/**
	 * @return string|null
	 */
	public function getCode(): ?string {
		return $this->code;
	}
	
	/**
	 * @param string|null $code
	 */
	public function setCode(string $code = null): void {
		$this->code = $code;
	}
	
	/**
	 * @return Collection
	 */
	public function getMembers() {
		return $this->members;
	}
	
	/**
	 * @param Collection $members
	 */
	public function setMembers($members): void {
		$this->members = $members;
	}
	
	/**
	 * @return Collection
	 */
	public function getAdminUsers(): Collection {
		return $this->adminUsers;
	}
	
	/**
	 * @param Collection $adminUsers
	 */
	public function setAdminUsers(Collection $adminUsers): void {
		$this->adminUsers = $adminUsers;
	}
	
	/**
	 * @return Collection
	 */
	public function getRoles(): Collection {
		return $this->roles;
	}
	
	/**
	 * @param Collection $roles
	 */
	public function setRoles(Collection $roles): void {
		$this->roles = $roles;
	}
}
