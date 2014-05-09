<?php

namespace Ferus\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table(name="ferus_log_stock")
 * @ORM\Entity(repositoryClass="Ferus\ProductBundle\Repository\StockRepository")
 */
class Stock
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="stocks", fetch="EAGER")
     * @ORM\JoinColumn(name="id_product", referencedColumnName="id_product")
     */
    protected $product;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="stocks", fetch="EAGER")
     * @ORM\JoinColumn(name="id_place", referencedColumnName="id_place")
     */
    protected $place;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    public function __toString()
    {
        return ''.$this->number;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return Stock
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param integer $number
     */
    public function add($number)
    {
        $this->number += $number;
    }

    /**
     * @param integer $number
     */
    public function sub($number)
    {
        $this->number -= $number;
    }

    /**
     * Set product
     *
     * @param Product $product
     * @return Stock
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
     * Set place
     *
     * @param Place $place
     * @return Stock
     */
    public function setPlace(Place $place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return Place
     */
    public function getPlace()
    {
        return $this->place;
    }
}
