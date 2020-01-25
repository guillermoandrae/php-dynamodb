<?php

namespace Guillermoandrae\DynamoDb\Helper;

/**
 * Inflector class.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
final class Inflector
{
    public static function camelize(string $input): string
    {
        $output = $input;
        if (strstr($input, '-')) {
            $output = '';
            $segments = explode('-', $input);
            foreach ($segments as $segment) {
                $output .= strtoupper($segment);
            }
        }
        return $output;
    }
}
