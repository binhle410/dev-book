<?php
declare(strict_types=1);

namespace Bean\Component\Book\Model;

use Bean\Component\Book\IoC\ChapterContainerInterface;
use Bean\Component\CreativeWork\Model\CreativeWork;
use Bean\Component\CreativeWork\Model\CreativeWorkInterface;

class Chapter extends CreativeWork implements ChapterInterface
{
    private $siblingChapters;

    protected function getObjectProperties()
    {
        return array_merge(parent::getObjectProperties(), ['subChapters']);
    }

    protected function getObjectArrayProperties()
    {
        return array_merge(parent::getObjectArrayProperties(), ['subChapters' => 'setParentChapter']);
    }

    public function getSiblingChapters()
    {
        if (!empty($this->siblingChapters)) {
            return $this->siblingChapters;
        }
        if (empty($parent = $this->parentChapter)) {
            return $this->siblingChapters = $this->book->getRootChapters();
        } else {
            return $this->siblingChapters = $parent->getSubChapters();
        }
    }

    public function getListNumber($siblings = [])
    {
        if (empty($this->parentChapter)) {
            return $this->getPosition();
        }

        $this->rearrangePositions($siblings);

        return $this->parentChapter->getListNumber() . '.' . $this->position;
    }

    public function setPartOf(CreativeWorkInterface $partOf): void
    {
        parent::setPartOf($partOf);
        if ($partOf instanceof ChapterContainerInterface) {
            $partOf->addChapter($this);
        } elseif ($partOf instanceof ChapterInterface) {
            $partOf->addSubChapter($this);
        }
    }

    /**
     * @param ChapterInterface|null $parentChapter
     */
    public function setParentChapter(?ChapterInterface $parentChapter): void
    {
        if (!empty($parentChapter)) {
            $this->book = $parentChapter->getBook();
            if (method_exists($this->book, 'addChapter')) {
                $this->book->addChapter($this);
            }
        }
        $this->parentChapter = $parentChapter;
    }

    /**
     * NOT part of schema.org.
     * A Chapter can have sub-chapters
     * @var \Countable|\IteratorAggregate|\ArrayAccess|array|null
     */
    protected $subChapters;

    public function addSubChapter(ChapterInterface $chapter)
    {
        $this->addElementToArrayProperty($chapter, 'subChapters');
        if (empty($chapter->getParentChapter())) {
            $chapter->setPosition(0);
        }
        $chapter->setParentChapter($this);
        $chapter->setPosition($this->getLastSubChapterPosition() + 1);
    }

    public function removeSubChapter(ChapterInterface $chapter)
    {
        $this->removeElementFromArrayProperty($chapter, 'subChapters');
        $chapter->setParentChapter(null);
    }

    public function getLastSubChapterPosition()
    {
        $position = 0;
        /** @var ChapterInterface $chapter */
        foreach ($this->subChapters as $chapter) {
            if ($chapter->getPosition() > $position) {
                $position = $chapter->getPosition();
            }
        }

        return $position;
    }

    /**
     * NOT part of schema.org.
     * A Chapter can belong to a a parent chapter.
     * @var ChapterInterface|null
     */
    protected $parentChapter;

    /**
     * NOT part of schema.org.
     * A Chapter should belong to a Book.
     * @var BookInterface|null
     */
    protected $book;

    /**
     * The page on which the work ends; for example "138" or "xvi".
     * @var string|integer
     */
    protected $pageEnd;

    /**
     * The page on which the work starts; for example "135" or "xiii".
     * @var string|integer
     */
    protected $pageStart;

    /**
     * @return int|string
     */
    public function getPageEnd()
    {
        return $this->pageEnd;
    }

    /**
     * @param int|string $pageEnd
     */
    public function setPageEnd($pageEnd): void
    {
        $this->pageEnd = $pageEnd;
    }

    /**
     * @return int|string
     */
    public function getPageStart()
    {
        return $this->pageStart;
    }

    /**
     * @param int|string $pageStart
     */
    public function setPageStart($pageStart): void
    {
        $this->pageStart = $pageStart;
    }

    /**
     * @return BookInterface|null
     */
    public function getBook(): ?BookInterface
    {
        return $this->book;
    }

    /**
     * @param BookInterface $book
     */
    public function setBook(BookInterface $book): void
    {
        $this->book = $book;
    }

    /**
     * @return array|\ArrayAccess|null
     */
    public function getSubChapters()
    {
        return $this->subChapters;
    }

    /**
     * @param array|\ArrayAccess|null $subChapters
     */
    public function setSubChapters($subChapters): void
    {
        $this->subChapters = $subChapters;
    }

    /**
     * @return ChapterInterface|null
     */
    public function getParentChapter(): ?ChapterInterface
    {
        return $this->parentChapter;
    }
}
