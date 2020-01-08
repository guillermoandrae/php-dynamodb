<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Guillermoandrae\DynamoDb\Contract\AbstractOperation;
use Guillermoandrae\DynamoDb\Contract\LimitAwareOperationTrait;

/**
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#listtables
 */
final class ListTablesOperation extends AbstractOperation
{
    use LimitAwareOperationTrait;

    /**
     * @var string The name of the last table in the current page of results.
     */
    protected $lastEvaluatedTableName;

    /**
     * Registers the name of table to be used as the last in the current page of results.
     *
     * @param string $lastEvaluatedTableName The name of the last table in the current page of results.
     * @return ListTablesOperation This object.
     */
    public function setLastEvaluatedTableName(string $lastEvaluatedTableName): ListTablesOperation
    {
        $this->lastEvaluatedTableName = $lastEvaluatedTableName;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $query = [];
        if ($this->lastEvaluatedTableName) {
            $query['LastEvaluatedTableName'] = $this->lastEvaluatedTableName;
        }
        if ($this->limit) {
            $query['Limit'] = $this->limit;
        }
        return $query;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(): ?array
    {
        $tables = $this->client->listTables($this->toArray());
        return $tables['TableNames'];
    }
}
