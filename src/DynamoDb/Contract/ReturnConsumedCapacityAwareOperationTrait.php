<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Guillermoandrae\DynamoDb\Constant\ReturnConsumedCapacityOptions;

/**
 * Trait for ReturnConsumedCapacity aware operations.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
trait ReturnConsumedCapacityAwareOperationTrait
{
    /**
     * @var string The level of detail about provisioned throughput consumption that is returned in the response.
     */
    protected $returnConsumedCapacity = ReturnConsumedCapacityOptions::NONE;

    /**
     * Registers the desired level of consumption detail to return.
     *
     * @param string $returnConsumedCapacity The level of consumption detail to return.
     * @return mixed This object.
     */
    final public function setReturnConsumedCapacity(string $returnConsumedCapacity)
    {
        $this->returnConsumedCapacity = $returnConsumedCapacity;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'ReturnConsumedCapacity' => $this->returnConsumedCapacity
        ];
    }
}
