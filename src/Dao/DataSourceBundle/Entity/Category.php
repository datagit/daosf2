<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/5/15
 * Time: 3:49 PM
 */

namespace Dao\DataSourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Dat.Dao
 * category: id, parent_id, name, slug, enabled, created_at, updated_at
 *
 * Class Category
 * @package Dao\DataSourceBundle\Entity
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Dao\DataSourceBundle\Repository\CategoryRepository")
 *
 */
class Category
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     */
    const NUM_ITEMS = 10;

    /**
     * The identifier of the category.
     *
     * @var int
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id = null;

    /**
     * The category parent.
     *
     * @var Category
     * @ORM\OneToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     **/
    protected $parent = 0;

    /**
     * The category name.
     *
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message = "Vui lòng nhập tên danh mục.")
     */
    protected $name = null;

    /**
     * The category slug.
     *
     * @var string
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    protected $slug = null;

    /**
     * the category enabled
     *
     * @var boolean
     * @ORM\Column(name="enabled", type="boolean", options={"default":1, "comment":"1 enabled, 0 disabled"})
     */
    protected $enabled = true;

    /**
     * the category createdAt
     *
     * @var boolean
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt = null;

    /**
     * the category updatedAt
     *
     * @var boolean
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt = null;

    /**
     * Product in the category.
     *
     * @var Product[]
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     **/
    protected $products;

    /**
     * The name of the product.
     *
     * @var string
     * @ORM\Column(name="lang", type="string", length=255, nullable=true)
     */
    protected $lang = 'en';

    //st tracking--------------
    /**
     * @var string $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(type="string", nullable=true)
     */
    private $createdBy;

    /**
     * @var string $updatedBy
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(type="string", nullable=true)
     */
    private $updatedBy;

    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
    //ed tracking--------------



    /**
     * initialize default value
     */
    public function __construct() {
        //Initialize product as a Doctrine Collection
        $this->products = new ArrayCollection();
        //$this->createdAt = new \DateTime();
    }

    /** {@inheritdoc} */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Category $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return boolean
     */
    public function isCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param boolean $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return boolean
     */
    public function isUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param boolean $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product[] $products
     */
    public function setProducts($products)
    {
        $this->products->clear();
        $this->products = new ArrayCollection($products);
    }

    /**
     * Add a product in the category.
     *
     * @param $product Product The product to associate
     */
    public function addProduct($product)
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }
    }
    /**
     * @param Product $product
     */
    public function removeProduct($product)
    {
        $this->products->removeElement($product);
    }


    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    /**
     * Set createdBy
     *
     * @param string $createdBy
     *
     * @return Category
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Set updatedBy
     *
     * @param string $updatedBy
     *
     * @return Category
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param mixed $lang
     * @return Product
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

}
