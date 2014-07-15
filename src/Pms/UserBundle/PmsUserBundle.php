<?php

namespace Pms\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PmsUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
