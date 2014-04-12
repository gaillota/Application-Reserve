<?php

namespace Ferus\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StockType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', 'entity', array(
                'class' => 'FerusProductBundle:Product',
                'empty_value' => false,
            ))
            ->add('place', 'entity', array(
                'class' => 'FerusProductBundle:Place',
                'empty_value' => false,
            ))
            ->add('number', 'integer', array(
                'label' => 'Quantité',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ferus\ProductBundle\Entity\Stock'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_productbundle_stock';
    }
}
