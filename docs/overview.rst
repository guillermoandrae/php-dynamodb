Overview
**************************
**php-dynamodb** is a PHP library that can be used to interact with `Amazon DynamoDB <https://aws.amazon.com/dynamodb/>`. It provides a layer of abstraction between your code and the DynamoDB-related classes made available by the `AWS SDK for PHP <https://github.com/aws/aws-sdk-php>`_.

Installation
###############
The recommended way to install this library is through `Composer <https://getcomposer.org>`_:
::
    composer install guillermoandrae/php-dynamodb

You can also specify this library as a dependency in your project using your composer.json file:
.. code-block:: json
    {
       "require": {
          "guillermoandrae/php-dynamodb": "*"
       }
    }

Running DynamoDB Locally
##############################
To aid in your development, you can run the following commands to manage DynamoDB locally:
::
    composer install-db # downloads and installs DynamoDB locally
    composer start-db # starts DynamoDB locally
    composer stop-db # stops DynamoDB locally
    composer restart-db # calls stop-db then start-db

Contributing
##############
To find out how to contribute to this project, please refer to the `CONTRIBUTING <https://github.com/guillermoandrae/php-dynamodb/blob/master/CONTRIBUTING.md>`_ file in the project's GitHub repository.