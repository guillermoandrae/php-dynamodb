<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

/**
 * Abstract for item operations.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
abstract class AbstractItemOperation extends AbstractOperation implements ItemOperationInterface
{
    use ExpressionAttributeValueAwareOperationTrait, ReturnConsumedCapacityAwareOperationTrait {
        ExpressionAttributeValueAwareOperationTrait::toArray as expressionAwareTraitToArray;
        ReturnConsumedCapacityAwareOperationTrait::toArray as returnConsumedCapacityAwareTraitToArray;
    }

    /**
     * @var array The primary key.
     */
    protected $primaryKey;

    /**
     * Registers the DynamoDb client, Marshaler, table name, and primary key with this object.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array $primaryKey The primary key.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler, string $tableName, array $primaryKey)
    {
        parent::__construct($client, $marshaler, $tableName);
        $this->setPrimaryKey($primaryKey);
    }

    final public function setPrimaryKey(array $primaryKey): ItemOperationInterface
    {
        $this->primaryKey = $this->getMarshaler()->marshalItem($primaryKey);
        return $this;
    }

    public function toArray(): array
    {
        $operation = parent::toArray();
        $operation += $this->returnConsumedCapacityAwareTraitToArray();
        $operation += $this->expressionAwareTraitToArray();
        $operation['Key'] = $this->primaryKey;
        return $operation;
    }
}
