<?php

namespace Magenta\Bundle\CBookApiBundle\Controller\Organisation;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndividualMemberController extends Controller {
	
	public function contactableMembersAction(Organisation $data): Collection {
		
		$criteria = Criteria::create();
		$expr     = Criteria::expr();
		
		$criteria->where(
			$expr->eq('contactable', true)
		);
		
		return $data->getIndividualMembers()->matching($criteria);
	}
}
