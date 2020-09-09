<?php

namespace App\Shared\Helpers;

class BaseHelper
{
    protected $container;
    protected $request;

    public function __construct($container, $request)
    {
        $this->container = $container;
        $this->request   = $request;
    }
}
