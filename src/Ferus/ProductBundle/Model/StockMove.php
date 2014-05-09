<?php
namespace Ferus\ProductBundle\Model;


use Ferus\ProductBundle\Entity\Place;
use Ferus\ProductBundle\Entity\Product;

class StockMove
{
    /**
     * @var Place
     */
    private $from;

    /**
     * @var Place
     */
    private $to;

    /**
     * @var Product
     */
    private $product;

    /**
     * @var integer
     */
    private $quantity;

    /**
     * @param Place $from
     */
    public function setFrom(Place $from)
    {
        $this->from = $from;
    }

    /**
     * @return Place
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param Place $to
     */
    public function setTo(Place $to)
    {
        $this->to = $to;
    }

    /**
     * @return Place
     */
    public function getTo()
    {
        return $this->to;
    }
}