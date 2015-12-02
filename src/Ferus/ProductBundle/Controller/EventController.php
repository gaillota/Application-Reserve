<?php

namespace Ferus\ProductBundle\Controller;


use Doctrine\ORM\EntityManager;
use Ferus\ProductBundle\Entity\Event;
use Ferus\ProductBundle\Entity\Historical;
use Ferus\ProductBundle\Form\EventType;
use Ferus\ProductBundle\Form\EventEditType;
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
     * @return array
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction(Request $request)
    {
        $search = $request->query->get('search', null);
        $search = isset($search) ? (!empty($search) ? $search : null) : null; //BRAAAH

        if (null !== $search) {
            $listEvents = $this->em->getRepository('FerusProductBundle:Event')->findSearch($search);
        } else {
            $listEvents = $this->em->getRepository('FerusProductBundle:Event')->findBy(array(), array(
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
    public function newAction(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(new EventType(), $event);

        if ($form->handleRequest($request)->isValid()) {
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
    public function editAction(Event $event, Request $request)
    {
        $form = $this->createForm(new EventEditType($event->getPrevis()), $event);

        if ($form->handleRequest($request)->isValid()) {
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
    public function removeAction(Event $event, Request $request)
    {
        $form = $this->createFormBuilder()->getForm();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
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

        $previs = $this->em->getRepository('FerusProductBundle:Previs')->findOneBy(array(
            'event' => $event
        ));

        return array(
            'event' => $event,
            'previs' => $previs
        );
    }

} 