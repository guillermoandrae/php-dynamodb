Overview
**************************
php-dynamodb is a PHP library that can be used to interact with Amazon DynamoDB. It provides a layer of abstraction between your code and the DynamoDB-related classes made available by the `AWS SDK for PHP <https://github.com/aws/aws-sdk-php>`_.

Installation
###############
The recommended way to install this library is through `Composer <https://getcomposer.org>`_:
::
    composer install guillermoandrae/php-dynamodb

Running DynamoDB locally
##############################
To aid in your development, you can run the following commands to manage DynamoDB locally:
::
    composer install-db # downloads and installs DynamoDB locally
    composer start-db # starts DynamoDB locally
    composer stop-db # stops DynamoDB locally
    composer restart-db # calls stop-db then start-db

Testing
#########
Run the following command to make sure your code is appropriately styled:
::
    composer check-style

Run the following command to check style, run tests, and generate a Clover report:
::
    composer test

Run the following command to check style, run tests, and generate an HTML report (access the report at http://localhost:8080):
::
    composer test-html


Contributing
###############

Coming soon!