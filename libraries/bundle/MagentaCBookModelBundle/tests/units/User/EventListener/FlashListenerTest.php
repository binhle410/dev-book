<?php
/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Magenta\Bundle\CBookModelBundle\Tests\User\EventListener;

use Magenta\Bundle\CBookModelBundle\EventListener\FlashListener;
use Magenta\Bundle\CBookModelBundle\Event\MagentaUserEvents;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class FlashListenerTest extends TestCase {
	/** @var Event */
	private $event;
	
	/** @var FlashListener */
	private $listener;
	
	public function setUp() {
		$this->event = new Event();
		
		$flashBag = $this->getMockBuilder(FlashBag::class)->getMock();
		
		$session = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
		$session
			->expects($this->once())
			->method('getFlashBag')
			->willReturn($flashBag);
		
		$translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
		
		$this->listener = new FlashListener($session, $translator);
	}
	
	public function testAddSuccessFlash() {
		$this->listener->addSuccessFlash($this->event, MagentaUserEvents::CHANGE_PASSWORD_COMPLETED);
	}
}