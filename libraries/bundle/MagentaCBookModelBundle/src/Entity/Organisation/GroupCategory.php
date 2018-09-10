<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Organisation;

use Bean\Component\Person\Model\Person;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organisation__member_groups_categories")
 */
class GroupCategory {
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
	
	/**
	 * @var MemberGroup
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\MemberGroup", inversedBy="groupCategories")
	 * @ORM\JoinColumn(name="id_group", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $group;
	
	/**
	 * @var Category
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Classification\Category", inversedBy="groupCategories")
	 * @ORM\JoinColumn(name="id_category", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $category;
	
	/**
	 * @return MemberGroup
	 */
	public function getGroup(): MemberGroup {
		return $this->group;
	}
	
	/**
	 * @param MemberGroup $group
	 */
	public function setGroup(MemberGroup $group): void {
		$this->group = $group;
	}
	
	/**
	 * @return Category
	 */
	public function getCategory(): Category {
		return $this->category;
	}
	
	/**
	 * @param Category $category
	 */
	public function setCategory(Category $category): void {
		$this->category = $category;
	}
}
