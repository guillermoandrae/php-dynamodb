<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

trait DynamoDbClientAwareTrait
{
    /**
     * @var DynamoDbClient The DynamoDb client.
     */
    protected $client;

    /**
     * @var Marshaler The Marshaler.
     */
    protected $marshaler;

    /**
     * Returns the client.
     *
     * @return DynamoDbClient The client.
     */
    public function getClient(): DynamoDbClient
    {
        return $this->client;
    }

    /**
     * Registers the client.
     *
     * @param mixed $client The client.
     * @return mixed This object.
     */
    public function setClient(DynamoDbClient $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Returns the Marshaler.
     *
     * @return Marshaler The Marshaler.
     */
    public function getMarshaler(): Marshaler
    {
        return $this->marshaler;
    }

    /**
     * Registers the Marshaler with this object.
     *
     * @param Marshaler $marshaler The Marshaler.
     * @return mixed This object.
     */
    public function setMarshaler(Marshaler $marshaler)
    {
        $this->marshaler = $marshaler;
        return $this;
    }
}
