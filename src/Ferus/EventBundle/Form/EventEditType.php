<?php

namespace Ferus\EventBundle\Form;

use Ferus\EventBundle\Entity\Previs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EventEditType extends AbstractType
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
            ->remove('saveAndAdd')
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_eventbundle_event_edit';
    }

    /**
     * @return EventType|null|string|\Symfony\Component\Form\FormTypeInterface
     */
    public function getParent()
    {
        return new EventType($this->previs);
    }
}
