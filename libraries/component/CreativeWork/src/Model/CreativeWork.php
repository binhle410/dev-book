<?php
declare(strict_types=1);

namespace Bean\Component\CreativeWork\Model;

use Bean\Component\Thing\Model\Thing;

abstract class CreativeWork extends Thing implements CreativeWorkInterface
{

    protected function rearrangePositions(&$siblings)
    {
        usort($siblings, function (CreativeWork $a, CreativeWork $b) {
            if (($ap = $a->getPosition()) < ($bp = $b->getPosition())) {
                return -1;
            } elseif ($ap === $bp) {
                return 0;
            } elseif ($ap > $bp) {
                return 1;
            }
        });

        for ($i = 0; $i < count($siblings); $i++) {
            $siblings[$i]->setPosition($i + 1);
        }
    }

    /**
     * NOT part of schema.org
     * @var CreativeWorkInterface|null $previousVersion
     */
    protected $previousVersion;

    /**
     * NOT part of schema.org
     * @var CreativeWorkInterface|null $nextVersion
     */
    protected $nextVersion;

    /**
     * Indicates a CreativeWork that is (in some sense) a part of this CreativeWork.
     * Inverse property: partOf.
     * @var \Countable|\IteratorAggregate|\ArrayAccess|array|null
     */
    protected $parts;

    /**
     * Indicates a CreativeWork that this CreativeWork is (in some sense) part of.
     * Inverse property: parts / hasPart.
     * @var CreativeWorkInterface|null
     */
    protected $partOf;

    /**
     * Derived from version: Number in schema.org
     * It also has version: Text
     * @var integer|null
     */
    protected $versionNumber = 1;

    /**
     * The position of an item in a series or sequence of items.
     * @var integer|null
     */
    protected $position = 1;

    /**
     * Headline of the article.
     * @var string|null
     */
    protected $headline;

    /**
     *    The language of the content or performance or used in an action. Please use one of the language codes from the IETF BCP 47 standard.
     * @var string
     */
    protected $locale = 'en';

    /**
     * The subject matter of the content.
     * @var string|null
     */
    protected $about;

    /**
     * The textual content of this CreativeWork.
     * @var string|null
     */
    protected $text;

    /**
     * Text or URL
     * Media type, typically MIME format (see IANA site) of the content e.g. application/zip of a SoftwareApplication binary. In cases where a CreativeWork has several media type representations, 'encoding' can be used to indicate each MediaObject alongside particular fileFormat information. Unregistered or niche file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia
     * @var string|null
     */
    protected $fileFormat;

    /**
     * @return string|null
     */
    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    /**
     * @param string $headline
     */
    public function setHeadline(string $headline): void
    {
        $this->headline = $headline;
    }


    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getAbout(): ?string
    {
        return $this->about;
    }

    /**
     * @param string $about
     */
    public function setAbout(string $about): void
    {
        $this->about = $about;
    }

    /**
     * @return string
     */
    public function getFileFormat(): ?string
    {
        return $this->fileFormat;
    }

    /**
     * @param string $fileFormat
     */
    public function setFileFormat(string $fileFormat): void
    {
        $this->fileFormat = $fileFormat;
    }

    /**
     * @return \ArrayAccess|array|null
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * @param array $parts
     */
    public function setParts(array $parts): void
    {
        $this->parts = $parts;
    }

    /**
     * @return CreativeWorkInterface
     */
    public function getPartOf(): ?CreativeWorkInterface
    {
        return $this->partOf;
    }

    /**
     * @param CreativeWorkInterface $partOf
     */
    public function setPartOf(CreativeWorkInterface $partOf): void
    {
        $this->partOf = $partOf;
    }

    /**
     * @return null|string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param null|string $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return int|null
     */
    public function getVersionNumber(): ?int
    {
        return $this->versionNumber;
    }

    /**
     * @param int|null $versionNumber
     */
    public function setVersionNumber(?int $versionNumber): void
    {
        $this->versionNumber = $versionNumber;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int|null $position
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return CreativeWorkInterface|null
     */
    public function getPreviousVersion(): ?CreativeWorkInterface
    {
        return $this->previousVersion;
    }

    /**
     * @param CreativeWorkInterface|null $previousVersion
     */
    public function setPreviousVersion(?CreativeWorkInterface $previousVersion): void
    {
        $this->previousVersion = $previousVersion;
    }

    /**
     * @return CreativeWorkInterface|null
     */
    public function getNextVersion(): ?CreativeWorkInterface
    {
        return $this->nextVersion;
    }

    /**
     * @param CreativeWorkInterface|null $nextVersion
     */
    public function setNextVersion(?CreativeWorkInterface $nextVersion): void
    {
        $this->nextVersion = $nextVersion;
    }
}
