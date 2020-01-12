<?php

namespace Guillermoandrae\DynamoDb\Contract;

/**
 * Trait for limit aware operations.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
trait LimitAwareOperationTrait
{
    /**
     * @var int The result limit.
     */
    protected $limit;

    /**
     * Registers the result limit with this object.
     *
     * @param integer $limit The result limit.
     * @return mixed An implementation of this trait.
     */
    final public function setLimit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'Limit' => $this->limit
        ];
    }
}
