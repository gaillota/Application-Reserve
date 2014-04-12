<?php

namespace Ferus\ProductBundle\Controller;

use Ferus\ProductBundle\Entity\Place;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class PlaceController extends Controller
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
            'places' => $this->em->getRepository('FerusProductBundle:Place')->findAll(),
        );
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        $place = new Place($this->request->query->get('name'));
        $this->em->persist($place);
        $this->em->flush();

        return $this->redirect($this->generateUrl('ferus_places'));
    }

    /**
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function showAction(Place $place)
    {
        return array(
            'place' => $place,
            //'products' => $this->em->getRepository('FerusProductBundle:Product')->findByCategory($place),
        );
    }
}
