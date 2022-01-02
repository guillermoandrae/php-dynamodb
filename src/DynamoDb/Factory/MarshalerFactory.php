<?php

namespace Guillermoandrae\DynamoDb\Factory;

use Aws\DynamoDb\Marshaler;

/**
 * Marshaler factory.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
final class MarshalerFactory
{
    /**
     * Returns a marshaler object.
     *
     * @return Marshaler The marshaler.
     */
    public static function factory(): Marshaler
    {
        return new Marshaler();
    }
}
