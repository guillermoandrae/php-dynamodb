<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

interface DynamoDbClientAwareInterface
{
    /**
     * Registers the client.
     *
     * @param mixed $client The DynamoDb client.
     * @return DynamoDbClientAwareInterface An implementation of this interface.
     */
    public function setClient(DynamoDbClient $client): DynamoDbClientAwareInterface;

    /**
     * Returns the client.
     *
     * @return DynamoDbClient The client.
     */
    public function getClient(): DynamoDbClient;

    /**
     * Registers the Marshaler with this object.
     *
     * @param Marshaler $marshaler The Marshaler.
     * @return DynamoDbClientAwareInterface An implementation of this interface.
     */
    public function setMarshaler(Marshaler $marshaler): DynamoDbClientAwareInterface;

    /**
     * Returns the Marshaler.
     *
     * @return Marshaler The Marshaler.
     */
    public function getMarshaler(): Marshaler;
}
