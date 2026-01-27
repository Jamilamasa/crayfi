<?php

namespace Cray\Laravel\Tests\Unit;

use Cray\Laravel\Tests\TestCase;
use Cray\Laravel\Http\CrayClient;
use Illuminate\Http\Client\Factory as HttpFactory;
use Cray\Laravel\Support\ResponseNormalizer;
use ReflectionClass;

class ConfigurationTest extends TestCase
{
    protected function getClient(array $config)
    {
        $http = new HttpFactory();
        $normalizer = new ResponseNormalizer();
        return new CrayClient($http, $normalizer, $config);
    }

    protected function getProtectedBaseUrl(CrayClient $client)
    {
        $reflection = new ReflectionClass($client);
        $method = $reflection->getMethod('getBaseUrl');
        $method->setAccessible(true);
        return $method->invoke($client);
    }

    public function test_default_environment_is_sandbox()
    {
        $config = ['env' => 'sandbox'];
        $client = $this->getClient($config);
        
        $this->assertEquals(
            'https://dev-gateman.v3.connectramp.com', 
            $this->getProtectedBaseUrl($client)
        );
    }

    public function test_live_environment()
    {
        $config = ['env' => 'live'];
        $client = $this->getClient($config);
        
        $this->assertEquals(
            'https://pay.connectramp.com', 
            $this->getProtectedBaseUrl($client)
        );
    }

    public function test_custom_base_url_overrides_environment()
    {
        $config = [
            'env' => 'live',
            'base_url' => 'https://custom-proxy.com'
        ];
        $client = $this->getClient($config);
        
        $this->assertEquals(
            'https://custom-proxy.com', 
            $this->getProtectedBaseUrl($client)
        );
    }

    public function test_custom_base_url_works_with_sandbox()
    {
        $config = [
            'env' => 'sandbox',
            'base_url' => 'https://sandbox-proxy.com'
        ];
        $client = $this->getClient($config);
        
        $this->assertEquals(
            'https://sandbox-proxy.com', 
            $this->getProtectedBaseUrl($client)
        );
    }
}
