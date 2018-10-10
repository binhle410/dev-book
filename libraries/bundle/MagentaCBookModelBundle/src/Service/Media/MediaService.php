<?php

namespace Magenta\Bundle\CBookModelBundle\Service\Media;

use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Sonata\MediaBundle\Entity\MediaManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MediaService extends MediaManager {
	/** @var ContainerInterface $container */
	private $container;
	
	public function generatePrivateUrl($mid, $format = 'admin') {
		$mediaPrefix = $this->container->getParameter('MEDIA_API_PREFIX');
		if($mediaPrefix === '/') {
			$mediaPrefix = '';
		}
		
		/** @var Media $medium */
		$medium = $this->container->get('doctrine')->getRepository(Media::class)->find($mid);
		if(empty($medium)) {
			return '';
		}
		
		$format = $medium->getContext() . '_' . $format;
		
		return $url = $this->container->getParameter('MEDIA_API_BASE_URL') . $mediaPrefix . sprintf('/media/%d/binaries/%s/view', $mid, $format);
	}
	
	/**
	 * @param ContainerInterface $container
	 */
	public function setContainer(ContainerInterface $container): void {
		$this->container = $container;
	}
}
