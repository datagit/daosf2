<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/5/15
 * Time: 5:13 PM
 */

namespace Dao\DataSourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Product.
 *
 * @author Dat.Dao
 * product: id, category_id, name, slug, tags, ean, enabled,
 *              features, price, description, photo,
 *              created_at, updated_at
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="Dao\DataSourceBundle\Repository\ProductRepository")
 * @Vich\Uploadable
 */
class Product
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     */
    const NUM_ITEMS = 10;

    /**
     * The identifier of the product.
     *
     * @var int
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     *
     * @var Category;
     */
    protected $category;

    /**
     * The name of the product.
     *
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message = "Vui lòng nhập tên sản phẩm.")
     */
    protected $name;

    /**
     * The slug of product.
     *
     * @var string
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    protected $slug;


    /**
     * List of tags associated to the product.
     *
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $tags = '';

    /**
     * The EAN 13 of the product. (type set to string in PHP due to 32 bit limitation).
     *
     * @var string
     * @ORM\Column(type="bigint")
     * @Assert\NotBlank(message = "Vui lòng nhập mã sản phẩm.")
     */
    protected $ean;

    /**
     * Indicate if the product is enabled (available in store).
     *
     * @var bool
     * @ORM\Column(name="enabled", type="boolean", options={"default":1, "comment":"1 enabled, 0 disabled"})
     */
    protected $enabled = false;

    /**
     * Features of the product.
     * Associative array, the key is the name/type of the feature, and the value the data.
     * Example:<pre>array(
     *     'size' => '13cm x 15cm x 6cm',
     *     'bluetooth' => '4.1'
     * )</pre>.
     *
     * @var array
     * @ORM\Column(type="text", nullable=true)
     */
    protected $features = '{ "size":"12 x 3 x 10 cm", "color":"white" }';

    /**
     * The price of the product.
     *
     * @var float
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Type(type="float", message="Vui lòng nhập giá đúng định dạng.")
     *
     */
    protected $price = 0.0;

    /**
     * The description of the product.
     *
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

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
     * @ORM\ManyToOne(targetEntity="ConfigLang")
     *
     * @var ConfigLang;
     */
    protected $language;

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
     * Set category
     *
     * @param Category $category
     * @return Category
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get user
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    //st upload file------------------------------------
    /**
     * @Vich\UploadableField(mapping="post_image", fileNameProperty="photo")
     * @Assert\File(maxSize="2M")
     * @var File $photoFile
     */
    protected $photoFile;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Post
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setPhotoFile(File $image = null)
    {
        $this->photoFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    //ed upload file------------------------------------



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set tags
     *
     * @param string $tags
     *
     * @return Product
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set ean
     *
     * @param integer $ean
     *
     * @return Product
     */
    public function setEan($ean)
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * Get ean
     *
     * @return integer
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Product
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
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
     * Set features
     *
     * @param array $features
     *
     * @return Product
     */
    public function setFeatures($features)
    {
        $this->features = $features;

        return $this;
    }

    /**
     * Get features
     *
     * @return array
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Product
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Product
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
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
     * @return Product
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
     * @return Product
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return ConfigLang
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param ConfigLang $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }




}
