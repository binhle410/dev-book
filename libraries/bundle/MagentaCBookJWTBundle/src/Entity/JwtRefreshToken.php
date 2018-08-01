<?php
namespace Magenta\Bundle\CBookJWTBundle\Entity;

use Gesdinet\JWTRefreshTokenBundle\Entity\AbstractRefreshToken;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class override Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken to have another table name.
 *
 * @ORM\Table("user__token")
 * @ORM\Entity(repositoryClass="Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository")
 * @UniqueEntity("refreshToken")
 */
class JwtRefreshToken extends AbstractRefreshToken
{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * {@inheritdoc}
	 */
	public function getId()
	{
		$this->id;
	}
}