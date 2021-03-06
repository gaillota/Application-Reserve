<?php

namespace Ferus\ProductBundle\Controller;

use Ferus\ProductBundle\Entity\Category;
use Ferus\AdminBundle\Entity\Historical;
use Ferus\ProductBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class CategoryController extends Controller
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
        return array(
            'listCategories' => $this->em->getRepository('FerusProductBundle:Category')->findBy(array(), array(
                'id' => 'ASC'
            )),
        );
    }

    /**
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template
     */
    public function newAction()
    {
        $category = new Category;
        $form = $this->createForm(new CategoryType, $category);

        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $this->em->persist($category);

            $historical = new Historical();
            $historical->setAuthor($this->getUser());
            $historical->setEntity('Categorie');
            $historical->setAction('Création de "'.$category->getName().'".');
            $this->em->persist($historical);

            $this->em->flush();

            return $this->redirectToRoute('ferus_category');
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Category $category
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template
     */
    public function editAction(Category $category)
    {
        $form = $this->createForm(new CategoryType, $category);

        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $this->em->persist($category);

            $historical = new Historical();
            $historical->setAuthor($this->getUser());
            $historical->setEntity('Catégorie');
            $historical->setAction('Modification de "'.$category->getName().'".');
            $this->em->persist($historical);

            $this->em->flush();

            return $this->redirectToRoute('ferus_category');
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @param Category $category
     * @return array
     * @Template
     */
    public function removeAction(Category $category)
    {
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $this->em->remove($category);

            $historical = new Historical();
            $historical->setAuthor($this->getUser());
            $historical->setEntity('Catégorie');
            $historical->setAction('Suppression de "'.$category->getName().'".');
            $this->em->persist($historical);

            $this->em->flush();

            $this->addFlash('success', 'Catégorie supprimée avec succès.');

            return $this->redirectToRoute('ferus_category');
        }

        return array(
            'form' => $form->createView(),
            'category' => $category,
        );
    }
}
