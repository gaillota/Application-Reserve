<?php

namespace Ferus\EventBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ferus\EventBundle\Entity\Previs;
use Ferus\EventBundle\Form\BarType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType
{
    /**
     * @var Previs $previs
     */
    private $previs;

    public function __construct($previs = null)
    {
        $this->previs = $previs;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',   'text', array(
                'label' => 'Nom'
            ))
            ->add('date',   'datetime', array(
                'label' => 'Date'
            ))
            ->add('previs', 'entity', array(
                'class' => 'FerusEventBundle:Previs',
                'property'      => 'name',
                'multiple'      => false,
                'expanded'      => false,
                'label'         => 'Prévis',
                'empty_value'   => 'Aucun prévis',
                'required'      => false,
                'query_builder' => function(EntityRepository $repo) {
                    $qb = $repo->createQueryBuilder('p');

                    $qb
                        ->where($qb->expr()->isNull('p.event'))
                        ;
                        if ($this->previs instanceof Previs) {
                            $qb
                                ->orWhere('p.id = :id')
                                ->setParameter('id', $this->previs->getId())
                            ;
                        }

                    return $qb;
                }
            ))
            ->add('bars', 'collection', array(
                'type' => new BarType(),
                'label' => 'Liste des bars',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ))
            ->add('save',   'submit', array(
                'label' => 'Enregistrer'
            ))
            ->add('saveAndAdd', 'submit', array(
                'label' => 'Enregistrer + prévis'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ferus\EventBundle\Entity\Event'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_eventbundle_event';
    }
}
