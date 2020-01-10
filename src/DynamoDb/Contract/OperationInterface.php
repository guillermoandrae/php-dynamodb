<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Guillermoandrae\Common\ArrayableInterface;

interface OperationInterface extends ArrayableInterface
{
    /**
     * Registers the table name with this object.
     *
     * @param string $tableName The table name.
     * @return OperationInterface This object.
     */
    public function setTableName(string $tableName): OperationInterface;

    /**
     * Executes the operation.
     *
     * @return mixed
     */
    public function execute();
}
