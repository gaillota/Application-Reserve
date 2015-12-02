<?php

namespace Ferus\ProductBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ferus\ProductBundle\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PrevisType extends AbstractType
{
    /**
     * @var Event $event
     */
    private $event;

//    /**
//     * @var boolean
//     */
//    private $hasEvent;

    /**
     * Set the hasEvent boolean
     *
     * @param null $eventFlag
     */
    public function __construct($event = null)
    {
        $this->event = $event;
//        $this->hasEvent = $eventFlag;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Nom'
            ))
//        ;
//        if (null === $this->hasEvent  || !$this->hasEvent) {
//            $builder
            ->add('event', 'entity', array(
                'class' => 'FerusProductBundle:Event',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Soirée',
                'empty_value' => 'Aucun événement associé',
                'required' => false,
                'query_builder' => function (EntityRepository $repo) {
                    $qb = $repo->createQueryBuilder('e');

                    $qb
                        ->leftJoin('e.previs', 'p')
                        ->where($qb->expr()->isNull('p.event'))
                    ;
                    if ($this->event instanceof Event) {
                        $qb
                            ->orWhere('e.id = :id')
                            ->setParameter('id', $this->event->getId())
                        ;
                    }

                    return $qb;
                }
            ))
//        ;
//        }
//        $builder
            ->add('previs_products', 'collection', array(
                'type' => new PrevisProductType,
                'label' => ' ',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                )
            )
            ->add('save', 'submit', array(
                'label' => 'Enregistrer',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ferus\ProductBundle\Entity\Previs'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_productbundle_previs';
    }
}
