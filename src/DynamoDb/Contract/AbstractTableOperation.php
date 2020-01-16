<?php

namespace Guillermoandrae\DynamoDb\Contract;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

/**
 * Abstract for table operations.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
abstract class AbstractTableOperation extends AbstractOperation implements TableOperationInterface
{
    use TableAwareOperationTrait {
        TableAwareOperationTrait::toArray as tableAwareTraitToArray;
    }

    /**
     * Registers the DynamoDb client, Marshaler, and the table name with this object.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler, string $tableName)
    {
        parent::__construct($client, $marshaler);
        $this->setTableName($tableName);
    }

    public function toArray(): array
    {
        return $this->tableAwareTraitToArray();
    }
}
