<?php

namespace Ferus\ProductBundle\Controller;

use Ferus\ProductBundle\Entity\Stock;
use Ferus\ProductBundle\Form\StockMoveType;
use Ferus\ProductBundle\Form\StockType;
use Ferus\ProductBundle\Model\StockMove;
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

    /**
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function moveAction()
    {
        $stockMove = new StockMove;

        // Set product if defined
        if($this->request->query->has('product'))
            $stockMove->setProduct(
                $this->em->getRepository('FerusProductBundle:Product')->findOneById(
                    $this->request->query->get('product', null)
                )
            );

        // Set place if defined
        if($this->request->query->has('from'))
            $stockMove->setFrom(
                $this->em->getRepository('FerusProductBundle:Place')->findOneById(
                    $this->request->query->get('from', null)
                )
            );
        $form = $this->createForm(new StockMoveType, $stockMove);

        if($this->request->isMethod('post')){
            $form->handleRequest($this->request);

            if($form->isValid()){

                // Remove from source
                /** @var Stock */$allReadyExist = $this->em->getRepository('FerusProductBundle:Stock')->findOneBy(array(
                    'product' => $stockMove->getProduct(),
                    'place' => $stockMove->getFrom()
                ));

                if($allReadyExist === null || $allReadyExist->getNumber() < $stockMove->getQuantity()){
                    return array(
                        'form' => $form->createView(),
                        'error' => 'Pas assez de '.$stockMove->getProduct()->getName().' dans la '.$stockMove->getFrom()->getName(),
                    );
                }

                $allReadyExist->sub($stockMove->getQuantity());

                if($allReadyExist->getNumber() == 0)
                    $this->em->remove($allReadyExist);
                else
                    $this->em->persist($allReadyExist);

                // Add to destination
                /** @var Stock */$destination = $this->em->getRepository('FerusProductBundle:Stock')->findOneBy(array(
                    'product' => $stockMove->getProduct(),
                    'place' => $stockMove->getTo()
                ));

                if($destination !== null)
                    $destination->add($stockMove->getQuantity());
                else{
                    $destination = new Stock;
                    $destination->setPlace($stockMove->getTo());
                    $destination->setProduct($stockMove->getProduct());
                    $destination->setNumber($stockMove->getQuantity());
                }

                $this->em->persist($destination);

                // flush
                $this->em->flush();

                return $this->redirect($this->generateUrl('ferus_products_show', array('id' => $stockMove->getProduct()->getId())));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function removeAction()
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

                if($allReadyExist === null || $allReadyExist->getNumber() < $stock->getNumber()){
                    return array(
                        'form' => $form->createView(),
                        'error' => 'Pas assez de '.$stock->getProduct()->getName().' dans la '.$stock->getPlace()->getName(),
                    );
                }

                $allReadyExist->sub($stock->getNumber());

                if($allReadyExist->getNumber() == 0)
                    $this->em->remove($allReadyExist);
                else
                    $this->em->persist($allReadyExist);

                // flush
                $this->em->flush();

                return $this->redirect($this->generateUrl('ferus_products_show', array('id' => $stock->getProduct()->getId())));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
