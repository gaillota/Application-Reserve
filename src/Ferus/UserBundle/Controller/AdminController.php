<?php


namespace Ferus\UserBundle\Controller;

use Ferus\UserBundle\Entity\User;
use Ferus\UserBundle\Form\UserEditType;
use Ferus\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
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
     * @return array
     * @Template
     */
    public function indexAction()
    {
        $users = $this->em->getRepository('FerusUserBundle:User')->findAll();

        return array(
            'users' => $users,
        );
    }

    /**
     * @return array
     * @Template
     */
    public function addAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setEnabled(true);
        $form = $this->createForm(new UserType(true), $user);

        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $userManager->updateUser($user);
            $this->addFlash('success', 'Utilisateur crée avec succès.');

            return $this->redirect($this->generateUrl('ferus_user_admin'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @param User $user
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template
     */
    public function editAction(User $user)
    {
        $form = $this->createForm(new UserEditType(true), $user);

        if ($this->request->isMethod('POST')) {
            if ($form->handleRequest($this->request)->isValid()) {
                $userManager = $this->get('fos_user.user_manager');
                $userManager->updateUser($user);
                $this->addFlash('warning', 'Utilisateur mis à jour.');

                return $this->redirect($this->generateUrl('ferus_user_admin'));
            }
        }
        return array(
            'form' => $form->createView(),
            'user' => $user,
        );
    }

    /**
     * @param User $user
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template
     */
    public function removeAction(User $user)
    {
        $form = $this->createFormBuilder()->getForm();

        if ($this->request->isMethod('POST')) {
            if ($form->handleRequest($this->request)->isValid()) {
                $this->em->remove($user);
                $this->em->flush();
                $this->addFlash('danger', 'Utilisateur supprimé avec succès.');

                return $this->redirectToRoute('ferus_user_admin');
            }
        }

        return array(
            'form' => $form->createView(),
            'user' => $user,
        );
    }
}
