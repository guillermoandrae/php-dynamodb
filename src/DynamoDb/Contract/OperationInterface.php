<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Guillermoandrae\Common\ArrayableInterface;

interface OperationInterface extends DynamoDbClientAwareInterface, ArrayableInterface
{
    /**
     * Executes the operation.
     *
     * @return mixed
     */
    public function execute();
}
