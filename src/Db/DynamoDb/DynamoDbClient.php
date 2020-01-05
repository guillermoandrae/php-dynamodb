<?php

namespace Guillermoandrae\Db\DynamoDb;

use Aws\DynamoDb\DynamoDbClient as AWSDynamoDbClient;

final class DynamoDbClient extends AWSDynamoDbClient
{
    /**
     * DynamoDbClient constructor.
     *
     * If no options are provided, uses local options.
     *
     * @param array $options OPTIONAL The client options.
     */
    public function __construct(array $options = [])
    {
        if (empty($options)) {
            $options = [
                'region' => 'us-west-2',
                'version'  => 'latest',
                'endpoint' => 'http://localhost:8000',
                'credentials' => [
                    'key' => 'not-a-real-key',
                    'secret' => 'not-a-real-secret',
                ],
            ];
        }
        parent::__construct($options);
    }
}
