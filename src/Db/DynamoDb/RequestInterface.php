<?php

namespace Guillermoandrae\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;

interface RequestInterface
{
    /**
     * Registers the JSON Marshaler with this object.
     *
     * @param Marshaler $marshaler The JSON Marshaler.
     * @return RequestInterface An implementation of this interface.
     */
    public function setMarshaler(Marshaler $marshaler): RequestInterface;

    /**
     * Returns the query.
     *
     * @return array The query.
     */
    public function get(): array;
}
