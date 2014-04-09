<?php

namespace Ferus\ProductBundle\Controller;

use Ferus\ProductBundle\Entity\Category;
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
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction()
    {
        return array(
            'products' => $this->em->getRepository('FerusProductBundle:Product')->findAll(),
        );
    }

    /**
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {

        return array(

        );
    }
}
