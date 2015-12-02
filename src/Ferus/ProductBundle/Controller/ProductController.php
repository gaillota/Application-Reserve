<?php

namespace Ferus\ProductBundle\Controller;

use Ferus\ProductBundle\Entity\Category;
use Ferus\ProductBundle\Entity\Historical;
use Ferus\ProductBundle\Entity\Product;
use Ferus\ProductBundle\Form\ListProductAddType;
use Ferus\ProductBundle\Form\ProductAddType;
use Ferus\ProductBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class ProductController extends Controller
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
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction()
    {
        $search = $this->request->query->get('search', null);
        $search = isset($search) ? (!empty($search) ? $search : null) : null; //BRAAAH

        $selectedCategory = $this->request->query->get('category', null);

        $params = array(
            'search' => $search,
            'selectedCategory' => $selectedCategory
        );

        if (null !== $search) {
            $listProducts = $this->em->getRepository('FerusProductBundle:Product')->findSearch($search);
            $params['listProducts'] = $listProducts;
        } elseif (null !== $selectedCategory && $selectedCategory != 0) {
            $category = $this->em->getRepository('FerusProductBundle:Category')->myFindByCategory($selectedCategory);
            $params['category'] = $category;
        } else {
            $listCategories = $this->em->getRepository('FerusProductBundle:Category')->myFindAll();
            $params['listCategories'] = $listCategories;
        }

        $categories = $this->em->getRepository('FerusProductBundle:Category')->findAllArray();
        $params['categories'] = $categories;

        return $params;
    }

    /**
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        $product = new Product;

        // Set name if defined
        $product->setName($this->request->query->get('name', null));

        // Set category if defined
        if($this->request->query->has('category_id'))
            $product->setCategory(
                $this->em->getRepository('FerusProductBundle:Category')->findOneById(
                    $this->request->query->get('category_id')
                )
            );

        $form = $this->createForm(new ProductType(true), $product);

        if($this->request->isMethod('post')){
            $form->handleRequest($this->request);

            if($form->isValid()){
                $this->em->persist($product);

                $historical = new Historical();
                $historical->setAuthor($this->getUser());
                $historical->setEntity("Produit");
                $historical->setAction('Nouveau produit : "'.$product->getName().'".');
                $this->em->persist($historical);

                $this->em->flush();

                $clickedButton = $form->getClickedButton();

                if ($clickedButton->getName() == 'saveAndAdd')
                    return $this->redirectToRoute('ferus_product_new');

                return $this->redirectToRoute('ferus_product');
            }
        }

        return array(
            'form' => $form->createView(),
            'category' => $this->request->query->get('category_id', null),
        );
    }

    /**
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Product $product)
    {
        $form = $this->createForm(new ProductType, $product);

        if($this->request->isMethod('post')){
            $form->handleRequest($this->request);

            if($form->isValid()){
                $this->em->persist($product);

                $historical = new Historical();
                $historical->setAuthor($this->getUser());
                $historical->setEntity("Produit");
                $historical->setAction('Modification de "'.$product->getName().'".');

                $this->em->flush();

                return $this->redirect($this->generateUrl('ferus_product'));
            }
        }

        return array(
            'product' => $product,
            'form' => $form->createView(),
        );
    }

    /**
     * @param Product $product
     * @return JsonResponse
     * @Secure(roles="ROLE_USER")
     */
    public function removeAction(Product $product)
    {
        $this->em->remove($product);

        $historical = new Historical();
        $historical->setAuthor($this->getUser());
        $historical->setEntity("Produit");
        $historical->setAction('Suppression de "'.$product->getName().'".');
        $this->em->persist($historical);

        $this->em->flush();

        return new JsonResponse(array("ok"));
    }

    /**
     * @param Product $product
     * @return JsonResponse
     * @Secure(roles="ROLE_USER")
     */
    public function changeQuantityAction(Product $product)
    {
        $newValue = $this->request->query->get('quantity', null);
        if (null === $newValue) {
            return new JsonResponse(array(
                'success' => 0,
                'error' => 'Veuillez spécifier une valeur'
            ));
        }
        $formerValue = $product->getQuantity();

        $product->setQuantity($newValue);

        $historical = new Historical();
        $historical->setAuthor($this->getUser());
        $historical->setEntity("Produit");
        $change = $newValue - $formerValue;
        if ($change < 0) {
            $action = 'Retrait de '.(-1 * $change).' '.$product->getName().' dans la réserve.';
        } elseif ($change > 0) {
            $action = 'Ajout de '.$change.' '.$product->getName().' dans la réserve.';
        } else {
            return new JsonResponse(array(
                'success' => 0,
                'error' => 'Problème de script JS'
            ));
        }
        $historical->setAction($action);
        $this->em->persist($historical);

        $this->em->flush();

        return new JsonResponse(array(
            'success' => 1
        ));
    }

    /**
     * @return array
     * @Template
     * @Secure(roles="ROLE_USER")
     */
    public function addAction()
    {
        $previs_id = $this->request->query->get('previs', null);
        $previs_id = isset($previs_id) ? (!empty($previs_id) ? $previs_id : null) : null; //BRAAAH
        $previs = null;
        $listProducts = null;

        if (null !== $previs_id) {
            $previs = $this->em->getRepository('FerusProductBundle:Previs')->find($previs_id);
            $listProducts = $this->em->getRepository('FerusProductBundle:Product')->findAll();
        }

        $form = $this->createForm(new ListProductAddType);

        $form->handleRequest($this->request);

        if ($this->request->isMethod('POST') && $form->isValid()) {
            $data = $form->getData();

            $products = $data['list_products'];
            foreach ($products as $productForm) {
                $product = $productForm['product'];
                $product->addQuantity($productForm['quantity']);
                $this->em->persist($product);
            }

            $historical = new Historical();
            $historical->setAuthor($this->getUser());
            $historical->setEntity("Produit");
            $historical->setAction('Retour de Métro.');
            $this->em->persist($historical);

            $this->em->flush();

            return $this->redirectToRoute('ferus_product');
        }

        return array(
            'form' => $form->createView(),
            'previs' => $previs,
            'listProducts' => $listProducts,
        );
    }
}
