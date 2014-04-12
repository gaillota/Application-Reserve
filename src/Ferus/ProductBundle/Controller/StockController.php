<?php

namespace Ferus\ProductBundle\Controller;

use Ferus\ProductBundle\Entity\Stock;
use Ferus\ProductBundle\Form\StockType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class StockController extends Controller
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
    public function addAction()
    {
        $stock = new Stock;

        // Set product if defined
        if($this->request->query->has('product'))
            $stock->setProduct(
                $this->em->getRepository('FerusProductBundle:Product')->findOneById(
                    $this->request->query->get('product', null)
                )
            );

        // Set place if defined
        if($this->request->query->has('place'))
            $stock->setPlace(
                $this->em->getRepository('FerusProductBundle:Place')->findOneById(
                    $this->request->query->get('place', null)
                )
            );

        $form = $this->createForm(new StockType, $stock);

        if($this->request->isMethod('post')){
            $form->handleRequest($this->request);

            if($form->isValid()){
                /** @var Stock */$allReadyExist = $this->em->getRepository('FerusProductBundle:Stock')->findOneBy(array(
                    'product' => $stock->getProduct(),
                    'place' => $stock->getPlace()
                ));

                if($allReadyExist !== null)
                    $allReadyExist->add($stock->getNumber());
                else
                    $allReadyExist = $stock;

                $this->em->persist($allReadyExist);
                $this->em->flush();

                return $this->redirect($this->generateUrl('ferus_products_show', array('id' => $stock->getProduct()->getId())));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
