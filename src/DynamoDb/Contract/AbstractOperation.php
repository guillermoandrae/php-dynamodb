<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

/**
 * Abstract for operations.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
abstract class AbstractOperation implements OperationInterface
{
    use DynamoDbClientAwareTrait;

    /**
     * @var string The table name.
     */
    protected $tableName;

    /**
     * Registers the DynamoDb client, Marshaler, and table name with this object.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler, string $tableName)
    {
        $this->setClient($client);
        $this->setMarshaler($marshaler);
        $this->setTableName($tableName);
    }

    final public function setTableName(string $tableName): OperationInterface
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'TableName' => $this->tableName,
        ];
    }
}
