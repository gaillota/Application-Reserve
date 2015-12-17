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
                'attr' => array(
                    'autofocus' => true
                )
            ))
            ->add('unit', 'choice', array(
                'label' => 'Unité de mesure',
                'expanded' => true,
                'multiple' => false,
                'choices' => array(
                    '35mL' => '35mL',
                    '70mL' => '70mL',
                    '1L' => '1L',
                    '1,5L' => '1,5L',
                ),
                'choices_as_values' => true,
            ))
            ->add('price', 'number', array(
                'label' => 'Prix HT',
                'precision' => 2,
            ))
            ->add('taxes', 'choice', array(
                'label' => '% TVA',
                'expanded' => true,
                'multiple' => false,
                'choices' => array(
                    '20%' => '20',
                    '10%' => '10',
                    '5,5%' => '5.5'
                ),
                'choices_as_values' => true,
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
