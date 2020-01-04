<?php

namespace Guillermoandrae\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;

abstract class AbstractTableAwareRequest extends AbstractRequest
{
    /**
     * @var string The table name.
     */
    protected $tableName;

    /**
     * Registers the JSON Marshaler and table name with this object.
     *
     * @param Marshaler $marshaler The JSON Marshaler.
     * @param string $tableName The table name.
     */
    public function __construct(Marshaler $marshaler, string $tableName)
    {
        parent::__construct($marshaler);
        $this->setTableName($tableName);
    }

    /**
     * Registers the table name.
     *
     * @param string $tableName The table name.
     * @return RequestInterface An implementation of this interface.
     */
    final public function setTableName(string $tableName): RequestInterface
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): array
    {
        return [
            'TableName' => $this->tableName,
        ];
    }
}
