<?php


namespace Ferus\AdminBundle\Controller;

use Ferus\UserBundle\Entity\User;
use Ferus\AdminBundle\Form\UserEditType;
use Ferus\AdminBundle\Form\UserType;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class UserController extends Controller
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
        /** @var UserManager $userManager */
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();

        $form = $this->createForm(new UserType, $user);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            //Generate random password of 8 characters
            $password = bin2hex(openssl_random_pseudo_bytes(4));
            //Set random password to the user
            $user->setPlainPassword($password);
            $user->setEnabled(true);

            $userManager->updateUser($user, true);

            $message = \Swift_Message::newInstance()
                ->setSubject('[Réserve] Vos identifiants de connexion')
                ->setFrom(array('reserve@edu.esiee.fr' => 'Application Réserve - BDE ESIEE Paris'))
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'FerusAdminBundle:Email:addUser.txt.twig',
                        array(
                            'user' => $user,
                            'password' => $password,
                        )
                    )
                )
            ;

            $this->get('mailer')->send($message);

            $this->addFlash('success', 'Utilisateur crée avec succès.');

            return $this->redirectToRoute('ferus_admin_user');
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
        $form = $this->createForm(new UserEditType, $user);

        if ($this->request->isMethod('POST')) {
            if ($form->handleRequest($this->request)->isValid()) {
                $userManager = $this->get('fos_user.user_manager');
                $userManager->updateUser($user);
                $this->addFlash('warning', 'Utilisateur mis à jour.');

                return $this->redirectToRoute('ferus_admin_user');
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

                return $this->redirectToRoute('ferus_admin_user');
            }
        }

        return array(
            'form' => $form->createView(),
            'user' => $user,
        );
    }
}
