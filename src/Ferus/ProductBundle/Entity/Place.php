<?php

namespace Ferus\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Place
 *
 * @ORM\Table(name="ferus_log_place")
 * @ORM\Entity(repositoryClass="Ferus\ProductBundle\Repository\PlaceRepository")
 * @UniqueEntity("name")
 */
class Place
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_place", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=40, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Stock", mappedBy="place")
     */
    protected $stocks;

    /**
     * Constructor
     */
    public function __construct($name = null)
    {
        $this->setName($name);
        $this->stocks = new ArrayCollection;
    }


    public function __toString()
    {
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     * @return Place
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
     * Add stock
     *
     * @param Stock $stock
     * @return Place
     */
    public function addStock(Stock $stock)
    {
        $this->stocks[] = $stock;

        return $this;
    }

    /**
     * Remove stock
     *
     * @param Stock $stock
     */
    public function removeStock(Stock $stock)
    {
        $this->stocks->removeElement($stock);
    }

    /**
     * Get stocks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStocks()
    {
        return $this->stocks;
    }
}
