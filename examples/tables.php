<?php declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;
use Guillermoandrae\DynamoDb\DynamoDbAdapter;

// create a new adapter
$adapter = new DynamoDbAdapter();

try {
    $tableName = 'myTable';

    // create a table
    $keys = [
        'name' => [AttributeTypes::STRING, KeyTypes::HASH],
        'date' => [AttributeTypes::NUMBER, KeyTypes::RANGE],
    ];

    // check for the existence of a table
    if ($adapter->useTable($tableName)->tableExists()) {
        $adapter->useTable($tableName)->deleteTable();
    }

    if ($adapter->useTable($tableName)->createTable($keys)) {
        printf("The '%s' table was successfully created!" . PHP_EOL, $tableName);
    }

    // list the created tables
    $tables = $adapter->listTables();
    foreach ($tables as $table) {
        printf("The '%s' table exists!" . PHP_EOL, $table);
    }

    // delete a table
    if ($adapter->useTable($tableName)->deleteTable()) {
        printf("The '%s' table was successfully deleted!" . PHP_EOL, $tableName);
    }

    // check for the existence of a table
    if (!$adapter->useTable($tableName)->tableExists()) {
        printf("The '%s' table no longer exists!" . PHP_EOL, $tableName);
    }

} catch (\Exception $ex) {
    die($ex->getMessage() . PHP_EOL);
}
