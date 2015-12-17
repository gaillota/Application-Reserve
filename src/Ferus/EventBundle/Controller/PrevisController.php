<?php

namespace Ferus\EventBundle\Controller;


use Doctrine\ORM\EntityManager;
use Ferus\AdminBundle\Entity\Historical;
use Ferus\EventBundle\Entity\Previs;
use Ferus\ProductBundle\Form\PrevisType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class PrevisController extends Controller
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
     */
    public function indexAction()
    {
        $search = $this->request->query->get('search', null);
        $search = isset($search) ? (!empty($search) ? $search : null) : null; //BRAAAH BRAH
        
        if (null !== $search) {
            $listPrevis = $this->em->getRepository('FerusEventBundle:Previs')->findSearch($search);
        } else {
            $listPrevis = $this->em->getRepository('FerusEventBundle:Previs')->findAll();
        }

        return array(
            'search' => $search,
            'listPrevis' => $listPrevis,
        );
    }

    /**
     * @param Previs $previs
     * @return array
     * @Template
     */
    public function showAction(Previs $previs)
    {
        return array(
            'previs' => $previs,
        );
    }

    /**
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template
     */
    public function newAction()
    {
        $previs = new Previs;
        $event_id = $this->request->query->get('event', null);
        $event = null;
        if (null !== $event_id && !empty($event_id)) {
            $event = $this->em->getRepository('FerusEventBundle:Event')->find($event_id);

            if (null !== $event) {
                $previs->setEvent($event);
            }
        }
        $form = $this->createForm(new PrevisType($event), $previs);

        if ($form->handleRequest($this->request)->isValid()) {
            $this->em->persist($previs);

            $historical = new Historical();
            $historical->setAuthor($this->getUser());
            $historical->setEntity("Prévis");
            $historical->setAction('Nouveau prévis : "'.$previs->getName().'".');
            $this->em->persist($historical);

            $this->em->flush();

            return $this->redirect($this->generateUrl('ferus_previs_show', array('id' => $previs->getId())));
        }

        return array(
            'form' => $form->createView(),
            'previs' => $previs,
        );
    }

    /**
     * @param Request $request
     * @param Previs $previs
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template
     */
    public function editAction(Previs $previs)
    {
        $form = $this->createForm(new PrevisType($previs->getEvent()), $previs);

        if ($form->handleRequest($this->request)->isValid()) {
            $this->em->persist($previs);

            $historical = new Historical();
            $historical->setAuthor($this->getUser());
            $historical->setEntity("Prévis");
            $historical->setAction('Modification de "'.$previs->getName().'".');
            $this->em->persist($historical);

            $this->em->flush();

            return $this->redirect($this->generateUrl('ferus_previs_show', array('id' => $previs->getId())));
        }

        return array(
            'form' => $form->createView(),
            'previs' => $previs
        );
    }

    /**
     * @param Previs $previs
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Template
     */
    public function removeAction(Previs $previs)
    {
        $form = $this->createFormBuilder()->getForm();

        if ($this->request->isMethod('POST') && $form->handleRequest($this->request)->isValid()) {
            $this->em->remove($previs);

            $historical = new Historical();
            $historical->setAuthor($this->getUser());
            $historical->setEntity("Prévis");
            $historical->setAction('Suppression de "'.$previs->getName().'".');
            $this->em->persist($historical);

            $this->em->flush();

            return $this->redirect($this->generateUrl('ferus_previs'));
        }

        return array(
            'previs' => $previs,
            'form' => $form->createView()
        );
    }

    /**
     * @param Previs $previs
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Template
     */
    public function calculAction(Previs $previs)
    {
        $coutHT = $previs->getCoutHT();
        $coutTTC = $previs->getCoutTTC();

        return array(
            'previs' => $previs,
            'coutHT' => $coutHT,
            'coutTTC' => $coutTTC,
        );
    }

    /**
     * @param Previs $previs
     * @return mixed
     */
    public function downloadPDFAction(Previs $previs)
    {
        $coutHT = $previs->getCoutHT();
        $coutTTC = $previs->getCoutTTC();

        $name = str_replace(' ', '_', $previs->getName());
        $name = str_replace('\'', '_', $name);

        $pdfGenerator = $this->get('siphoc.pdf.generator');
        $pdfGenerator->setName($name.'.pdf');

        $historical = new Historical();
        $historical->setAuthor($this->getUser());
        $historical->setEntity("Prévis");
        $historical->setAction('Téléchargement du PDF pour "'.$previs->getName().'".');
        $this->em->persist($historical);

        $this->em->flush();

        return $pdfGenerator->downloadFromView(
            'FerusEventBundle:Previs:pdf.html.twig', array(
                'previs' => $previs,
                'coutHT' => $coutHT,
                'coutTTC' => $coutTTC,
            )
        );
    }

    /**
     * @param Previs $previs
     * @return array
     * @Template
     */
    public function selfMailAction(Previs $previs)
    {
        $coutHT = $previs->getCoutHT();
        $coutTTC = $previs->getCoutTTC();

        $name = str_replace(' ', '_', $previs->getName());
        $name = str_replace('\'', '_', $name);

        $pdfGenerator = $this->get('siphoc.pdf.generator');
        $pdfGenerator->setName($name.'.pdf');

        $path = __DIR__.'/../../../../web/pdf/'.$name.'.pdf';

        if (file_exists($path))
            unlink($path);

        $html = $pdfGenerator->getTemplatingEngine()->render(
            'FerusEventBundle:Previs:pdf.html.twig', array(
                'previs' => $previs,
                'coutHT' => $coutHT,
                'coutTTC' => $coutTTC,
            )
        );
        $html = $pdfGenerator->getCssConverter()->convertToString($html);
        $html = $pdfGenerator->getJSConverter()->convertToString($html);

        $pdf = $pdfGenerator->generateFromHtml($html, $path);

        $message = \Swift_Message::newInstance()
            ->setSubject('['.$previs->getName().'] Liste des courses - Envoyé par '.$this->getUser()->getUsername())
            ->setFrom('reserve@bde.esiee.fr')
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                $this->renderView('FerusEventBundle:Previs:mail.txt.twig', array(
                    'previs' => $previs,
                    'user' => $this->getUser(),
                ))
            )
            ->attach(\Swift_Attachment::fromPath($path));
        ;

        $this->get('mailer')->send($message);

        unlink($path);

        $historical = new Historical();
        $historical->setAuthor($this->getUser());
        $historical->setEntity("Prévis");
        $historical->setAction('Envoi du PDF par mail pour "'.$previs->getName().'" à soi-même.');
        $this->em->persist($historical);

        $this->em->flush();

        $this->get('session')->getFlashBag()->add(
            'notice',
            'E-mail envoyé avec succès (moi uniquement).'
        );

        return $this->redirect($this->generateUrl('ferus_previs_calcul', array('id' => $previs->getId())));
    }

    /**
     * @param Previs $previs
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function allMailAction(Previs $previs)
    {
        $coutHT = $previs->getCoutHT();
        $coutTTC = $previs->getCoutTTC();

        $name = str_replace(' ', '_', $previs->getName());
        $name = str_replace('\'', '_', $name);

        $pdfGenerator = $this->get('siphoc.pdf.generator');
        $pdfGenerator->setName($name.'.pdf');

        $path = __DIR__.'/../../../../web/pdf/'.$name.'.pdf';

        if (file_exists($path))
            unlink($path);

        $html = $pdfGenerator->getTemplatingEngine()->render(
            'FerusEventBundle:Previs:pdf.html.twig', array(
                'previs' => $previs,
                'coutHT' => $coutHT,
                'coutTTC' => $coutTTC,
            )
        );
        $html = $pdfGenerator->getCssConverter()->convertToString($html);
        $html = $pdfGenerator->getJSConverter()->convertToString($html);

        $pdf = $pdfGenerator->generateFromHtml($html, $path);

        $usersManager = $this->get('fos_user.user_manager');
        $users = $usersManager->findUsers();
        $mailRecipients = array();
        foreach ($users as $user) {
            if ($user->getNotified()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject('Prévis : '.$previs->getName().' - Envoyé par '.$this->getUser()->getUsername())
                    ->setFrom('reserve@bde.esiee.fr')
                    ->setTo($this->getUser()->getEmail())
                    ->setBody(
                        $this->renderView('FerusEventBundle:Previs:mail.txt.twig', array(
                            'previs' => $previs,
                            'user' => $user
                        ))
                    )
                    ->attach(\Swift_Attachment::fromPath($path));
                ;

                $this->get('mailer')->send($message);
            }
        }

        unlink($path);

        $historical = new Historical();
        $historical->setAuthor($this->getUser());
        $historical->setEntity("Prévis");
        $historical->setAction('Envoi du PDF par mail pour "'.$previs->getName().'" à tout le monde.');
        $this->em->persist($historical);

        $this->em->flush();

        $this->get('session')->getFlashBag()->add(
            'notice',
            'E-mail envoyé avec succès à tous les réservistes.'
        );

        return $this->redirect($this->generateUrl('ferus_previs_calcul', array('id' => $previs->getId())));
    }
} 