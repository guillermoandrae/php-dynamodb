<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

/**
 * Trait for DynamoDb client aware classes.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
trait DynamoDbClientAwareTrait
{
    /**
     * @var DynamoDbClient The DynamoDb client.
     */
    protected DynamoDbClient $client;

    /**
     * @var Marshaler The Marshaler.
     */
    protected Marshaler $marshaler;

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
     * @param DynamoDbClient $client The client.
     * @return static This object.
     */
    public function setClient(DynamoDbClient $client): static
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
     * @return static This object.
     */
    public function setMarshaler(Marshaler $marshaler): static
    {
        $this->marshaler = $marshaler;
        return $this;
    }
}
