Working with Tables
**************************

Listing Tables
####################
To see a list of the tables that exist your database, use the ``listTables()`` method. It will return an indexed array that stores the names of the existing tables.

.. code-block:: php
    $tables = $adapter->listTables();
    foreach ($tables as $table) {
        echo $table . PHP_EOL;
    }

Creating Tables
####################
Simple table creation can be accomplished using the adapter's ``createTable()`` method.

Deleting Tables
####################
To delete a table in your database, use the ``deleteTable()`` method. It will return a boolean value that indicates whether or not the deletion was successful. For example:

.. code-block:: php
    $result = $adapter->deleteTable('myTable');
