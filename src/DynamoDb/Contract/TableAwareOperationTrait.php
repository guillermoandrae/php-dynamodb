<?php

namespace Guillermoandrae\DynamoDb\Contract;

/**
 * Trait for table aware operation classes.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
trait TableAwareOperationTrait
{
    /**
     * @var string The table name.
     */
    protected string $tableName = '';

    /**
     * Registers the table name with this object.
     *
     * @param string $tableName The table name.
     * @return static This object.
     */
    final public function setTableName(string $tableName): static
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'TableName' => $this->tableName,
        ];
    }
}
