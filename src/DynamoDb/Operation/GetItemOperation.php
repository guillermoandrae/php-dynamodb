<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\Exception\DynamoDbException;
use Guillermoandrae\DynamoDb\Contract\AbstractItemOperation;
use Guillermoandrae\DynamoDb\Exception;

/**
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#getitem
 */
final class GetItemOperation extends AbstractItemOperation
{
    /**
     * {@inheritDoc}
     */
    public function execute(): array
    {
        try {
            $item = [];
            $results = $this->client->getItem($this->toArray());
            if (!empty($results['Item']) && is_array($results['Item'])) {
                $item = $this->getMarshaler()->unmarshalItem($results['Item']);
            }
            return $item;
        } catch (DynamoDbException $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
