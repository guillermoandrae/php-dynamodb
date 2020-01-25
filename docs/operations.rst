Executing Operations
**************************
You can easily execute DynamoDB operations using a straightforward API.

The DynamoDB Client Factory
############################
To create an instance of the AWS ``DynamoDbClient`` class, you can use php-dynamodb's ``DynamoDbClientFactory``. By default, it will use local credentials to create an instance of the client, but you can provide your own options to create a client that can connect to a DynamoDB table in your AWS account. You can use the ``DynamoDbClientFactory`` to create clients that can be passed to the DynamoDB adapter.

**Example**
::
    use Guillermoandrae\DynamoDb\Factory\DynamoDbClientFactory;

    $client = DynamoDbClientFactory::factory();

or

**Example**
::
    use Guillermoandrae\DynamoDb\Factory\DynamoDbClientFactory;

    $client = DynamoDbClientFactory::factory([
        'region' => '<your region>',
        'version' => 'latest',
        'endpoint' => '<your endpoint>',
        'credentials' => [
            'key' => '<your key>',
            'secret' => '<your secret>',
    ]);

The Marshaler Factory
##########################
A ``Marshaler`` object is needed to process requests and results. The ``MarshalerFactory`` creates instances of the AWS ``Marshaler`` that can be passed to the DynamoDB adapter.

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
