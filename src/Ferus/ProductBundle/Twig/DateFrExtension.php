<?php

namespace Ferus\ProductBundle\Twig;


class DateFrExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('date_fr', array(
                $this,
                'dateFrGenerator'
            ))
        );
    }

    public function dateFrGenerator(\DateTime $dateTime)
    {
        $days = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
        $months = array('N/C', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
        return $days[$dateTime->format("w")] . " " . $dateTime->format("j") . " " . $months[$dateTime->format("n")] . " " . $dateTime->format("Y");
    }

    public function getName()
    {
        return 'fate_fr_extension';
    }
} 