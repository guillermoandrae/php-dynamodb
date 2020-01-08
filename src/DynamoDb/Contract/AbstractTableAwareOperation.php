<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

abstract class AbstractTableAwareOperation extends AbstractOperation
{
    /**
     * @var string The table name.
     */
    protected $tableName;

    /**
     * Registers the Marshaler and table name with this object.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler, string $tableName)
    {
        parent::__construct($client, $marshaler);
        $this->setTableName($tableName);
    }

    /**
     * Registers the table name.
     *
     * @param string $tableName The table name.
     * @return OperationInterface An implementation of this interface.
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
