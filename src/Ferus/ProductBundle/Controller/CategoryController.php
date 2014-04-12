<?php

namespace Ferus\ProductBundle\Controller;

use Ferus\ProductBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class CategoryController extends Controller
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
            'categories' => $this->em->getRepository('FerusProductBundle:Category')->findAll(),
        );
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        $category = new Category($this->request->query->get('name'));
        $this->em->persist($category);
        $this->em->flush();

        return $this->redirect($this->generateUrl('ferus_categories'));
    }

    /**
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function showAction(Category $category)
    {
        return array(
            'category' => $category,
            'products' => $this->em->getRepository('FerusProductBundle:Product')->findByCategory($category),
            'stocks' => $this->em->getRepository('FerusProductBundle:Stock')->findStocks(),
        );
    }
}
