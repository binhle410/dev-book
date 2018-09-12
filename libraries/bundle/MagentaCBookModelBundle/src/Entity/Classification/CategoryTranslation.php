<?php
namespace Magenta\Bundle\CBookModelBundle\Entity\Classification;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;


/**
 * @ORM\Entity
 * @ORM\Table(name="classification__category_trans"
 *     )}
 * )
 */
class CategoryTranslation
{
    use ORMBehaviors\Translatable\Translation;
	
	/**
	 * @var string
	 * @ORM\Column(type="string",length=5)
	 */
    protected $locale;
    
    /**
     * Convenient constructor
     *
     * @param string $locale
     * @param string $field
     * @param string $value
     */
//    public function __construct($locale, $field, $value)
//    {
//        $this->setLocale($locale);
//    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 */
    protected $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $description;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  string
     * @return null
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param  string
     * @return null
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
	
	/**
	 * @return mixed
	 */
	public function getSlug() {
		return $this->slug;
	}
	
	/**
	 * @param mixed $slug
	 */
	public function setSlug($slug): void {
		$this->slug = $slug;
	}
}
