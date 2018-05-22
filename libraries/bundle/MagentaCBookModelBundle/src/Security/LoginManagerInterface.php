<?php
/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Magenta\Bundle\CBookModelBundle\Security;

use Magenta\Bundle\CBookModelBundle\Entity\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;

interface LoginManagerInterface
{
	/**
	 * @param string        $firewallName
	 * @param UserInterface $user
	 * @param Response|null $response
	 */
	public function logInUser($firewallName, UserInterface $user, Response $response = null);
}