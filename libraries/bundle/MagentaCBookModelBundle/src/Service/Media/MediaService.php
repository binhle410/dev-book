<?php

namespace Magenta\Bundle\CBookModelBundle\Service\Media;

use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Sonata\MediaBundle\Entity\MediaManager;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MediaService extends MediaManager
{
    private $defaultFormat = 'unknown';

    /** @var ContainerInterface $container */
    private $container;

    /**
     * @param MediaInterface $media
     *
     * @return string the file extension for the $media, or the $defaultExtension if not available
     */
    protected function getExtension(MediaInterface $media)
    {
        $ext = $media->getExtension();
        if (!\is_string($ext) || \strlen($ext) < 3) {
            $ext = $this->defaultFormat;
        }

        return $ext;
    }

    public function generatePublicUrl($mid, $format = 'admin')
    {
        /** @var Media $medium */
        $medium = $this->container->get('doctrine')->getRepository(Media::class)->find($mid);
        if (empty($medium)) {
            return '';
        }

        $provider = $this->container->get('sonata.media.pool')->getProvider($medium->getProviderName());

        if (MediaProviderInterface::FORMAT_REFERENCE === $format) {
            $path = $provider->getReferenceImage($medium);
        } else {
            if ($format !== 'admin') {
                $format = $medium->getContext() . '_' . $format;
            }
            $path = sprintf('%s/thumb_%s_%s.%s', $provider->generatePath($medium), $medium->getId(), $format, $this->getExtension($medium));
        }

        $path = $this->container->getParameter('s3_directory') . '/' . $path;

//        return $path;

        $credentials = new Credentials($this->container->getParameter('s3_access_key'), $this->container->getParameter('s3_secret_key'));

        //Creating a presigned request
        $s3Client = new S3Client([
//            'profile' => 'default',
            'region' => $this->container->getParameter('s3_region'),
            'version' => $this->container->getParameter('s3_version'),
            'credentials' => $credentials
        ]);

        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $this->container->getParameter('s3_bucket_name'),
            'Key' => $path
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+120 minutes');
        $url = (string)$request->getUri();
        return $url;
    }


    public function generatePrivateUrl($mid, $format = 'admin')
    {
        /** @var Media $medium */
        $medium = $this->container->get('doctrine')->getRepository(Media::class)->find($mid);
        if (empty($medium)) {
            return '';
        }

        $mediaPrefix = $this->container->getParameter('MEDIA_API_PREFIX');
        if ($mediaPrefix === '/') {
            $mediaPrefix = '';
        }


        $format = $medium->getContext() . '_' . $format;

        return $url = $this->container->getParameter('MEDIA_API_BASE_URL') . $mediaPrefix . sprintf('/media/%d/binaries/%s/view', $mid, $format);
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }
}
