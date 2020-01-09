<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Guillermoandrae\Common\ArrayableInterface;

interface OperationInterface extends ArrayableInterface
{
    /**
     * Executes the operation.
     *
     * @return mixed
     */
    public function execute();
}
