<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\Exception\DynamoDbException;
use Guillermoandrae\DynamoDb\Contract\AbstractTableAwareOperation;
use Guillermoandrae\DynamoDb\Exception;
use InvalidArgumentException;

/**
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#deletetable
 */
final class DeleteTableOperation extends AbstractTableAwareOperation
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
        } catch (InvalidArgumentException $ex) {
            throw new Exception('Bad key schema: ' . $ex->getMessage());
        }
    }
}
