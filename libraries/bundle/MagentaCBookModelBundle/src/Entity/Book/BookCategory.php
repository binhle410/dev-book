<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Book;

use Bean\Component\Book\Model\Book as BookModel;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;

/**
 * @ORM\Entity()
 * @ORM\Table(name="book__books_categories")
 */
class BookCategory {
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
	 * @ORM\OneToMany(
	 *     targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\MemberGroup",
	 *     mappedBy="ca", cascade={"persist"}, orphanRemoval=true
	 * )
	 * @ORM\OrderBy({"position"="ASC"})
	 *
	 * @var Collection $memberGroups ;
	 */
	protected $memberGroups;
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Book\BookCategoryGroup", mappedBy="group")
	 */
	protected $bookCategoryGroups;
	
	public function addBookCategoryGroup(BookCategoryGroup $bkg) {
		$this->bookCategoryGroups->add($bkg);
		$bkg->setCategory($this);
	}
	
	public function removeBookCategoryGroup(BookCategoryGroup $bkg) {
		$this->bookCategoryGroups->removeElement($bkg);
		$bkg->setCategory(null);
	}
	
	/**
	 * @var Category
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Classification\Category", inversedBy="bookCategories")
	 * @ORM\JoinColumn(name="id_category", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $category;
	
	/**
	 * @var Book
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Book\Book", inversedBy="bookCategories")
	 * @ORM\JoinColumn(name="id_book", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $book;
	
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
	
	/**
	 * @return Book
	 */
	public function getBook(): Book {
		return $this->book;
	}
	
	/**
	 * @param Book $book
	 */
	public function setBook(Book $book): void {
		$this->book = $book;
	}
}
