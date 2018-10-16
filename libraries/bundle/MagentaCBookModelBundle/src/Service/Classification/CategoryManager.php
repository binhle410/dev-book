<?php

namespace Magenta\Bundle\CBookModelBundle\Service\Classification;

use Doctrine\Common\Persistence\ManagerRegistry;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Context;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Sonata\ClassificationBundle\Model\CategoryInterface;
use Sonata\ClassificationBundle\Model\ContextInterface;
use Sonata\ClassificationBundle\Model\ContextManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CategoryManager extends \Sonata\ClassificationBundle\Entity\CategoryManager implements \Sonata\MediaBundle\Model\CategoryManagerInterface
{
    /** @var ContainerInterface */
    private $container;

    /** @var Organisation $organisation */
    private $organisation;

    public function __construct(string $class, ManagerRegistry $registry, ContextManagerInterface $contextManager, ContainerInterface $container)
    {
        parent::__construct($class, $registry, $contextManager);
        $this->container = $container;
    }

    public function initiateRootCategories($cid)
    {
        $manager = $this->container->get('doctrine.orm.default_entity_manager');
        $contextManager = $this->container->get('sonata.classification.manager.context');
        $catManager = $this->container->get('sonata.classification.manager.category');

        $cname = ucfirst(str_replace('_', ' ', $cid));
        if (empty($context = $contextManager->find($cid))) {
            /** @var Context $context */
            $context = $contextManager->create();
            $context->setId($cid);
            $context->setName($cname);
            $context->setEnabled(true);
            $context->setCreatedAt(new \DateTime());
            $contextManager->save($context);
        }

        $defaultRoot = $catManager->findOneBy(['parent' => null, 'organisation' => null, 'context' => $cid]);
        if (empty($defaultRoot)) {
            /** @var Category $category */
            $category = $catManager->create();
            $category->setContext($context);
            $category->setName($cname);
            $category->setEnabled(true);
            $manager->persist($category);
        }

        $rootCategories = $catManager->findBy(['parent' => null, 'context' => $cid]);

        $orgsHavingRootCategory = [];
        /** @var Category $rootCategory */
        foreach ($rootCategories as $rootCategory) {
            if (!empty($org = $rootCategory->getOrganization())) {
                $orgsHavingRootCategory[] = $org->getId();
            }
        }

        $orgs = $this->container->get('doctrine')->getRepository(Organisation::class)->findAll();

        /** @var Organisation $org */
        foreach ($orgs as $org) {
            if (!in_array($org->getId(), $orgsHavingRootCategory)) {
                /** @var Category $category */
                $category = $catManager->create();
                $category->setContext($context);
                $category->setName($cname);
                $category->setEnabled(true);
                $category->setOrganization($org);
                $manager->persist($category);
                $orgsHavingRootCategory[] = $org->getId();
            }
        }

        $manager->flush();
    }

    /**
     * Load all categories from the database, the current method is very efficient for < 256 categories.
     * have to sync with its parent method
     *
     * @param bool $loadChildren
     *
     * @return array|\Sonata\ClassificationBundle\Model\CategoryInterface[]
     */
    public function getAllRootCategories($loadChildren = true, Organisation $org = null)
    {
        if (empty($org)) {
            if (empty($orgId = $this->container->get('request_stack')->getCurrentRequest()->get('organisation'))) {
                if (empty($this->organisation)) {
                    throw new UnauthorizedHttpException('No Org Info');
                } else {
                    $org = $this->organisation;
                    $orgId = $org->getId();
                }
            }
        } else {
            $orgId = $org->getId();
        }

        $class = $this->getClass();

        $rootCategories = $this->getObjectManager()->createQuery(sprintf('SELECT c FROM %s c WHERE c.parent IS NULL AND c.organisation = :org', $class))
            ->setParameter('org', $orgId)
            ->execute();

        $categories = [];

        foreach ($rootCategories as $category) {
            if (null === $category->getContext()) {
                throw new \RuntimeException('Context cannot be null');
            }

            $categories[] = $loadChildren ? $this->getRootCategoryWithChildren($category, $org) : $category;
        }

        return $categories;
    }

    /**
     * Copied from parent
     *
     * @param CategoryInterface $category
     * @param Organisation|null $org
     *
     * @return CategoryInterface
     */
    public function getRootCategoryWithChildren(CategoryInterface $category, Organisation $org = null)
    {
        if (null === $category->getContext()) {
            throw new \RuntimeException('Context cannot be null');
        }
        if (null != $category->getParent()) {
            throw new \RuntimeException('Method can be called only for root categories');
        }
        $context = $this->getContext($category->getContext());

        $this->loadCategories($context, $org);

        foreach ($this->categories[$context->getId()] as $contextRootCategory) {
            if ($category->getId() == $contextRootCategory->getId()) {
                return $contextRootCategory;
            }
        }

        throw new \RuntimeException('Category does not exist');
    }

    /**
     * copied from parent
     *
     * @param bool $loadChildren
     * @param Organisation|null $organisation
     *
     * @return array
     */
    public function getRootCategoriesSplitByContexts($loadChildren = true, Organisation $organisation = null)
    {
        $rootCategories = $this->getAllRootCategories($loadChildren, $organisation);

        $splitCategories = [];

        foreach ($rootCategories as $category) {
            $splitCategories[$category->getContext()->getId()][] = $category;
        }

        return $splitCategories;
    }

    public function getRootCategoriesForContext(ContextInterface $context = null, Organisation $organisation = null)
    {
        $context = $this->getContext($context);

        $this->loadCategories($context, $organisation);

        return $this->categories[$context->getId()];
    }

    /**
     * Load all categories from the database, the current method is very efficient for < 256 categories.
     * have to sync with its parent method
     *
     * @param ContextInterface $context
     */
    protected function loadCategories(ContextInterface $context, Organisation $org = null)
    {
        if (array_key_exists($context->getId(), $this->categories)) {
            return;
        }

        if (empty($org)) {
            if (empty($orgId = $this->container->get('request_stack')->getCurrentRequest()->get('organisation'))) {
                if (empty($this->organisation)) {
                    throw new UnauthorizedHttpException('No Org Info');
                } else {
                    $org = $this->organisation;
                    $orgId = $org->getId();
                }
            }
        } else {
            $orgId = $org->getId();
        }

        $class = $this->getClass();

        $categories = $this->getObjectManager()->createQuery(sprintf('SELECT c FROM %s c WHERE c.context = :context AND c.organisation = :org ORDER BY c.parent ASC', $class))
            ->setParameter('context', $context->getId())
            ->setParameter('org', $orgId)
            ->execute();

        if (0 == count($categories)) {
            // no category, create one for the provided context

            if (empty($organisation = $this->container->get('doctrine')->getRepository(Organisation::class)->find($orgId))) {
                throw new UnauthorizedHttpException('Org not found');
            }
            $category = $this->create();
            $category->setName('Root');
            $category->setEnabled(true);
            $category->setContext($context);
            $category->setDescription($context->getName());
            $category->setOrganisation($organisation);

            $this->save($category);

            $categories = [$category];
        }

        $rootCategories = [];
        foreach ($categories as $pos => $category) {
            if (null === $category->getParent()) {
                $rootCategories[] = $category;
            }

            $this->categories[$context->getId()][$category->getId()] = $category;

            $parent = $category->getParent();

            $category->disableChildrenLazyLoading();

            if ($parent) {
                $parent->addChild($category);
            }
        }

        $this->categories[$context->getId()] = $rootCategories;
    }

    /**
     * copied from parent
     *
     * @param $contextCode
     *
     * @return ContextInterface
     */
    private function getContext($contextCode)
    {
        if (empty($contextCode)) {
            $contextCode = ContextInterface::DEFAULT_CONTEXT;
        }

        if ($contextCode instanceof ContextInterface) {
            return $contextCode;
        }

        $context = $this->contextManager->find($contextCode);

        if (!$context instanceof ContextInterface) {
            $context = $this->contextManager->create();

            $context->setId($contextCode);
            $context->setName($contextCode);
            $context->setEnabled(true);

            $this->contextManager->save($context);
        }

        return $context;
    }

    /**
     * @return Organisation|null
     */
    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    /**
     * @param Organisation|null $organisation
     */
    public function setOrganisation(?Organisation $organisation): void
    {
        $this->organisation = $organisation;
    }
}
