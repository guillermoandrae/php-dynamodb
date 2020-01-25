<?php

namespace Guillermoandrae\DynamoDb\Helper;

/**
 * Inflector class.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
final class Inflector
{
    /**
     * Returns a camel-cased string.
     *
     * @param string $input The input string.
     * @return string The camel-cased string.
     */
    public static function camelize(string $input): string
    {
        $output = '';
        $segments = explode('-', $input);
        foreach ($segments as $segment) {
            $output .= ucfirst(strtolower($segment));
        }
        return $output;
    }
}
