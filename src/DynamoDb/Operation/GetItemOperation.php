<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\Exception\DynamoDbException;
use Guillermoandrae\DynamoDb\Contract\AbstractKeyAwareOperation;
use Guillermoandrae\DynamoDb\Exception;

final class GetItemOperation extends AbstractKeyAwareOperation
{
    /**
     * {@inheritDoc}
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#getitem
     */
    public function execute(): array
    {
        try {
            $results = $this->client->getItem($this->toArray());
            $item = [];
            if (is_array($results['Item'])) {
                $item = $this->marshaler->unmarshalItem($results['Item']);
            }
            return $item;
        } catch (DynamoDbException $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
