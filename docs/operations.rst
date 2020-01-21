Executing Operations
**************************
You can easily create DynamoDB operations using a straightforward API.

The DynamoDB Client Factory
##########################
**Example**
::
    use Guillermoandrae\DynamoDb\Factory\DynamoDbClientFactory;

    $client = DynamoDbClientFactory::factory();

or
**Example**
::
    use Guillermoandrae\DynamoDb\Factory\DynamoDbClientFactory;

    $client = DynamoDbClientFactory::factory([
    ]);

The Marshaler Factory
##########################
**Example**
::
    use Guillermoandrae\DynamoDb\Factory\MarshalerFactory;

    $marshaler = MarshalerFactory::factory()

The DynamoDB Adapter
##########################
**Example**
::
    use Guillermoandrae\DynamoDb\DynamoDbAdapter;

    $adapter = new DynamoDbAdapter();

The Operation Factory
##########################
**Example**
::
    use Guillermoandrae\DynamoDb\Factory\DynamoDbClientFactory;
    use Guillermoandrae\DynamoDb\Factory\MarshalerFactory;
    use Guillermoandrae\DynamoDb\Factory\OperationFactory;

    OperationFactory::registerClient(DynamoDbClientFactory::factory());
    OperationFactory::registerMarshaler(MarshalerFactory::factory());
    $operation = OperationFactory::factory('list-tables', 'myTable');
    $operation->execute();
