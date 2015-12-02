<?php

namespace Ferus\ProductBundle\Controller;

use Ferus\UserBundle\Entity\User;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class AdminController extends Controller
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
    public function indexAction()
    {
        return array();
    }

    /**
     * @return array
     * @Template
     */
    public function historicalAction(User $user = null)
    {
        $listHistorical = $this->paginator->paginate(
            $this->em->getRepository('FerusProductBundle:Historical')->getHistoricalByUser($user),
            $page = $this->request->query->getInt('page', 1),
            25
        );

        return array(
            'listHistorical' => $listHistorical,
            'userSelected' => $user,
            'listUsers' => $this->container->get('fos_user.user_manager')->findUsers(),
        );
    }

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
