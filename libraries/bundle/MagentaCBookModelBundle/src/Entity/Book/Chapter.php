<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Book;

use Bean\Component\Book\Model\Chapter as ChapterModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="book__chapter")
 */
class Chapter extends ChapterModel
{

    function __construct()
    {
        parent::__construct();
        $this->locale = 'en';
        $this->subChapters = new ArrayCollection();
    }

    public function rearrangeSubChapters()
    {
        $subChapters = $this->getSubChapters();
        $subChapterArray = $this->getArrayData($subChapters);
        $this->rearrangePositions($subChapterArray);
        return $subChapters;
    }

    public function getNextChapter()
    {
        $chapters = $this->getArrayData($this->getSiblingChapters());
        $this->rearrangePositions($chapters);
        $found = false;
        /** @var Chapter $chapter */
        foreach ($chapters as $chapter) {
            if ($found) {
                return $chapter;
            }
            if ($chapter === $this) {
                $found = true;
            }
        }
        return null;
    }

    public function getPreviousChapter()
    {
        $chapters = $this->getArrayData($this->getSiblingChapters());
        $this->rearrangePositions($chapters);
        $previousChapter = null;

        /** @var Chapter $chapter */
        foreach ($chapters as $chapter) {
            if ($chapter === $this) {
                return $previousChapter;
            }
            $previousChapter = $chapter;
        }

        return $previousChapter;
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

    public function getListNumber($siblings = [])
    {
        if ($this->parentChapter !== null) {
            $subs = $this->parentChapter->getSubChapters();
            $siblings = $this->getArrayData($subs);

        }

        return parent::getListNumber($siblings);
    }

    /**
     * @var Book $book
     * @ORM\ManyToOne(targetEntity="Book", inversedBy="chapters")
     * @ORM\JoinColumn(name="id_book", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $book;

    /**
     * @ORM\OneToMany(targetEntity="Chapter", cascade={"persist","merge"}, mappedBy="parentChapter")
     * @ORM\OrderBy({"position"="ASC"})
     */
    protected $subChapters;

    protected function removeElementFromArrayProperty($element, $prop)
    {
        if ($prop === 'subChapters') {
            $this->subChapters->removeElement($element);
            $element->setParentChapter(null);

            return true;
        }

        return parent::removeElementFromArrayProperty($element, $prop);
    }

    /**
     * @var Chapter $parentChapter
     * @ORM\ManyToOne(targetEntity="Chapter", inversedBy="subChapters")
     * @ORM\JoinColumn(name="id_parent_chapter", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parentChapter;
}
