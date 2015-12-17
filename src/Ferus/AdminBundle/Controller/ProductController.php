<?php

namespace Ferus\AdminBundle\Controller;

use Ferus\UserBundle\Entity\User;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class ProductController extends Controller
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @return array
     * @Template
     */
    public function listAction()
    {
        return array(
            'listProducts' => $this->em->getRepository('FerusProductBundle:Product')->findBy(array(), array(
                'category' => 'DESC',
                'name' => 'ASC',
            ))
        );
    }
}
