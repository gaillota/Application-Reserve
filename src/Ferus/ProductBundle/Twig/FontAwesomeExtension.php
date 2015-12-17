<?php

namespace Ferus\ProductBundle\Twig;


class FontAwesomeExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('fa', array(
                $this,
                'fontAwesomeGenerator'
            ), array(
                'is_safe' => array(
                    'html'
                )
            ))
        );
    }

    public function fontAwesomeGenerator($string)
    {
        return '<i class="fa fa-' . $string . '"></i>';
    }

    public function getName()
    {
        return 'font_awesome_extension';
    }
} 