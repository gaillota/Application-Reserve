<?php

namespace Ferus\EventBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ferus\EventBundle\Entity\Event;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Previs
 *
 * @ORM\Table(name="ferus_reserve_previs")
 * @ORM\Entity(repositoryClass="Ferus\EventBundle\Repository\PrevisRepository")
 */
class Previs
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=40, unique=false)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="Ferus\EventBundle\Entity\Event", inversedBy="previs")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $event;

    /**
     * @ORM\OneToMany(targetEntity="Ferus\EventBundle\Entity\PrevisProduct", mappedBy="previs", cascade={"all"}, orphanRemoval=true)
     */
    private $previs_products;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->previs_products = new ArrayCollection();
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
     * Set event
     *
     * @param Event $event
     * @return Previs
     */
    public function setEvent(Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Previs
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
        if (null === $this->name && null !== $this->event)
            $this->setName('Prévis ' . $this->event->getName());
        return $this->name;
    }

    /**
     * Add previs_products
     *
     * @param PrevisProduct $previsProducts
     * @return Previs
     */
    public function addPrevisProduct(PrevisProduct $previsProducts)
    {
        $this->previs_products[] = $previsProducts;

        $previsProducts->setPrevis($this);

        return $this;
    }

    /**
     * Remove previs_products
     *
     * @param PrevisProduct $previsProducts
     */
    public function removePrevisProduct(PrevisProduct $previsProducts)
    {
        $this->previs_products->removeElement($previsProducts);
    }

    /**
     * Get previs_products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrevisProducts()
    {
        return $this->previs_products;
    }

    public function getCoutHT()
    {
        $coutHT = 0;
        foreach ($this->getPrevisProducts() as $previs_product) {
            $quantity = ($previs_product->getQuantity() - $previs_product->getProduct()->getQuantity()) < 0 ? 0 : $previs_product->getQuantity() - $previs_product->getProduct()->getQuantity();
            $coutHT += $quantity * $previs_product->getProduct()->getPrice();
        }

        return $coutHT;
    }

    public function getCoutTTC()
    {
        $coutTTC = 0;
        foreach ($this->getPrevisProducts() as $previs_product) {
            $quantity = ($previs_product->getQuantity() - $previs_product->getProduct()->getQuantity()) < 0 ? 0 : $previs_product->getQuantity() - $previs_product->getProduct()->getQuantity();
            $coutTTC += $quantity * ($previs_product->getProduct()->getPrice() * $previs_product->getProduct()->getTaxes() / 100 + $previs_product->getProduct()->getPrice());
        }

        return $coutTTC;
    }
}
