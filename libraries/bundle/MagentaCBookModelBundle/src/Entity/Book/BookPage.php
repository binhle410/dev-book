<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Book;

use Bean\Component\Book\Model\Book as BookModel;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\BookCategoryItem;

/**
 * @ORM\Entity()
 * @ORM\Table(name="book__page")
 */
class BookPage extends \Bean\Bundle\BookBundle\Doctrine\Orm\BookPage {
	
	function __construct() {
		parent::__construct();
		$this->locale = 'en';
	}

//	/**
//	 * @ORM\OneToMany(targetEntity="Chapter", cascade={"persist","merge"}, orphanRemoval=true, mappedBy="page")
//	 */
//	protected $chapters;
//
//	/**
//	 * @var Collection
//	 * @ORM\OneToMany(targetEntity="Bean\Component\CreativeWork\Model\CreativeWork", cascade={"persist","merge"}, orphanRemoval=true, mappedBy="partOf")
//	 */
//	protected $parts;
//
//	/**
//	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="books")
//	 * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
//	 */
//	protected $organisation;

//	/**
//	 * @var boolean|null
//	 * @ORM\Column(type="boolean", nullable=true)
//	 */
//	protected $abridged;
//
//	/**
//	 * @var integer|null
//	 * @ORM\Column(type="integer", nullable=true)
//	 */
//	protected $numberOfPages;
//
//	/**
//	 * @var string|null
//	 * @ORM\Column(type="string", nullable=true)
//	 */
//	protected $bookEdition;
//
//	/**
//	 * @var string|null
//	 * @ORM\Column(type="string", nullable=true)
//	 */
//	protected $bookFormat;
//
//	/**
//	 * @var string|null
//	 * @ORM\Column(type="string", nullable=true)
//	 */
//	protected $isbn;

}
