<?php

namespace Ferus\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ferus\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Historical
 *
 * @ORM\Table(name="ferus_reserve_historical")
 * @ORM\Entity(repositoryClass="Ferus\ProductBundle\Repository\HistoricalRepository")
 */
class Historical
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Ferus\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     * @Assert\Valid()
     */
    private $author;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $entity;


    /**
     * Constructor
     */
    function __construct()
    {
        $this->date = new \DateTime();
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Historical
     */
    public function setDate($date)
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
     * Set action
     *
     * @param string $action
     *
     * @return Historical
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set author
     *
     * @param User $author
     *
     * @return Historical
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set entity
     *
     * @param string $entity
     *
     * @return Historical
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
