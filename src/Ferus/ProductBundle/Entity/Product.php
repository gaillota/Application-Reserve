<?php

namespace Ferus\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table(name="ferus_reserve_product")
 * @ORM\Entity(repositoryClass="Ferus\ProductBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer")
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="taxes", type="integer")
     * @Assert\NotBlank()
     */
    private $taxes;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=40)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=40)
     * @Assert\NotBlank()
     */
    private $unit;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     * @Assert\Type(type="integer", message ="La valeur {{ value }} n'est pas un entier valide.")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $category;


    public function __construct()
    {
        $this->quantity = 0;
        $this->price = '1000';
    }


    public function __toString()
    {
        return $this->name;
    }


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
     * Set price
     *
     * @param integer $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = intval(100 * $price);

        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        if($this->price == null) return null;
        return $this->price / 100;
    }

    /**
     * Set taxes
     *
     * @param integer $taxes
     * @return Product
     */
    public function setTaxes($taxes)
    {
        $this->taxes = intval(100 * $taxes);

        return $this;
    }

    /**
     * Get taxes
     *
     * @return integer 
     */
    public function getTaxes()
    {
        return null == $this->taxes ? null : $this->taxes / 100;
    }

    /**
     * Set name
     *
     * @param string $name
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
     * Set category
     *
     * @param Category $category
     * @return Product
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set unit
     *
     * @param string $unit
     * @return Product
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string 
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set quantity
     *
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Add quantity
     *
     * @param $quantity
     */
    public function addQuantity($quantity)
    {
        $this->quantity = $this->quantity + $quantity;
    }
}
