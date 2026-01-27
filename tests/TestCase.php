<?php

namespace Cray\Laravel\Tests;

use Cray\Laravel\CrayServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            CrayServiceProvider::class,
        ];
    }
    
    protected function getPackageAliases($app)
    {
        return [
            'Cray' => \Cray\Laravel\Facades\Cray::class,
        ];
    }
}
