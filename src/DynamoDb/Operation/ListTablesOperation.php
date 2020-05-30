<?php

namespace Guillermoandrae\DynamoDb\Operation;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Guillermoandrae\DynamoDb\Contract\AbstractTableOperation;
use Guillermoandrae\DynamoDb\Contract\PaginationAwareOperationTrait;

/**
 * ListTables operation.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 * @link https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#listtables
 */
final class ListTablesOperation extends AbstractTableOperation
{
    use PaginationAwareOperationTrait {
        PaginationAwareOperationTrait::toArray as paginationAwareTraitToArray;
    }

    /**
     * @var string The name of the last table in the current page of results.
     */
    protected $lastEvaluatedTableName;

    /**
     * Registers the DynamoDb client and Marshaler with this object.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @param Marshaler $marshaler The Marshaler.
     */
    public function __construct(DynamoDbClient $client, Marshaler $marshaler)
    {
        $this->setClient($client);
        $this->setMarshaler($marshaler);
    }

    /**
     * Registers the name of table to be used as the last in the current page of results.
     *
     * @param string $lastEvaluatedTableName The name of the last table in the current page of results.
     * @return ListTablesOperation This object.
     */
    public function setLastEvaluatedTableName(string $lastEvaluatedTableName): ListTablesOperation
    {
        $this->lastEvaluatedTableName = $lastEvaluatedTableName;
        return $this;
    }

    public function execute(): ?array
    {
        $tables = $this->client->listTables($this->toArray());
        return $tables['TableNames'];
    }

    public function toArray(): array
    {
        $operation = parent::toArray();
        unset($operation['TableName']);
        if ($this->lastEvaluatedTableName) {
            $operation['LastEvaluatedTableName'] = $this->lastEvaluatedTableName;
        }
        if ($this->limit) {
            $operation += $this->paginationAwareTraitToArray();
        }
        return $operation;
    }
}
