<?php

namespace Ferus\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BarProduct
 *
 * @ORM\Table(name="ferus_reserve_bar_product")
 * @ORM\Entity(repositoryClass="Ferus\ProductBundle\Repository\BarProductRepository")
 */
class BarProduct
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
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hour", type="time")
     */
    private $hour;

    /**
     * @ORM\ManyToOne(targetEntity="Bar", inversedBy="bar_products", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $bar;

    /**
     * @ORM\ManyToOne(targetEntity="Product", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $product;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->hour = new \DateTime();
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return BarProduct
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set bar
     *
     * @param Bar $bar
     *
     * @return BarProduct
     */
    public function setBar(Bar $bar)
    {
        $this->bar = $bar;

        return $this;
    }

    /**
     * Get bar
     *
     * @return Bar
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * Set product
     *
     * @param Product $product
     *
     * @return BarProduct
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set hour
     *
     * @param \DateTime $hour
     *
     * @return BarProduct
     */
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * Get hour
     *
     * @return \DateTime
     */
    public function getHour()
    {
        return $this->hour;
    }
}
