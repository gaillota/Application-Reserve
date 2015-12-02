<?php

namespace Ferus\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Bar
 *
 * @ORM\Table(name="ferus_reserve_bar")
 * @ORM\Entity(repositoryClass="Ferus\ProductBundle\Repository\BarRepository")
 */
class Bar
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
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="bars")
     * @Assert\NotNull()
     */
    private $event;

    /**
     * @ORM\OneToMany(targetEntity="BarProduct", mappedBy="bar", cascade={"all"}, orphanRemoval=true)
     */
    private $output;

    /**
     * @ORM\OneToMany(targetEntity="BarProduct", mappedBy="bar", cascade={"all"}, orphanRemoval=true)
     */
    private $firstPeriod;

    /**
     * @ORM\OneToMany(targetEntity="BarProduct", mappedBy="bar", cascade={"all"}, orphanRemoval=true)
     */
    private $secondPeriod;

    /**
     * @ORM\OneToMany(targetEntity="BarProduct", mappedBy="bar", cascade={"all"}, orphanRemoval=true)
     */
    private $thirdPeriod;

    /**
     * @ORM\OneToMany(targetEntity="BarProduct", mappedBy="bar", cascade={"all"}, orphanRemoval=true)
     */
    private $input;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bar_products = new ArrayCollection();
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
     *
     * @return Bar
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
     * Set event
     *
     * @param Event $event
     *
     * @return Bar
     */
    public function setEvent(Event $event)
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
     * Add output
     *
     * @param BarProduct $output
     *
     * @return Bar
     */
    public function addOutput(BarProduct $output)
    {
        $this->output[] = $output;

        return $this;
    }

    /**
     * Remove output
     *
     * @param BarProduct $output
     */
    public function removeOutput(BarProduct $output)
    {
        $this->output->removeElement($output);
    }

    /**
     * Get output
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Add firstPeriod
     *
     * @param BarProduct $firstPeriod
     *
     * @return Bar
     */
    public function addFirstPeriod(BarProduct $firstPeriod)
    {
        $this->firstPeriod[] = $firstPeriod;

        return $this;
    }

    /**
     * Remove firstPeriod
     *
     * @param BarProduct $firstPeriod
     */
    public function removeFirstPeriod(BarProduct $firstPeriod)
    {
        $this->firstPeriod->removeElement($firstPeriod);
    }

    /**
     * Get firstPeriod
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFirstPeriod()
    {
        return $this->firstPeriod;
    }

    /**
     * Add secondPeriod
     *
     * @param BarProduct $secondPeriod
     *
     * @return Bar
     */
    public function addSecondPeriod(BarProduct $secondPeriod)
    {
        $this->secondPeriod[] = $secondPeriod;

        return $this;
    }

    /**
     * Remove secondPeriod
     *
     * @param BarProduct $secondPeriod
     */
    public function removeSecondPeriod(BarProduct $secondPeriod)
    {
        $this->secondPeriod->removeElement($secondPeriod);
    }

    /**
     * Get secondPeriod
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSecondPeriod()
    {
        return $this->secondPeriod;
    }

    /**
     * Add thirdPeriod
     *
     * @param BarProduct $thirdPeriod
     *
     * @return Bar
     */
    public function addThirdPeriod(BarProduct $thirdPeriod)
    {
        $this->thirdPeriod[] = $thirdPeriod;

        return $this;
    }

    /**
     * Remove thirdPeriod
     *
     * @param BarProduct $thirdPeriod
     */
    public function removeThirdPeriod(BarProduct $thirdPeriod)
    {
        $this->thirdPeriod->removeElement($thirdPeriod);
    }

    /**
     * Get thirdPeriod
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getThirdPeriod()
    {
        return $this->thirdPeriod;
    }

    /**
     * Add input
     *
     * @param BarProduct $input
     *
     * @return Bar
     */
    public function addInput(BarProduct $input)
    {
        $this->input[] = $input;

        return $this;
    }

    /**
     * Remove input
     *
     * @param BarProduct $input
     */
    public function removeInput(BarProduct $input)
    {
        $this->input->removeElement($input);
    }

    /**
     * Get input
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInput()
    {
        return $this->input;
    }
}
