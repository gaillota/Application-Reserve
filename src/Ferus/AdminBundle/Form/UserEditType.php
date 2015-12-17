<?php

namespace Ferus\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserEditType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('plain_password')
            ->remove('save')
            ->add('enabled', 'checkbox', array(
                'label' => 'Activé',
                'required' => false
            ))
            ->add('locked', 'checkbox', array(
                'label' => 'Bannir ?',
                'required' => false
            ))
            ->add('save', 'submit', array(
                'label' => 'Sauvegarder'
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_userbundle_user_edit';
    }

    public function getParent()
    {
        return new UserType();
    }
} 