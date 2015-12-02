<?php

namespace Ferus\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserEditType extends AbstractType
{
    /**
     * @var boolean
     */
    private $isAdmin;


    public function __construct($adminFlag){
        $this->isAdmin = $adminFlag;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('plain_password')
            ->remove('save')
        ;
        if ($this->isAdmin) {
            $builder
                ->add('locked', 'checkbox', array(
                    'label' => 'Bannir ?',
                    'required' => false
                ))
            ;
        }
        $builder
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
        return new UserType($this->isAdmin);
    }
} 