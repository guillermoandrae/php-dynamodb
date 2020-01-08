<?php

namespace Guillermoandrae\DynamoDb\Factory;

use Aws\DynamoDb\DynamoDbClient;

final class DynamoDbClientFactory
{
    private static $defaultOptions = [
        'region' => 'us-west-2',
        'version'  => 'latest',
        'endpoint' => 'http://localhost:8000',
        'credentials' => [
            'key' => 'not-a-real-key',
            'secret' => 'not-a-real-secret',
        ]
    ];

    public static function factory(?array $options = []): DynamoDbClient
    {
        if (empty($options)) {
            $options = static::$defaultOptions;
        }
        return new DynamoDbClient($options);
    }
}
