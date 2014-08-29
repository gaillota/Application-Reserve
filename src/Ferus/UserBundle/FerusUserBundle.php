<?php

namespace Ferus\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FerusUserBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
