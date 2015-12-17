<?php

namespace Ferus\EventBundle\Controller;


use Doctrine\ORM\EntityManager;
use Ferus\EventBundle\Entity\Event;
use Ferus\AdminBundle\Entity\Historical;
use Ferus\EventBundle\Form\EventType;
use Ferus\EventBundle\Form\EventEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EventController extends Controller
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
     * @return array
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction()
    {
        $search = $this->request->query->get('search', null);
        $search = isset($search) ? (!empty($search) ? $search : null) : null; //BRAAAH

        if (null !== $search) {
            $listEvents = $this->em->getRepository('FerusEventBundle:Event')->findSearch($search);
        } else {
            $listEvents = $this->em->getRepository('FerusEventBundle:Event')->findBy(array(), array(
                'date' => 'DESC'
            ));
        }

        return array(
            'events' => $listEvents,
            'search' => $search,
        );
    }

    /**
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        $event = new Event();
        $form = $this->createForm(new EventType(), $event);

        if ($form->handleRequest($this->request)->isValid()) {
            $this->em->persist($event);

            $historical = new Historical();
            $historical->setAuthor($this->getUser());
            $historical->setEntity('Événement');
            $historical->setAction('Création de "'.$event->getName().'".');
            $this->em->persist($historical);

            $this->em->flush();

            $newPrevis = $form->get('saveAndAdd')->isClicked();

            if ($newPrevis) {
                return $this->redirect($this->generateUrl('ferus_previs_new', array(
                    'event' => $event->getId()
                )));
            } else {
                return $this->redirect($this->generateUrl('ferus_event'));
            }

        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Event $event
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Event $event)
    {
        $form = $this->createForm(new EventEditType($event->getPrevis()), $event);

        if ($form->handleRequest($this->request)->isValid()) {
            $this->em->persist($event);

            $historical = new Historical();
            $historical->setAuthor($this->getUser());
            $historical->setEntity('Événement');
            $historical->setAction('Modification de "'.$event->getName().'".');
            $this->em->persist($historical);

            $this->em->flush();

            return $this->redirect($this->generateUrl('ferus_event'));
        }

        return array(
            'form' => $form->createView(),
            'event' => $event
        );
    }

    /**
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function removeAction(Event $event)
    {
        $form = $this->createFormBuilder()->getForm();

        if ($this->request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $this->em->remove($event);

            $historical = new Historical();
            $historical->setAuthor($this->getUser());
            $historical->setEntity('Evénement');
            $historical->setAction('Suppression de "'.$event->getName().'".');
            $this->em->persist($historical);

            $this->em->flush();

            return $this->redirect($this->generateUrl('ferus_event'));
        }

        return array(
            'event' => $event,
            'form' => $form->createView()
        );
    }

    /**
     * @param Event $event
     * @return array
     * @Template
     */
    public function listBarsAction(Event $event)
    {
        if (null === $event->getPrevis())
            throw new AccessDeniedException("Cette soirée ne contient pas de prévis, il est donc impossible d'accéder à cette section.");

        $previs = $this->em->getRepository('FerusEventBundle:Previs')->findOneBy(array(
            'event' => $event
        ));

        return array(
            'event' => $event,
            'previs' => $previs
        );
    }

} 