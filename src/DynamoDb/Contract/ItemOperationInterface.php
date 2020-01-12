<?php

namespace Guillermoandrae\DynamoDb\Contract;

/**
 * Interface for item operations.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
interface ItemOperationInterface extends OperationInterface
{
    /**
     * Registers the operation's primary key with this object.
     *
     * @param array $primaryKey The primary key values to be used when retrieving items.
     * @return ItemOperationInterface This object.
     */
    public function setPrimaryKey(array $primaryKey): ItemOperationInterface;
}
