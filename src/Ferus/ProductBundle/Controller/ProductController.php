<?php

namespace Ferus\ProductBundle\Controller;

use Ferus\ProductBundle\Entity\Category;
use Ferus\ProductBundle\Entity\Product;
use Ferus\ProductBundle\Form\ProductType;
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
            'products' => $this->em->getRepository('FerusProductBundle:Product')->findBy(array(), array('name' => 'asc')),
        );
    }

    /**
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        $product = new Product;

        // Set name if defined
        $product->setName($this->request->query->get('name', null));

        // Set category if defined
        if($this->request->query->has('category_id'))
            $product->setCategory(
                $this->em->getRepository('FerusProductBundle:Category')->findOneById(
                    $this->request->query->get('category_id')
                )
            );

        $form = $this->createForm(new ProductType, $product);

        if($this->request->isMethod('post')){
            $form->handleRequest($this->request);

            if($form->isValid()){
                $this->em->persist($product);
                $this->em->flush();

                return $this->redirect($this->generateUrl('ferus_products'));
            }
        }

        return array(
            'form' => $form->createView(),
            'category' => $this->request->query->get('category_id', null),
        );
    }

    /**
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function showAction(Product $product)
    {
        return array(
            'product' => $product,
        );
    }

    /**
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Product $product)
    {
        $form = $this->createForm(new ProductType, $product);

        if($this->request->isMethod('post')){
            $form->handleRequest($this->request);

            if($form->isValid()){
                $this->em->persist($product);
                $this->em->flush();

                return $this->redirect($this->generateUrl('ferus_products_show', array('id' => $product->getId())));
            }
        }

        return array(
            'product' => $product,
            'form' => $form->createView(),
        );
    }
}
