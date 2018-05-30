<?php

namespace Magenta\Bundle\CBookApiBundle\Controller\Organisation;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\OrgBook;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrgChapterController extends Controller {
	
	public function bookChaptersAction(OrgBook $data): Collection {
		$chapters = $data->getChapters();
		
		return $chapters;
	}
	
	public function bookRootChaptersAction(OrgBook $data): Collection {
		/** @var ArrayCollection $chapters */
		$chapters = $data->getChapters();
		
		$criteria = Criteria::create();
		$expr     = Criteria::expr();
		
		$criteria->where(
			$expr->isNull('parentChapter')
		);
		
		return $chapters->matching($criteria);
	}
}