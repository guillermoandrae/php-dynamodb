<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

abstract class AbstractOperation extends AbstractDynamoDbClientAware implements OperationInterface
{
    /**
     * Registers the DynamoDb client and Marshaler with this object.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler)
    {
        $this->setClient($client);
        $this->setMarshaler($marshaler);
    }
}
