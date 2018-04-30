<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Bean\Bundle\BookBundle\Doctrine\Orm\Chapter as BaseChapter;

/**
 * @ApiResource
 */
class Chapter extends BaseChapter {
	
}