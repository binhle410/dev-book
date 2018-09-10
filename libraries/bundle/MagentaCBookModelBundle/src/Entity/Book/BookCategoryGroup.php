<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Book;

use Bean\Component\Book\Model\Book as BookModel;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\MemberGroup;

/**
 * @ORM\Entity()
 * @ORM\Table(name="book__book_categories_groups")
 */
class BookCategoryGroup {
	/**
	 * @var integer|null
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * // Serializer\Groups(groups={"sonata_api_read", "sonata_api_write", "sonata_search"})
	 */
	protected $id;
	
	/**
	 * @return int|null
	 */
	public function getId(): ?int {
		return $this->id;
	}
	
	function __construct() {
	
	}
	
	/**
	 * @var MemberGroup
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\MemberGroup", inversedBy="bookCategoryGroups")
	 * @ORM\JoinColumn(name="id_group", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $group;
	
	/**
	 * @var BookCategory
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Book\BookCategory", inversedBy="bookCategoryGroups")
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
	 * @return BookCategory
	 */
	public function getCategory(): BookCategory {
		return $this->category;
	}
	
	/**
	 * @param BookCategory $category
	 */
	public function setCategory(BookCategory $category): void {
		$this->category = $category;
	}
}
