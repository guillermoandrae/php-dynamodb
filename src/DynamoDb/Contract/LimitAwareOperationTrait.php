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
     * @var int The result offset.
     */
    protected $offset = 0;

    /**
     * @var int|null The result limit.
     */
    protected $limit;

    /**
     * Registers the result offset with this object.
     *
     * @param integer $offset The result offset.
     * @return mixed An implementation of this trait.
     */
    final public function setOffset(int $offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Registers the result limit with this object.
     *
     * @param integer|null $limit The result limit.
     * @return mixed An implementation of this trait.
     */
    final public function setLimit(?int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'Limit' => $this->offset + $this->limit
        ];
    }
}
