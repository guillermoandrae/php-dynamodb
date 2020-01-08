<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\Exception\DynamoDbException;
use Guillermoandrae\DynamoDb\Contract\AbstractTableAwareOperation;
use Guillermoandrae\DynamoDb\Exception;

final class DescribeTableOperation extends AbstractTableAwareOperation
{
    /**
     * {@inheritDoc}
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#describetable
     */
    public function execute(): ?array
    {
        try {
            $result = $this->client->describeTable($this->toArray());
            return $result['Table'];
        } catch (DynamoDbException $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
