<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

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

    /**
     * Registers the table name.
     *
     * @param string $tableName The table name.
     * @return OperationInterface This object.
     */
    final public function setTableName(string $tableName): OperationInterface
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'TableName' => $this->tableName,
        ];
    }
}
