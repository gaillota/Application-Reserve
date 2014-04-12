<?php

namespace Ferus\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
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
            ))
            ->add('name', 'text', array(
                'label' => 'Nom'
            ))
            ->add('unit', 'text', array(
                'label' => 'Unité de mesure'
            ))
            ->add('price', 'text', array(
                'label' => 'Prix HT'
            ))
            ->add('taxes', 'text', array(
                'label' => '% TVA'
            ))
        ;
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
