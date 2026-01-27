<?php

namespace Cray\Laravel\Modules\Momo;

use Cray\Laravel\Http\CrayClient;

class MomoClient
{
    protected CrayClient $client;

    public function __construct(CrayClient $client)
    {
        $this->client = $client;
    }

    /**
     * Initiate Mobile Money Collection
     * 
     * @param array $data
     * @return array
     */
    public function initiate(array $data): array
    {
        return $this->client->post('/api/v2/momo/initiate', $data);
    }

    /**
     * Requery Transaction
     * 
     * @param string $reference Customer reference
     * @return array
     */
    public function requery(string $reference): array
    {
        return $this->client->get("/api/v2/momo/requery/{$reference}");
    }
}
