<?php
/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Magenta\Bundle\CBookAdminBundle\Tests\User\Event\Listener;

use Magenta\Bundle\CBookAdminBundle\Entity\User\UserInterface;
use Magenta\Bundle\CBookAdminBundle\Event\FilterUserResponseEvent;
use Magenta\Bundle\CBookAdminBundle\EventListener\AuthenticationListener;
use Magenta\Bundle\CBookAdminBundle\Event\MagentaUserEvents;
use Magenta\Bundle\CBookAdminBundle\Security\LoginManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationListenerTest extends TestCase {
	const FIREWALL_NAME = 'foo';
	
	/** @var EventDispatcherInterface */
	private $eventDispatcher;
	
	/** @var FilterUserResponseEvent */
	private $event;
	
	/** @var AuthenticationListener */
	private $listener;
	
	public function setUp() {
		$user = $this->getMockBuilder(UserInterface::class)->getMock();
		
		$response    = $this->getMockBuilder(Response::class)->getMock();
		$request     = $this->getMockBuilder(Request::class)->getMock();
		$this->event = new FilterUserResponseEvent($user, $request, $response);
		
		$this->eventDispatcher = $this->getMockBuilder(EventDispatcher::class)->getMock();
		$this->eventDispatcher
			->expects($this->once())
			->method('dispatch');
		
		$loginManager = $this->getMockBuilder(LoginManagerInterface::class)->getMock();
		
		$this->listener = new AuthenticationListener($loginManager, self::FIREWALL_NAME);
	}
	
	public function testAuthenticate() {
		$this->listener->authenticate($this->event, MagentaUserEvents::REGISTRATION_COMPLETED, $this->eventDispatcher);
	}
}