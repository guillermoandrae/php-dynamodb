<?php

namespace GuillermoandraeTest\Fisher;

use Aws\DynamoDb\DynamoDbClient;

final class LocalDynamoDbClient
{
    /**
     * @var DynamoDbClient The DynamoDB client.
     */
    private static $instance;
    
    /**
     * Returns an instance of the DynamoDB client.
     *
     * @return DynamoDbClient
     */
    public static function get(): DynamoDbClient
    {
        if (!self::$instance) {
            self::$instance = new DynamoDbClient([
                'region' => 'us-west-2',
                'version'  => 'latest',
                'endpoint' => 'http://localhost:8000',
                'credentials' => [
                    'key' => 'not-a-real-key',
                    'secret' => 'not-a-real-secret',
                ],
            ]);
        }
        return self::$instance;
    }
}
