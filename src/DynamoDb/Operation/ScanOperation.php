<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\Exception\DynamoDbException;
use Guillermoandrae\Common\Collection;
use Guillermoandrae\Common\CollectionInterface;
use Guillermoandrae\DynamoDb\Contract\AbstractSearchOperation;
use Guillermoandrae\DynamoDb\Exception;

final class ScanOperation extends AbstractSearchOperation
{
    /**
     * {@inheritDoc}
     * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#scan
     */
    public function execute(): CollectionInterface
    {
        try {
            $results = $this->client->scan($this->toArray());
            $rows = [];
            foreach ($results['Items'] as $item) {
                $rows[] = $this->marshaler->unmarshalItem($item);
            }
            return Collection::make($rows);
        } catch (DynamoDbException $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
