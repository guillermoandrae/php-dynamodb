<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

abstract class AbstractItemOperation extends AbstractOperation implements ItemOperationInterface
{
    use FilterExpressionAwareOperationTrait, ReturnConsumedCapacityAwareOperationTrait {
        FilterExpressionAwareOperationTrait::toArray as filterExpressionAwareTraitToArray;
        ReturnConsumedCapacityAwareOperationTrait::toArray as returnConsumedCapacityAwareTraitToArray;
    }

    /**
     * @var array The primary key.
     */
    protected $primaryKey;

    /**
     * Registers the DynamoDb client, Marshaler, and table name with this object.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array $primaryKey The primary key.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler, string $tableName, array $primaryKey)
    {
        $this->setClient($client);
        $this->setMarshaler($marshaler);
        $this->setTableName($tableName);
        $this->setPrimaryKey($primaryKey);
    }

    /**
     * Registers the operation's primary key with this object.
     *
     * @param array $primaryKey The primary key values to be used when retrieving items.
     * @return mixed An implementation of this abstract.
     */
    public function setPrimaryKey(array $primaryKey)
    {
        $this->primaryKey = $this->getMarshaler()->marshalItem($primaryKey);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $operation = parent::toArray();
        $operation += $this->returnConsumedCapacityAwareTraitToArray();
        $operation += $this->filterExpressionAwareTraitToArray();
        $operation['Key'] = $this->primaryKey;
        return $operation;
    }
}
