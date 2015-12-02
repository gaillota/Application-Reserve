<?php

namespace Ferus\ProductBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    /**
     * @var boolean
     */
    private $isAdding;


    public function __construct($addingFlag = null)
    {
        $this->isAdding = $addingFlag;
    }

        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', 'entity', array(
                'class' => 'FerusProductBundle:Category',
                'empty_value' => false,
                'label' => 'Catégorie',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.id', 'ASC');
                }
            ))
            ->add('name', 'text', array(
                'label' => 'Nom',
            ))
            ->add('unit', 'choice', array(
                'label' => 'Unité de mesure',
                'choices' => array(
                    '35mL' => '35mL',
                    '70mL' => '70mL',
                    '1L' => '1L',
                    '1,5L' => '1,5L'
                ),
                'preferred_choices' => array(
                    '70mL'
                ),
            ))
            ->add('price', 'number', array(
                'label' => 'Prix HT',
                'precision' => 2,
                'data' => '10.00'
            ))
            ->add('taxes', 'number', array(
                'label' => '% TVA',
                'precision' => 2,
                'data' => '20'
            ))
            ->add('quantity', 'integer', array(
                'label' => 'Quantité'
            ))
            ->add('save', 'submit', array(
                'label' => 'Enregistrer'
            ))
        ;
        if ($this->isAdding) {
            $builder
                ->add('saveAndAdd', 'submit', array(
                    'label' => 'Enregistrer et recommencer'
                ))
            ;
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ferus\ProductBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_productbundle_product';
    }
}
