<?php

namespace Cray\Laravel\Modules\Wallets;

use Cray\Laravel\Http\CrayClient;

class WalletClient
{
    protected CrayClient $client;

    public function __construct(CrayClient $client)
    {
        $this->client = $client;
    }

    /**
     * Get Wallet Balance
     * 
     * @return array
     */
    public function balances(): array
    {
        return $this->client->get('/api/balance');
    }

    /**
     * Get Subaccounts
     * 
     * @return array
     */
    public function subaccounts(): array
    {
        return $this->client->get('/api/get-subaccount');
    }
}
