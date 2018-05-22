<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Magenta\Bundle\CBookModelBundle\Event;

use Magenta\Bundle\CBookModelBundle\Entity\User\UserInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class UserEvent extends Event
{
	/**
	 * @var null|Request
	 */
	protected $request;
	
	/**
	 * @var UserInterface
	 */
	protected $user;
	
	/**
	 * UserEvent constructor.
	 *
	 * @param UserInterface $user
	 * @param Request|null  $request
	 */
	public function __construct(UserInterface $user, Request $request = null)
	{
		$this->user = $user;
		$this->request = $request;
	}
	
	/**
	 * @return UserInterface
	 */
	public function getUser()
	{
		return $this->user;
	}
	
	/**
	 * @return Request
	 */
	public function getRequest()
	{
		return $this->request;
	}
}