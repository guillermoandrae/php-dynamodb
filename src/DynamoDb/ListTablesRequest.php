<?php

namespace Guillermoandrae\DynamoDb;

final class ListTablesRequest extends AbstractRequest
{
    use LimitAwareRequestTrait;

    /**
     * @var string The name of the last table in the current page of results.
     */
    protected $lastEvaluatedTableName;

    /**
     * Registers the name of table to be used as the last in the current page of results.
     *
     * @param string $lastEvaluatedTableName The name of the last table in the current page of results.
     * @return ListTablesRequest This object.
     */
    public function setLastEvaluatedTableName(string $lastEvaluatedTableName): ListTablesRequest
    {
        $this->lastEvaluatedTableName = $lastEvaluatedTableName;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): array
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
}
