<?php

namespace Ferus\ProductBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class HomeController extends Controller
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @return array
     * @Template
     */
    public function indexAction()
    {
        //Nombre de prévis à afficher sur la page d'accueil
        $nbPrevis = 3;

        //Nombre de soirées à afficher sur la page d'accueil
        $nbEvents = 3;

        $listPrevis = $this->em->getRepository('FerusEventBundle:Previs')->findBy(array(), array('id' => 'DESC'), $nbPrevis);
        $listEvents = $this->em->getRepository('FerusEventBundle:Event')->findBy(array(), array('date' => 'DESC'), $nbEvents);

        return array(
            'listPrevis' => $listPrevis,
            'listEvents' => $listEvents
        );
    }

} 