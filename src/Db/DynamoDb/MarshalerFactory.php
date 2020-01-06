<?php

namespace Guillermoandrae\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;

final class MarshalerFactory
{
    /**
     * Returns a marshaler object.
     *
     * @return Marshaler The marshaler.
     */
    public static function factory()
    {
        return new Marshaler();
    }
}
