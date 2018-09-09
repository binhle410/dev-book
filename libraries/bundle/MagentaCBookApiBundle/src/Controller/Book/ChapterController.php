<?php

namespace Magenta\Bundle\CBookApiBundle\Controller\Book;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Chapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ChapterController extends Controller {
	
	public function bookChaptersAction(Book $data): Collection {
		$chapters = $data->getChapters();
		
		return $chapters;
	}
	
	public function bookRootChaptersAction(Book $data): Collection {
		/** @var ArrayCollection $chapters */
		$chapters = $data->getChapters();
		
		$criteria = Criteria::create();
		$expr     = Criteria::expr();
		
		$criteria->where(
			$expr->isNull('parentChapter')
		);
		
		return $chapters->matching($criteria);
	}
	
	public function subChaptersAction(Chapter $data): Collection {
		return $data->getSubChapters();
	}
}
