<?php

namespace Ferus\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'label' => 'Nom d\'utilisateur'
            ))
            ->add('email', 'email', array(
                'label' => 'Adresse mail'
            ))
            ->add('firstName', 'text', array(
                'label' => 'Prénom'
            ))
            ->add('lastName', 'text', array(
                'label' => 'Nom'
            ))
            ->add('roles', 'choice', array(
                'label' => 'Droit(s)',
                'multiple' => true,
                'expanded' => true,
                'choices' => array(
                    'ROLE_ADMIN' => 'Administrateur',
                )
            ))
            ->add('save', 'submit', array(
                'label' => 'Sauvegarder'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ferus\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_adminbundle_user';
    }
}
