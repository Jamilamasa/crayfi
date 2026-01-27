<?php

namespace Cray\Laravel\Tests\Feature;

use Cray\Laravel\Tests\TestCase;
use Cray\Laravel\Facades\Cray;
use Illuminate\Support\Facades\Http;

class ClientTest extends TestCase
{
    public function test_requests_use_correct_base_url_sandbox()
    {
        config(['cray.env' => 'sandbox']);
        config(['cray.base_url' => null]);
        config(['cray.api_key' => 'test_key']);

        Http::fake([
            'dev-gateman.v3.connectramp.com/*' => Http::response(['success' => true], 200),
        ]);

        Cray::wallets()->balances();

        Http::assertSent(function ($request) {
            return str_starts_with($request->url(), 'https://dev-gateman.v3.connectramp.com');
        });
    }

    public function test_requests_use_correct_base_url_live()
    {
        config(['cray.env' => 'live']);
        config(['cray.base_url' => null]);
        config(['cray.api_key' => 'test_key']);

        Http::fake([
            'pay.connectramp.com/*' => Http::response(['success' => true], 200),
        ]);

        Cray::wallets()->balances();

        Http::assertSent(function ($request) {
            return str_starts_with($request->url(), 'https://pay.connectramp.com');
        });
    }

    public function test_requests_use_custom_base_url()
    {
        config(['cray.env' => 'live']); // Should be ignored
        config(['cray.base_url' => 'https://custom-proxy.com']);
        config(['cray.api_key' => 'test_key']);

        Http::fake([
            'custom-proxy.com/*' => Http::response(['success' => true], 200),
        ]);

        Cray::wallets()->balances();

        Http::assertSent(function ($request) {
            return str_starts_with($request->url(), 'https://custom-proxy.com');
        });
    }

    public function test_api_key_is_injected()
    {
        config(['cray.api_key' => 'secret_token']);
        config(['cray.base_url' => 'https://test.com']);

        Http::fake([
            '*' => Http::response(['success' => true], 200)
        ]);

        Cray::wallets()->balances();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization', 'Bearer secret_token');
        });
    }
}
