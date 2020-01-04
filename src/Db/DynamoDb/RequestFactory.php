<?php

namespace Guillermoandrae\Db\DynamoDb;

use Aws\DynamoDb\Marshaler;
use Guillermoandrae\Db\DbException;
use ICanBoogie\Inflector;
use ReflectionClass;
use ReflectionException;

final class RequestFactory
{
    /**
     * @var Marshaler The JSON Marshaler.
     */
    private static $marshaler;
    
    /**
     * Creates and returns the desired request.
     *
     * @param string $type The request type.
     * @param mixed $options,... OPTIONAL The request options.
     * @return RequestInterface The request.
     * @throws DbException Thrown when an error occurs in creating the request.
     */
    public static function factory(string $type, ...$options): RequestInterface
    {
        try {
            $className = sprintf(
                '%s\%sRequest',
                __NAMESPACE__,
                Inflector::get()->camelize($type)
            );
            $reflectionClass = new ReflectionClass($className);
            $args = [self::getMarshaler()];
            foreach ($options as $option) {
                $args[] = $option;
            }
            $request = $reflectionClass->newInstanceArgs($args);
            return $request;
        } catch (ReflectionException $ex) {
            throw new DbException(
                sprintf('The %s request does not exist.', $type)
            );
        }
    }

    /**
     * Registers the Marshaler with this class.
     *
     * @param Marshaler $marshaler The JSON Marshaler.
     * @return void
     */
    public static function setMarshaler(Marshaler $marshaler): void
    {
        self::$marshaler = $marshaler;
    }

    /**
     * Returns the JSON Marshaler.
     *
     * @return Marshaler The JSON Marshaler.
     */
    public static function getMarshaler(): Marshaler
    {
        return self::$marshaler;
    }
}
