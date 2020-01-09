<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\Exception\DynamoDbException;
use Guillermoandrae\DynamoDb\Contract\AbstractOperation;
use Guillermoandrae\DynamoDb\Exception;

/**
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#deletetable
 */
final class DeleteTableOperation extends AbstractOperation
{
    /**
     * {@inheritDoc}
     */
    public function execute(): bool
    {
        try {
            $this->client->deleteTable($this->toArray());
            //$this->client->waitUntil('TableExists', ['TableName' => $this->tableName]);
            return true;
        } catch (DynamoDbException $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
