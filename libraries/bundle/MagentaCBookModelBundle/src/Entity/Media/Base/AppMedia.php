<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Media\Base;

use Bean\Bundle\SonataMediaBundle\Doctrine\Orm\BaseMedia;
use Bean\Component\CreativeWork\Model\CreativeWork;
use Bean\Component\Organization\Model\OrganizationInterface;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\CategoryItemContainerInterface;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\MediaCategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sonata\ClassificationBundle\Model\CategoryInterface;
use Sonata\MediaBundle\Model\MediaInterface;

/** @ORM\MappedSuperclass */
abstract class AppMedia extends BaseMedia implements MediaInterface, CategoryItemContainerInterface
{
    private $baseUrl = '/';
    private $contentUrlPrefix;

    /**
     * @var int|null
     * @ORM\Id
     * @ORM\Column(type="integer",options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    function __construct()
    {
        parent::__construct();
        $this->enabled = true;
    }

    public function getOrganization(): ?OrganizationInterface
    {
        return $this->organisation;
    }

    public function setOrganization(?OrganizationInterface $org)
    {
        return $this->organisation = $org;
    }

    public function addCategoryItem(CategoryItem $item)
    {
        $this->addMediaCategoryItem($item);
    }

    public function removeCategoryItem(CategoryItem $item)
    {
        $this->removeMediaCategoryItem($item);
    }

    /**
     * @ORM\OneToMany(
     *     targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\MediaCategoryItem",
     *     mappedBy="item", cascade={"persist"}, orphanRemoval=true
     * )
     * @ORM\OrderBy({"position"="ASC"})
     *
     * @var \Doctrine\Common\Collections\Collection $bookCategoryItems ;
     */
    protected $mediaCategoryItems;

    public function addMediaCategoryItem(MediaCategoryItem $bc)
    {
        $this->mediaCategoryItems->add($bc);
        $bc->setItem($this);
    }

    public function removeMediaCategoryItem(MediaCategoryItem $bc)
    {
        $this->mediaCategoryItems->removeElement($bc);
        $bc->setItem(null);
    }

    /**
     * @param CategoryInterface $category
     */
    public function setCategory(CategoryInterface $category): void
    {
        $this->category = $category;
    }

    /**
     * @var Organisation
     * @ORM\OneToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="logo")
     * @ORM\JoinColumn(name="id_logo_organisation", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $logoOrganisation;

    /**
     * @var Organisation|null
     * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="mediaAssets")
     * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $organisation;

    /**
     * @var CreativeWork|null
     * @ORM\ManyToOne(targetEntity="Bean\Component\CreativeWork\Model\CreativeWork")
     * @ORM\JoinColumn(name="id_creative_work", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $creativeWork;

    protected $name;
    protected $context;
    protected $description;
    protected $contentUrl;
    protected $link;

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return mixed
     */
    public function getContentUrlPrefix()
    {
        return $this->contentUrlPrefix;
    }

    /**
     * @param mixed $contentUrlPrefix
     */
    public function setContentUrlPrefix($contentUrlPrefix): void
    {
        $this->contentUrlPrefix = $contentUrlPrefix;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }

    public function getLink()
    {
        if (empty($this->link)) {
            if (strlen($this->contentUrlPrefix) === 1) {
                $prefix = '';
            } else {
                $prefix = $this->contentUrlPrefix;
            }

            $this->link = $this->getBaseUrl() . $prefix . $this->contentUrl;
        }

        return $this->link;
    }

    /**
     * @return Organisation
     */
    public function getLogoOrganisation()
    {
        return $this->logoOrganisation;
    }

    /**
     * @param Organisation $logoOrganisation
     */
    public function setLogoOrganisation($logoOrganisation)
    {
        $this->logoOrganisation = $logoOrganisation;
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

    /**
     * @return CreativeWork|null
     */
    public function getCreativeWork(): ?CreativeWork
    {
        return $this->creativeWork;
    }

    /**
     * @param CreativeWork|null $creativeWork
     */
    public function setCreativeWork(?CreativeWork $creativeWork): void
    {
        $this->creativeWork = $creativeWork;
    }
}
