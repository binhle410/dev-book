<?php
declare(strict_types=1);

namespace Bean\Component\Thing\Model;

/**
 * Class Thing: The most generic type of item.
 * @package Bean\Component\Thing\Model
 */
abstract class Thing implements ThingInterface
{

    protected $id;

    function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __clone()
    {
        if ($this->id) {
            $this->id = null;
            $objProps = $this->getObjectProperties();
            foreach ($objProps as $prop) {
                $this->{$prop} = clone $this->{$prop};
            }
            $objArrayProps = $this->getObjectArrayProperties();
            foreach ($objArrayProps as $prop => $inversedMethod) {
                $cloned = [];
                foreach ($this->{$prop} as $item) {
                    $clonedItem = clone $item;
                    $clonedItem->{$inversedMethod}($this);
                    $cloned[] = $clonedItem;
                }
                $this->{$prop} = $cloned;
            }
        }
    }

    public function copyScalarPropertiesFrom(ThingInterface $thing)
    {
        $vars = get_object_vars($this);
        foreach ($vars as $prop => $value) {
            $setter = 'set' . ucfirst($prop);
            $getter = 'get' . ucfirst($prop);
            if (empty($value) && method_exists($thing, $setter)) {
                if (!method_exists($thing, $getter)) {
                    $getter = 'is' . ucfirst($prop);
                }
                if (method_exists($thing, $setter)) {
                    $getterValue = $thing->$getter();
                    if (is_scalar($getterValue)) {
                        $this->$setter($getterValue);
                    }
                }
            }
        }
//		$m_person->setEmail($email);
//		$m_person->setFamilyName($person->getFamilyName());
//		$m_person->setGivenName($person->getGivenName());
//		$m_person->setName($person->getName());
//		$m_person->setEnabled(true);
//		$m_person->setHomeAddress($person->getHomeAddress());
//		$m_person->setTelephone($person->getTelephone());
//		$m_person->setBirthDate($person->getBirthDate());
//		$m_person->setDescription($person->getDescription());
    }

    protected function getObjectArrayProperties()
    {
        return [];
    }

    protected function getObjectProperties()
    {
        return [];
    }

    /**
     * NOT part of schema.org
     *
     * @param $element
     * @param $prop
     *
     * @return bool
     */
    protected function addElementToArrayProperty($element, $prop)
    {
        $this->{$prop}[] = $element;

        return true;

    }

    /**
     * NOT part of schema.org
     *
     * @param $el
     * @param $array
     *
     * @return bool
     */
    protected function removeElementFromArrayProperty($element, $prop)
    {
        $key = array_search($element, $this->{$prop}, true);
        if ($key === false) {
            return false;
        }
        unset($this->{$prop}[$key]);

        return true;
    }

    /**
     * NOT part of schema.org
     * @var boolean
     */
    protected $enabled = false;

    /**
     * NOT part of schema.org
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * NOT part of schema.org
     * @var \DateTime|null
     */
    protected $updatedAt;

    /**
     * NOT part of schema.org
     * A thing may have a status like DRAFT, OPEN, CLOSED, EXPIRED, ARCHIVED
     * @var string|null
     */
    protected $status;

    /**
     * The name of the item.
     * @var string|null
     */
    protected $name;

    /**
     * A description of the item.
     * @var string|null
     */
    protected $description;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt = null): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt = null): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * @param null|string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return null|string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param null|string $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

}
