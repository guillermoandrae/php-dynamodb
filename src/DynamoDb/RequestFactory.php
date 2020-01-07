<?php

namespace Guillermoandrae\DynamoDb;

use Aws\DynamoDb\Marshaler;
use ICanBoogie\Inflector;
use ReflectionClass;
use ReflectionException;

final class RequestFactory
{
    /**
     * @var Marshaler The Marshaler.
     */
    private static $marshaler;
    
    /**
     * Creates and returns the desired request.
     *
     * @param string $type The request type.
     * @param mixed $options,... OPTIONAL The request options.
     * @return RequestInterface The request.
     * @throws Exception Thrown when an error occurs in creating the request.
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
            throw new Exception(
                sprintf('The %s request does not exist.', $type)
            );
        }
    }

    /**
     * Registers the Marshaler with this class.
     *
     * @param Marshaler $marshaler The Marshaler.
     * @return void
     */
    public static function setMarshaler(Marshaler $marshaler): void
    {
        self::$marshaler = $marshaler;
    }

    /**
     * Returns the Marshaler.
     *
     * @return Marshaler The Marshaler.
     */
    public static function getMarshaler(): Marshaler
    {
        return self::$marshaler;
    }
}
