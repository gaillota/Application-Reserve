<?php

namespace Ferus\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Event
 *
 * @ORM\Table(name="ferus_reserve_event")
 * @ORM\Entity(repositoryClass="Ferus\ProductBundle\Repository\EventRepository")
 */
class Event
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     *
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @ORM\OneToOne(targetEntity="Previs", mappedBy="event")
     */
    private $previs;

    /**
     * @ORM\OneToMany(targetEntity="Bar", mappedBy="event", cascade={"persist"}, orphanRemoval=true)
     */
    private $bars;


    /**
     * Constructor
     */
    function __construct()
    {
        $date = new \DateTime();
        $date->setTime(22, 30);
        $this->date = $date;

        $this->bars = new ArrayCollection();
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
     * @return Event
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
     * Set date
     *
     * @param \DateTime $date
     * @return Event
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set previs
     *
     * @param integer $previs
     * @return Event
     */
    public function setPrevis(Previs $previs)
    {
        $this->previs = $previs;

        $previs->setEvent($this);

        return $this;
    }

    /**
     * Get previs
     *
     * @return integer 
     */
    public function getPrevis()
    {
        return $this->previs;
    }

    /**
     * Add bar
     *
     * @param Bar $bar
     *
     * @return Event
     */
    public function addBar(Bar $bar)
    {
        $this->bars[] = $bar;
        $bar->setEvent($this);

        return $this;
    }

    /**
     * Remove bar
     *
     * @param Bar $bar
     */
    public function removeBar(Bar $bar)
    {
        $this->bars->removeElement($bar);
    }

    /**
     * Get bars
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBars()
    {
        return $this->bars;
    }
}
