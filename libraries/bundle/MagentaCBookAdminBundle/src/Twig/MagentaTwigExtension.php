<?php

namespace Magenta\Bundle\CBookAdminBundle\Twig;

use Magenta\Bundle\CBookModelBundle\Entity\Book\Chapter;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MagentaTwigExtension extends AbstractExtension
{

    const TYPE_PDF_DOWNLOAD_SERVICE_SHEET = 'PDF_DOWNLOAD_SERVICE_SHEET';

    /** @var ContainerInterface $container */
    private $container;

    public function __construct(ContainerInterface $c)
    {
        $this->container = $c;
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('privateMediumUrl', array($this, 'privateMediumUrl')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('currentOrganisation', array($this, 'getCurrentOrganisation')),
            new \Twig_SimpleFunction('organisationBySubdomain', array($this, 'organisationBySubdomain')),
            new \Twig_SimpleFunction('privateMediumUrl', array($this, 'privateMediumUrl')),
            new \Twig_SimpleFunction('publicMediumUrl', array($this, 'publicMediumUrl')),
            new \Twig_SimpleFunction('paginationItemGroup', array($this, 'paginationItemGroup')),
            new \Twig_SimpleFunction('paginationItemClass', array($this, 'paginationItemClass')),
        );
    }

    public function paginationItemGroup(Chapter $chapter, Chapter $current)
    {
        $position = $chapter->getPosition();
        $chaptersPerGroup = 3;
        $quotient = (int)($position / $chaptersPerGroup);
        $remainder = $position % $chaptersPerGroup;
        if ($remainder === 0) {
            return $quotient;
        } elseif ($remainder === 1) {
            return $quotient + 1;
        } elseif ($remainder === 2) {
            return $quotient + 1;
        }
        return $quotient;
    }

    public function paginationItemClass(Chapter $chapter, Chapter $current)
    {
        $class = '';
        if ($chapter === $current) {
            $class .= ' active';
        }
        $pos = $chapter->getPosition();
        $cpos = $current->getPosition();

        $siblings = $current->getSiblingChapters();
        if (count($siblings) > 3) {
            if ($cpos === 1) {
                if ($pos > 3) {
                    $class .= 'hide-chapter';
                }
            } elseif (($pos < $cpos - 1 || $pos > $cpos + 1)) {
                $class .= 'hide-chapter';
            }
        }
        return $class;
    }

    public function getCurrentOrganisation()
    {
        $repo = $this->container->get('doctrine')->getRepository(Organisation::class);
        $user = $this->container->get(UserService::class)->getUser();
        if (empty($org = $user->getAdminOrganisation())) {
            if (!empty($person = $user->getPerson())) {
                /** @var OrganisationMember $m */
                $m = $person->getMembers()->first();
                if (!empty($m)) {
                    return $m->getOrganization();
                }
            }
        }

        return $org;
    }

    public function downloadPdfUrl($type)
    {
//		if($type === )

    }
    public function publicMediumUrl($mediumId, $format = 'admin')
    {
        $c = $this->container;

        return $c->get('sonata.media.manager.media')->generatePublicUrl($mediumId, $format);
    }

    public function privateMediumUrl($mediumId, $format = 'admin')
    {
        $c = $this->container;

        return $c->get('sonata.media.manager.media')->generatePrivateUrl($mediumId, $format);
    }
}
