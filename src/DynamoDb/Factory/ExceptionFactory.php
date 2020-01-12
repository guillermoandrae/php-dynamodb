<?php

namespace Guillermoandrae\DynamoDb\Factory;

use Aws\DynamoDb\Exception\DynamoDbException;
use Guillermoandrae\DynamoDb\Exception\Exception;
use ReflectionClass;
use ReflectionException;

/**
 * Exception factory.
 *
 * @author Guillermo A. Fisher <me@guillermoandraefisher.com>
 */
final class ExceptionFactory
{
    /**
     * Returns an exception object.
     *
     * I'd rather deal with "physical" exceptions, so I created this class so that I can get actual objects.
     *
     * @param DynamoDbException $ex A DynamoDB exception.
     * @return Exception The exception object.
     */
    public static function factory(DynamoDbException $ex): Exception
    {
        try {
            $className = sprintf(
                '%s\%s',
                '\Guillermoandrae\DynamoDb\Exception',
                $ex->getAwsErrorCode()
            );
            $reflectionClass = new ReflectionClass($className);
            $args = [sprintf('An error has occurred => %s', $ex->getAwsErrorMessage()), $ex->getCode()];
            return $reflectionClass->newInstanceArgs($args);
        } catch (ReflectionException $ex) {
            return new Exception($ex->getMessage(), $ex->getCode());
        }
    }
}
