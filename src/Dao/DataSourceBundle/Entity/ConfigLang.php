<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/25/15
 * Time: 11:14 AM
 */

namespace Dao\DataSourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Class ConfigLang
 * @package Dao\DataSourceBundle\Entity
 * @ORM\Table(name="config_lang")
 * @ORM\Entity(repositoryClass="Dao\DataSourceBundle\Repository\ConfigLangRepository")
 */
class ConfigLang
{
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
     * The category name.
     *
     * @var string
     * @ORM\Column(name="value", type="string", length=255)
     */
    protected $value = '';

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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }

    public function __construct($id = null) {
        $this->id = $id;
        return $this;
    }



}