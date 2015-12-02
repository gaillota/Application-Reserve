<?php

namespace Ferus\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PrevisProduct
 *
 * @ORM\Table(name="ferus_reserve_previs_product")
 * @ORM\Entity(repositoryClass="Ferus\ProductBundle\Repository\PrevisProductRepository")
 */
class PrevisProduct
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
     * @Assert\Type(type="integer")
     * @Assert\Length(min=0)
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="Previs", inversedBy="previs_products", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $previs;

    /**
     * @ORM\ManyToOne(targetEntity="Product", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $product;

    public function __toString()
    {
        return 'Liste des produits';
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
     * Set previs
     *
     * @param \Ferus\ProductBundle\Entity\Previs $previs
     * @return PrevisProduct
     */
    public function setPrevis(Previs $previs)
    {
        $this->previs = $previs;

        return $this;
    }

    /**
     * Get previs
     *
     * @return \Ferus\ProductBundle\Entity\Previs
     */
    public function getPrevis()
    {
        return $this->previs;
    }

    /**
     * Set product
     *
     * @param \Ferus\ProductBundle\Entity\Product $product
     * @return PrevisProduct
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Ferus\ProductBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return PrevisProduct
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
}
