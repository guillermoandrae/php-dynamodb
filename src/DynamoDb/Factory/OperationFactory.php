<?php

namespace Guillermoandrae\DynamoDb\Factory;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Guillermoandrae\DynamoDb\Contract\OperationInterface;
use Guillermoandrae\DynamoDb\Exception;
use ICanBoogie\Inflector;
use ReflectionClass;
use ReflectionException;

final class OperationFactory
{
    /**
     * @var DynamoDbClient The DynamoDb client.
     */
    private static $client;

    /**
     * @var Marshaler The Marshaler.
     */
    private static $marshaler;
    
    /**
     * Creates and returns the desired request.
     *
     * @param string $type The request type.
     * @param mixed $options,... OPTIONAL The request options.
     * @return OperationInterface The request.
     * @throws Exception Thrown when an error occurs in creating the request.
     */
    public static function factory(string $type, ...$options): OperationInterface
    {
        try {
            $className = sprintf(
                '%s\%sOperation',
                '\Guillermoandrae\DynamoDb\Operation',
                Inflector::get()->camelize($type)
            );
            $reflectionClass = new ReflectionClass($className);
            $args = [self::getClient(), self::getMarshaler()];
            foreach ($options as $option) {
                $args[] = $option;
            }
            return $reflectionClass->newInstanceArgs($args);
        } catch (ReflectionException $ex) {
            throw new Exception(
                sprintf('The %s operation does not exist.', $type)
            );
        }
    }

    /**
     * Registers the DynamoDb client with this class.
     *
     * @param DynamoDbClient $client The DynamoDb client.
     * @return void
     */
    public static function setClient(DynamoDbClient $client): void
    {
        self::$client = $client;
    }

    /**
     * Returns the DynamoDb client.
     *
     * @return DynamoDbClient The DynamoDb client.
     */
    public static function getClient(): DynamoDbClient
    {
        return self::$client;
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
