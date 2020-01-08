<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

abstract class AbstractDynamoDbClientAware implements DynamoDbClientAwareInterface
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
     * Registers the client.
     *
     * @param mixed $client The client.
     * @return DynamoDbClientAwareInterface This object.
     */
    public function setClient(DynamoDbClient $client): DynamoDbClientAwareInterface
    {
        $this->client = $client;
        return $this;
    }

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
     * {@inheritDoc}
     */
    final public function setMarshaler(Marshaler $marshaler): DynamoDbClientAwareInterface
    {
        $this->marshaler = $marshaler;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    final public function getMarshaler(): Marshaler
    {
        return $this->marshaler;
    }
}
