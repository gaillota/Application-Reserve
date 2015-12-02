<?php

namespace Ferus\ProductBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductAddType extends AbstractType
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
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'label' => ' ',
                'required' => true
            ))
            ->add('quantity', 'integer', array(
                'label' => ' '
            ))
            ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_productbundle_product_add';
    }
} 