<?php declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Guillermoandrae\DynamoDb\Constant\AttributeTypes;
use Guillermoandrae\DynamoDb\Constant\KeyTypes;
use Guillermoandrae\DynamoDb\DynamoDbAdapter;

// create a new adapter
$adapter = new DynamoDbAdapter();

try {
    // create the table
    $tableName = 'singers';
    $keys = [
        'name' => [
            'attributeType' => \Guillermoandrae\DynamoDb\Constant\AttributeTypes::STRING,
            'keyType' => KeyTypes::HASH
        ],
        'year' => [
            'attributeType' => AttributeTypes::NUMBER,
            'keyType' => KeyTypes::RANGE
        ],
    ];
    $adapter->useTable($tableName)->createTable($keys);

    // insert items
    $names = ['Marvin Gaye', 'Jackie Wilson'];
    foreach ($names as $name) {
        $adapter->useTable($tableName)->insert([
            'name' => $name,
            'year' => 1984,
            'single' => 'Nightshift'
        ]);
        printf("Successfully added '%s' to the '%s' table!" . PHP_EOL, $name, $tableName);
    }

    // get all items
    $items = $adapter->useTable($tableName)->findAll();
    printf("The following items were found in the '%s' table:" . PHP_EOL, $tableName);
    foreach ($items as $item) {
        printf(
            "\t - '%s', who died in '%s'" . PHP_EOL,
            $item['name'],
            $item['year'],
            $tableName
        );
    }

    // get an item
    $item = $adapter->useTable($tableName)->find([
        'name' => 'Marvin Gaye',
        'year' => 1984
    ]);
    printf(
        "Successfully retrieved '%s' (mentioned in the Commodores' tribute single '%s') from the '%s' table!" . PHP_EOL,
        $item['name'],
        $item['single'],
        $tableName
    );

    // delete an item
    if ($adapter->useTable($tableName)->delete(['name' => 'Marvin Gaye', 'year' => 1984])) {
        printf(
            "Successfully deleted '%s' from the '%s' table!" . PHP_EOL,
            $item['name'],
            $tableName
        );
    }

    // get all items
    $items = $adapter->useTable($tableName)->findAll();
    printf("The following items remain in the '%s' table:" . PHP_EOL, $tableName);
    foreach ($items as $item) {
        printf(
            "\t - '%s', who died in '%s'" . PHP_EOL,
            $item['name'],
            $item['year'],
            $tableName
        );
    }

    // delete the table
    $adapter->useTable($tableName)->deleteTable();

} catch (\Exception $ex) {
    die($ex->getMessage());
}
