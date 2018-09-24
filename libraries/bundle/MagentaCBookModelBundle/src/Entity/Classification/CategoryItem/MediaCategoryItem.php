<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem;

use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;

/**
 * @ORM\Entity()
 * @ORM\Table(name="classification__category_item__media")
 */
class MediaCategoryItem extends CategoryItem {
	
	function __construct() {
		parent::__construct();
	}
	
	public function getType() {
		return self::TYPE_MEDIA;
	}
	
	/**
	 * @var Media
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Media\Media", inversedBy="mediaCategoryItems")
	 * @ORM\JoinColumn(name="id_thing", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $item;
}
