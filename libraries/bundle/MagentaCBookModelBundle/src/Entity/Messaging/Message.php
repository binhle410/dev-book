<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Messaging;

use Bean\Component\Organization\Model\IndividualMember as MemberModel;

use Bean\Component\Thing\Model\Thing;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\AccessControl\ACRole;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;

/**
 * @ORM\Entity()
 * @ORM\Table(name="messaging__message")
 */
class Message extends \Bean\Component\Messaging\Model\Message {

	/**
	 * @var Organisation|null
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="messageTemplates", cascade={"persist","merge"})
	 * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $organisation;

}
