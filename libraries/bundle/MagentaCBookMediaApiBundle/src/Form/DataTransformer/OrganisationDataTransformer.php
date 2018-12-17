<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Magenta\Bundle\CBookMediaApiBundle\Form\DataTransformer;

use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Sonata\MediaBundle\Provider\Pool;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\DataTransformerInterface;

class OrganisationDataTransformer implements DataTransformerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    /** @var RegistryInterface */
    private $registry;

    /**
     * @param Pool   $pool
     * @param string $class
     * @param array  $options
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
        $this->logger = new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function transform($modelValue)
    {
        if (null === $modelValue) {
            return;
        }
        if ($modelValue instanceof Organisation) {
            file_put_contents('php://stdout', ('ENTITY - ID Transforming org: '.get_class($modelValue).' --- '.$modelValue->getName()));
        }

        return $modelValue->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($formValue)
    {
        if ($formValue instanceof Organisation) {
            return $formValue;
        }

        $org = $this->registry->getRepository(Organisation::class)->find($formValue);

        return $org;
    }

    /**
     * Define the default options for the DataTransformer.
     *
     * @param array $options
     *
     * @return array
     */
    protected function getOptions(array $options)
    {
        return array_merge([
            'provider' => false,
            'context' => false,
            'empty_on_new' => true,
            'new_on_update' => true,
        ], $options);
    }
}
