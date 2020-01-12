<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\Exception\DynamoDbException;
use Guillermoandrae\DynamoDb\Contract\AbstractItemOperation;
use Guillermoandrae\DynamoDb\Factory\ExceptionFactory;

/**
 * DeleteItem operation.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#deleteitem
 */
final class DeleteItemOperation extends AbstractItemOperation
{
    public function execute(): bool
    {
        try {
            $this->client->deleteItem($this->toArray());
            return true;
        } catch (DynamoDbException $ex) {
            throw ExceptionFactory::factory($ex);
        }
    }
}
