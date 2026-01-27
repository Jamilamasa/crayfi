<?php

namespace Cray\Laravel\Modules\Cards;

use Cray\Laravel\Http\CrayClient;

class CardClient
{
    protected CrayClient $client;

    public function __construct(CrayClient $client)
    {
        $this->client = $client;
    }

    /**
     * Initiate Card Transaction
     * 
     * @param array $data
     * @return array
     */
    public function initiate(array $data): array
    {
        return $this->client->post('/api/v2/initiate', $data);
    }

    /**
     * Process Payment
     * 
     * @param array $data
     * @return array
     */
    public function charge(array $data): array
    {
        return $this->client->post('/api/v2/charge', $data);
    }

    /**
     * Query Transaction
     * 
     * @param string $reference Customer reference or transaction ID
     * @return array
     */
    public function query(string $reference): array
    {
        return $this->client->get("/api/query/{$reference}");
    }
}
