<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\Exception\DynamoDbException;
use Guillermoandrae\DynamoDb\Contract\AbstractOperation;
use Guillermoandrae\DynamoDb\Factory\ExceptionFactory;

/**
 * DeleteTable operation.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#deletetable
 */
final class DeleteTableOperation extends AbstractOperation
{
    public function execute(): bool
    {
        try {
            $this->client->deleteTable($this->toArray());
            //$this->client->waitUntil('TableExists', ['TableName' => $this->tableName]);
            return true;
        } catch (DynamoDbException $ex) {
            throw ExceptionFactory::factory($ex);
        }
    }
}
