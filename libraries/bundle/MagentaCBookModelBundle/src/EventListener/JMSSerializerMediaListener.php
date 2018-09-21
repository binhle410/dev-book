<?php

namespace Magenta\Bundle\CBookModelBundle\EventListener;

use JMS\Serializer\EventDispatcher\Event;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Service\Media\MediaService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class JMSSerializerMediaListener {
	/** @var ContainerInterface $container */
	private $container;
	
	public function __construct(ContainerInterface $c) {
		$this->container = $c;
	}
	
	public function onPreSerialize(Event $event) {
		/** @var Media $medium */
		$medium = $event->getObject();
		if($medium->getBaseUrl() === '/') {
			/**
			 * @var MediaService $ms
			 */
			$ms = $this->container->get('sonata.media.manager.media');
			
			if($medium->getProviderName() === 'sonata.media.provider.image') {
				$medium->setLink($ms->generatePrivateUrl($medium->getId(), 'big'));
			} else {
				$medium->setLink($ms->generatePrivateUrl($medium->getId(), 'reference'));
			}
			
		}
	}
}
