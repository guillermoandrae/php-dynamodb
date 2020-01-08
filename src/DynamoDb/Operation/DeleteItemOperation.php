<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\Exception\DynamoDbException;
use Guillermoandrae\DynamoDb\Contract\AbstractKeyAwareOperation;
use Guillermoandrae\DynamoDb\Exception;

final class DeleteItemOperation extends AbstractKeyAwareOperation
{
    /**
     * {@inheritDoc}
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#deleteitem
     */
    public function execute(): bool
    {
        try {
            $this->client->deleteItem($this->toArray());
            return true;
        } catch (DynamoDbException $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
