<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem;

use Bean\Component\Book\Model\Book as BookModel;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem;

/**
 * @ORM\Entity()
 * @ORM\Table(name="classification__category_item__book")
 */
class BookCategoryItem extends CategoryItem {
	
	function __construct() {
		parent::__construct();
	}
	
	public function getType() {
		return self::TYPE_BOOK;
	}
	
	/**
	 * @var Book
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Book\Book", inversedBy="bookCategoryItems")
	 * @ORM\JoinColumn(name="id_thing", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $item;
}
