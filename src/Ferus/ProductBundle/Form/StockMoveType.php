<?php

namespace Ferus\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StockMoveType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', 'entity', array(
                'class' => 'FerusProductBundle:Place',
                'empty_value' => '- Source -',
            ))
            ->add('to', 'entity', array(
                'class' => 'FerusProductBundle:Place',
                'empty_value' => '- Destination -',
            ))
            ->add('product', 'entity', array(
                'class' => 'FerusProductBundle:Product',
                'empty_value' => '- Produit -',
            ))
            ->add('quantity', 'integer', array(
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
            'data_class' => 'Ferus\ProductBundle\Model\StockMove'
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
