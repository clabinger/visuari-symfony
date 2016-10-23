<?php
// src/MyFOS/UserBundle/MyFOSUserBundle.php

namespace MyFOS\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MyFOSUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}