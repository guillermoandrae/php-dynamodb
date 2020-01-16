<?php

namespace Guillermoandrae\DynamoDb\Contract;

trait TableAwareOperationTrait
{
    /**
     * @var string The table name.
     */
    protected $tableName;

    /**
     * Registers the table name with this object.
     *
     * @param string $tableName The table name.
     * @return mixed This object.
     */
    final public function setTableName(string $tableName)
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
