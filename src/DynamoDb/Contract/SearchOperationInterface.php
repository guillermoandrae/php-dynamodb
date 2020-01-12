<?php

namespace Guillermoandrae\DynamoDb\Contract;

/**
 * Interface for search (query, scan) operations.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
interface SearchOperationInterface extends OperationInterface
{
    /**
     * Registers the read consistency model setting with this object.
     *
     * @param boolean $consistentRead Whether or not the read should be consistent.
     * @return SearchOperationInterface This object.
     */
    public function setConsistentRead(bool $consistentRead): SearchOperationInterface;

    /**
     * Registers the name of the secondary index to use.
     *
     * @param string $indexName The name of the secondary index to use.
     * @return SearchOperationInterface This object.
     */
    public function setIndexName(string $indexName): SearchOperationInterface;

    /**
     * Registers the attributes to retrieve from the specified table or index.
     *
     * @param string $projectionExpression The attributes to retrieve from the specified table or index.
     * @return SearchOperationInterface This object.
     */
    public function setProjectionExpression(string $projectionExpression): SearchOperationInterface;

    /**
     * Registers the attributes to be return in the result.
     *
     * @param string $select The attributes to be return in the result.
     * @return SearchOperationInterface This object.
     */
    public function setSelect(string $select): SearchOperationInterface;
}
