<?php

namespace Magenta\Bundle\CBookModelBundle\Command\Classification;

use Doctrine\ORM\EntityManager;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Service\Classification\CategoryManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Matthieu Bontemps <matthieu@knplabs.com>
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Luis Cordova <cordoval@gmail.com>
 */
class FixOrganisationLogoCategoryCommand extends Command
{
    protected static $defaultName = 'magenta:classification:fix-organisation-logo-category';

    private $manager;
    private $registry;
    private $categoryManager;
    private $rootCategoryByOrgIds = null;

    public function __construct(EntityManager $manager, RegistryInterface $registry, CategoryManager $categoryManager)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->registry = $registry;
        $this->categoryManager = $categoryManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Fix Organisation Logo Category.')
            ->setDefinition([])
            ->setHelp(<<<'EOT'
The <info>magenta:classification:fix-organisation-logo-category</info> 
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Fixing Org Logo Categories so that it associates to the categories of the organisation');
        $mRepo = $this->registry->getRepository(Media::class);
        $catRepo = $this->registry->getRepository(Category::class);

        $defaultLogoCat = $catRepo->findOneBy(['context' => 'organisation_logo', 'organisation' => null]);

        $media = $mRepo->findBy(['context' => 'organisation_logo', 'category' => $defaultLogoCat->getId()]);
        /** @var Media $m */
        foreach ($media as $m) {
            $org = $m->getOrganization();
            $cid = $m->getContext();
            if (empty($this->rootCategoryByOrgIds)) {
                $this->categoryManager->initiateRootCategories($cid);
                $logoCategories = $catRepo->findBy(['parent' => null, 'context' => 'organisation_logo']);
                $rootCategoryByOrgIds = [];
                /** @var Category $category */
                foreach ($logoCategories as $category) {
                    /** @var Organisation $org */
                    if (!empty($org = $category->getOrganization())) {
                        $rootCategoryByOrgIds[$org->getId()] = $category;
                    }
                }
                $this->rootCategoryByOrgIds = $rootCategoryByOrgIds;
            }

            if (array_key_exists($org->getId(), $this->rootCategoryByOrgIds)) {
                $category = $this->rootCategoryByOrgIds[$org->getId()];
                $m->setCategory($category);
                $output->writeln('Persisting '.$m->getName().' with new Category '.$category->getName());
                $this->manager->persist($m);
            }
        }
        $output->writeln('Flusing');
        $this->manager->flush();
        $output->writeln('DONE');
    }
}
