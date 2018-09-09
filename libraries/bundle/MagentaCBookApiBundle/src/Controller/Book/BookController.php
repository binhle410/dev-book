<?php
namespace Magenta\Bundle\CBookApiBundle\Controller\Book;

use Doctrine\Common\Collections\Collection;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BookController extends Controller {
	
	public function booksAction(Organisation $data): Collection {
		$books =  $data->getBooks();
		return $books;
	}
}
