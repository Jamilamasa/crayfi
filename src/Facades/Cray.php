<?php

namespace Cray\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Cray extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cray';
    }
}
