<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Organisation;

use Bean\Component\Organization\Model\Organization as OrganizationModel;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;

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
	}
	
	/**
	 * @ORM\OneToMany(
	 *     targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\MemberGroup",
	 *     mappedBy="organisation", cascade={"persist"}, orphanRemoval=true
	 * )
	 * @ORM\OrderBy({"position"="ASC"})
	 *
	 * @var Collection $memberGroups ;
	 */
	protected $memberGroups;
	
	public function addMemberGroup(MemberGroup $group) {
		$this->memberGroups->add($group);
		$group->setOrganisation($this);
	}
	
	public function removeMemberGroup(MemberGroup $group) {
		$this->memberGroups->removeElement($group);
		$group->setOrganisation(null);
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
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\OrganisationMember", mappedBy="organization")
	 */
	protected $members;
	
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
	 * @return string
	 */
	public function getCode(): string {
		return $this->code;
	}
	
	/**
	 * @param string $code
	 */
	public function setCode(string $code): void {
		$this->code = $code;
	}
	
}
