<?php

namespace Magenta\Bundle\CBookAdminBundle\Controller;

use \Sonata\MediaBundle\Controller\MediaController as SonataMediaController;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Gaufrette\Filesystem;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Model\Metadata;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\MediaBundle\CDN\CDNInterface;
use Sonata\MediaBundle\Extra\ApiMediaFile;
use Sonata\MediaBundle\Filesystem\Local;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;
use Sonata\MediaBundle\Thumbnail\ThumbnailInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class MediaController extends SonataMediaController
{

    /**
     * @param string $id
     * @param string $format
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     *
     */
    public function viewBinaryAction(Request $request, $id, $format = MediaProviderInterface::FORMAT_REFERENCE)
    {
        $media = $this->getMedia($id);

        if (!$media) {
            throw new NotFoundHttpException(sprintf('unable to find the media with the id : %s', $id));
        }

        if (!$this->get('sonata.media.pool')->getDownloadSecurity($media)->isGranted($media, $request)) {
            throw new AccessDeniedException();
        }

        $response = $this->getViewBinaryResponse($media, $format, $this->get('sonata.media.pool')->getDownloadMode($media));

        if ($response instanceof BinaryFileResponse) {
            $response->prepare($request);
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getViewBinaryResponse(MediaInterface $media, $format, $mode, array $headers = [])
    {
        $provider = $this->getProvider($media);

        // build the default headers
        $headers = array_merge([
            'Content-Type' => $media->getContentType(),
//			'Content-Disposition' => sprintf('attachment; filename="%s"', $media->getMetadataValue('filename')),
            'Content-Disposition' => sprintf('inline; filename="%s"', $media->getMetadataValue('filename')),
        ], $headers);

        if (!in_array($mode, ['http', 'X-Sendfile', 'X-Accel-Redirect'])) {
            throw new \RuntimeException('Invalid mode provided');
        }

        if ('http' == $mode) {
            if (MediaProviderInterface::FORMAT_REFERENCE === $format) {
                $file = $provider->getReferenceFile($media);
            } else {
                $file = $provider->getFilesystem()->get($provider->generatePrivateUrl($media, $format));
            }

            return new StreamedResponse(function () use ($file) {
                echo $file->getContent();
            }, 200, $headers);
        }

        if (!$provider->getFilesystem()->getAdapter() instanceof Local) {
            throw new \RuntimeException('Cannot use X-Sendfile or X-Accel-Redirect with non \Sonata\MediaBundle\Filesystem\Local');
        }

        $filename = sprintf('%s/%s',
            $provider->getFilesystem()->getAdapter()->getDirectory(),
            $provider->generatePrivateUrl($media, $format)
        );

        return new BinaryFileResponse($filename, 200, $headers);
    }

    public function downloadAction($id, $format = MediaProviderInterface::FORMAT_REFERENCE)
    {
        return parent::downloadAction($id, $format);
    }
}