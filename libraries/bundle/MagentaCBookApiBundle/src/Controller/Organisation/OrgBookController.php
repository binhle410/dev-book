<?php
namespace Magenta\Bundle\CBookApiBundle\Controller\Organisation;

use Doctrine\Common\Collections\Collection;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\OrgBook;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrgBookController extends Controller {
	
	public function booksAction(Organisation $data): Collection {
		$books =  $data->getBooks();
		return $books;
	}
}