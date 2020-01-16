<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Guillermoandrae\Common\ArrayableInterface;

/**
 * Interface for operations.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
interface OperationInterface extends ArrayableInterface
{
    /**
     * Executes the operation.
     *
     * @return mixed
     */
    public function execute();
}
