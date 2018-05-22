<?php
/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Magenta\Bundle\CBookModelBundle\EventListener;

use Magenta\Bundle\CBookModelBundle\Event\MagentaUserEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class FlashListener implements EventSubscriberInterface
{
	/**
	 * @var string[]
	 */
	private static $successMessages = array(
		MagentaUserEvents::CHANGE_PASSWORD_COMPLETED => 'change_password.flash.success',
		MagentaUserEvents::GROUP_CREATE_COMPLETED => 'group.flash.created',
		MagentaUserEvents::GROUP_DELETE_COMPLETED => 'group.flash.deleted',
		MagentaUserEvents::GROUP_EDIT_COMPLETED => 'group.flash.updated',
		MagentaUserEvents::PROFILE_EDIT_COMPLETED => 'profile.flash.updated',
		MagentaUserEvents::REGISTRATION_COMPLETED => 'registration.flash.user_created',
		MagentaUserEvents::RESETTING_RESET_COMPLETED => 'resetting.flash.success',
	);
	
	/**
	 * @var Session
	 */
	private $session;
	
	/**
	 * @var TranslatorInterface
	 */
	private $translator;
	
	/**
	 * FlashListener constructor.
	 *
	 * @param Session             $session
	 * @param TranslatorInterface $translator
	 */
	public function __construct(Session $session, TranslatorInterface $translator)
	{
		$this->session = $session;
		$this->translator = $translator;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents()
	{
		return array(
			MagentaUserEvents::CHANGE_PASSWORD_COMPLETED => 'addSuccessFlash',
			MagentaUserEvents::GROUP_CREATE_COMPLETED => 'addSuccessFlash',
			MagentaUserEvents::GROUP_DELETE_COMPLETED => 'addSuccessFlash',
			MagentaUserEvents::GROUP_EDIT_COMPLETED => 'addSuccessFlash',
			MagentaUserEvents::PROFILE_EDIT_COMPLETED => 'addSuccessFlash',
			MagentaUserEvents::REGISTRATION_COMPLETED => 'addSuccessFlash',
			MagentaUserEvents::RESETTING_RESET_COMPLETED => 'addSuccessFlash',
		);
	}
	
	/**
	 * @param Event  $event
	 * @param string $eventName
	 */
	public function addSuccessFlash(Event $event, $eventName)
	{
		if (!isset(self::$successMessages[$eventName])) {
			throw new \InvalidArgumentException('This event does not correspond to a known flash message');
		}
		
		$this->session->getFlashBag()->add('success', $this->trans(self::$successMessages[$eventName]));
	}
	
	/**
	 * @param string$message
	 * @param array $params
	 *
	 * @return string
	 */
	private function trans($message, array $params = array())
	{
		return $this->translator->trans($message, $params, 'MagentaCBookModelBundle');
	}
}