<?php

namespace Guillermoandrae\DynamoDb;

use Aws\DynamoDb\Marshaler;

final class PutItemRequest extends AbstractItemRequest
{
    /**
     * @var array $item The item data.
     */
    private $item;
    
    /**
     * Registers the Marshaler, table name, and item data with this object.
     *
     * @param Marshaler $marshaler The Marshaler.
     * @param string $tableName The table name.
     * @param array $item The item data.
     */
    public function __construct(Marshaler $marshaler, string $tableName, array $item)
    {
        parent::__construct($marshaler, $tableName);
        $this->setItem($item);
    }

    /**
     * Registers the item data with this object.
     *
     * @param array $item The item data.
     * @return PutItemRequest This object.
     */
    public function setItem(array $item): PutItemRequest
    {
        $this->item = $this->marshaler->marshalItem($item);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): array
    {
        return [
            'TableName' => $this->tableName,
            'Item' => $this->item,
        ];
    }
}
