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
    protected int $offset = 0;

    /**
     * @var int|null The result limit.
     */
    protected ?int $limit = 0;

    /**
     * Registers the result offset with this object.
     *
     * @param integer $offset The result offset.
     * @return static An implementation of this trait.
     */
    final public function setOffset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Registers the result limit with this object.
     *
     * @param integer|null $limit The result limit.
     * @return static An implementation of this trait.
     */
    final public function setLimit(?int $limit): static
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
