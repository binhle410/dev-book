<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Book;

use Bean\Component\Book\IoC\ChapterContainerInterface;
use Bean\Component\Book\Model\Book as BookModel;
use Bean\Component\Book\Model\ChapterInterface;
use Bean\Component\Organization\IoC\OrganizationAwareInterface;
use Bean\Component\Organization\Model\OrganizationInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\BookCategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\CategoryItemContainerInterface;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualGroup;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;

/**
 * @ORM\Entity()
 * @ORM\Table(name="book__book")
 */
class Book extends \Bean\Component\Book\Model\Book implements OrganizationAwareInterface, ChapterContainerInterface, CategoryItemContainerInterface
{

    function __construct()
    {
        parent::__construct();
        $this->locale = 'en';
        $this->status = self::STATUS_DRAFT;
        $this->bookCategoryItems = new ArrayCollection();
        $this->chapters = new ArrayCollection();
    }

    public function isAccessibleToIndividual(IndividualMember $member)
    {
        $accessible = false;
        $groups = $member->getGroups();
        $catItems = $this->getBookCategoryItems();

        /** @var BookCategoryItem $catItem */
        foreach ($catItems as $catItem) {
            if ($groups->count() === 0) {
                $accessible = $accessible || $catItem->getCategory()->isPublic();
            }
            /** @var IndividualGroup $group */
            foreach ($groups as $group) {
                if ($catItem->isAccessibleToGroup($group)) {
                    return true;
                }
            }
        }

        return $accessible;
    }

    public function __clone()
    {
        parent::__clone();
        $rootChapters = $this->getRootChapters();
        $this->setChapters(new ArrayCollection());

        /** @var Chapter $rootChapter */
        foreach ($rootChapters as $rootChapter) {
            $clonedRootChapter = clone $rootChapter;
            $this->addChapter($clonedRootChapter);
        }
    }

    protected function getObjectArrayProperties()
    {
        return array_merge(parent::getObjectArrayProperties(), []);
    }

    protected function getObjectProperties()
    {
        return array_merge(parent::getObjectProperties(), ['bookCategoryItems']);
    }

    public function addCategoryItem(CategoryItem $item)
    {
        $this->addBookCategoryItem($item);
    }

    public function removeCategoryItem(CategoryItem $item)
    {
        $this->removeBookCategoryItem($item);
    }

    public function publish()
    {
        /** @var Book $clone */
        $clone = parent::publish();
        $items = $this->getBookCategoryItems();
        /** @var BookCategoryItem $item */
        foreach ($items as $item) {
            $this->removeBookCategoryItem($item);
            $clone->addBookCategoryItem($item);
        }
        return $clone;
    }

    public function getArrayData($obj)
    {
        if ($obj instanceof Collection) {
            $siblings = $obj->getValues();
        } else {
            $siblings = $obj;
        }
        return $siblings;
    }

    public function rearrangeRootChapters()
    {
        $rootChapters = $this->getRootChapters();
        $rootChapterArray = $this->getArrayData($rootChapters);
        $this->rearrangePositions($rootChapterArray);
        return $rootChapters;
    }

    public function addChapter(ChapterInterface $chapter)
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters->add($chapter);
            $chapter->setBook($this);
            if (empty($chapter->getParentChapter())) {
                $chapter->setPosition(0);
            }
            if ($this->chapters->count() > 0) {
                $chapter->setPosition($this->getLastChapterPosition() + 1);
            }
        }

        foreach ($chapter->getSubChapters() as $subChapter) {
            $this->addChapter($subChapter);
        }
    }

    public function removeChapter(ChapterInterface $chapter)
    {
        $this->chapters->removeElement($chapter);
        $chapter->setBook(null);
    }

    public function getLastChapterPosition()
    {
        $position = 0;
        $rootChapters = $this->getRootChapters();
        /** @var ChapterInterface $chapter */
        foreach ($rootChapters as $chapter) {
            if ($chapter->getPosition() > $position) {
                $position = $chapter->getPosition();
            }
        }

        return $position;
    }

    public function getRootChapters()
    {
        $c = Criteria::create();
        $expr = $c->expr();
        $c->andWhere($expr->isNull('parentChapter'))
            ->orderBy(['position' => Criteria::ASC]);

        return $this->chapters->matching($c);
    }

    /**
     * @ORM\OneToMany(
     *     targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\BookCategoryItem",
     *     mappedBy="item", cascade={"persist"}, orphanRemoval=true
     * )
     * @ORM\OrderBy({"position"="ASC"})
     *
     * @var \Doctrine\Common\Collections\Collection $bookCategoryItems ;
     */
    protected $bookCategoryItems;

    public function addBookCategoryItem(BookCategoryItem $bc)
    {
        $this->bookCategoryItems->add($bc);
        $bc->setItem($this);
    }

    public function removeBookCategoryItem(BookCategoryItem $bc)
    {
        $this->bookCategoryItems->removeElement($bc);
        $bc->setItem(null);
    }

    /**
     * @ORM\OneToMany(targetEntity="Chapter", cascade={"persist","merge"}, orphanRemoval=true, mappedBy="book")
     * @ORM\OrderBy({"position"="ASC"})
     */
    protected $chapters;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Bean\Component\CreativeWork\Model\CreativeWork", cascade={"persist","merge"}, orphanRemoval=true, mappedBy="partOf")
     */
    protected $parts;

    /**
     * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="books")
     * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $organisation;

//	/**
//	 * @var boolean|null
//	 * @ORM\Column(type="boolean", nullable=true)
//	 */
//	protected $abridged;
//
//	/**
//	 * @var integer|null
//	 * @ORM\Column(type="integer", nullable=true)
//	 */
//	protected $numberOfPages;
//
//	/**
//	 * @var string|null
//	 * @ORM\Column(type="string", nullable=true)
//	 */
//	protected $bookEdition;
//
//	/**
//	 * @var string|null
//	 * @ORM\Column(type="string", nullable=true)
//	 */
//	protected $bookFormat;
//
//	/**
//	 * @var string|null
//	 * @ORM\Column(type="string", nullable=true)
//	 */
//	protected $isbn;

    /**
     * @return mixed
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @param mixed $organisation
     */
    public function setOrganisation($organisation): void
    {
        $this->organisation = $organisation;
    }

    /**
     * @return Collection
     */
    public function getBookCategoryItems(): Collection
    {
        return $this->bookCategoryItems;
    }

    /**
     * @param Collection $bookCategoryItems
     */
    public function setBookCategoryItems(Collection $bookCategoryItems): void
    {
        $this->bookCategoryItems = $bookCategoryItems;
    }

    public function getOrganization(): ?OrganizationInterface
    {
        return $this->organisation;
    }

    public function setOrganization(?OrganizationInterface $org)
    {
        return $this->organisation = $org;
    }

    /**
     * @return mixed
     */
    public function getChapters()
    {
        return $this->chapters;
    }

    /**
     * @param mixed $chapters
     */
    public function setChapters($chapters): void
    {
        $this->chapters = $chapters;
    }
}
