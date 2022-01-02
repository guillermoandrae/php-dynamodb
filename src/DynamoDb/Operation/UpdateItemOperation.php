<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use ErrorException;
use Guillermoandrae\DynamoDb\Constant\Operators;
use Guillermoandrae\DynamoDb\Contract\AbstractItemOperation;
use Guillermoandrae\DynamoDb\Factory\ExceptionFactory;

/**
 * UpdateItem operation.
 *
 * Currently, only SET is supported.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#updateitem
 */
class UpdateItemOperation extends AbstractItemOperation
{
    /**
     * @var string The name of the update expression field.
     */
    protected string $expressionFieldName = 'UpdateExpression';

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
        Marshaler      $marshaler,
        string         $tableName,
        array          $primaryKey,
        array          $updateData
    ) {
        parent::__construct($client, $marshaler, $tableName, $primaryKey);
        $updateDataArray = [];
        foreach ($updateData as $key => $options) {
            if (!isset($options['operator'])) {
                $updateDataArray[$key] = [
                    'operator' => Operators::EQ,
                    'value' => $options
                ];
            } else {
                $updateDataArray[$key] = $options;
            }
        }
        $this->setExpression($updateDataArray);
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
