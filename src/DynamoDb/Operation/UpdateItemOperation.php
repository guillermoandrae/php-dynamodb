<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use ErrorException;
use Guillermoandrae\DynamoDb\Contract\AbstractItemOperation;
use Guillermoandrae\DynamoDb\Factory\ExceptionFactory;

class UpdateItemOperation extends AbstractItemOperation
{
    /**
     * @var string The name of the update expression field.
     */
    protected $expressionFieldName = 'UpdateExpression';

    /**
     * Registers the DynamoDb client, Marshaler, table name, and item data with this object.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array $primaryKey The primary key of the item to update.
     * @param array $updateData The update data.
     * @throws ErrorException Thrown when an invalid operator is provided.
     */
    public function __construct(
        DynamoDbClient $client,
        Marshaler $marshaler,
        string $tableName,
        array $primaryKey,
        array $updateData
    ) {
        parent::__construct($client, $marshaler, $tableName, $primaryKey);
        $this->setExpression($updateData);
    }
    
    public function execute(): bool
    {
        try {
            $this->getClient()->updateItem($this->toArray());
            return true;
        } catch (DynamoDbException $ex) {
            throw ExceptionFactory::factory($ex);
        }
    }

    public function toArray(): array
    {
        $operation = parent::toArray();
        if (!empty($this->expression)) {
            $operation[$this->expressionFieldName] = 'SET ' . $operation['UpdateExpression'];
        }
        return $operation;
    }
}
