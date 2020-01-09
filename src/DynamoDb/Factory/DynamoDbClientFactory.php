<?php

namespace Guillermoandrae\DynamoDb\Factory;

use Aws\DynamoDb\DynamoDbClient;

final class DynamoDbClientFactory
{
    /**
     * @var array The default client options.
     */
    private static $defaultOptions = [
        'region' => 'us-west-2',
        'version' => 'latest',
        'endpoint' => 'http://localhost:8000',
        'credentials' => [
            'key' => 'not-a-real-key',
            'secret' => 'not-a-real-secret',
        ]
    ];

    /**
     * Returns a DynamoDb client.
     *
     * @param array|null $options OPTIONAL The client options.
     * @return DynamoDbClient The DynamoDb client.
     */
    public static function factory(?array $options = []): DynamoDbClient
    {
        if (empty($options)) {
            $options = self::$defaultOptions;
        }
        return new DynamoDbClient($options);
    }
}
