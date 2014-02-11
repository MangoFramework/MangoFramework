<?php
/*
|--------------------------------------------------------------------------
| Database Connections
|--------------------------------------------------------------------------
|
| All database work in Mango is done through the PHP PDO facilities
| so make sure you have the driver for your particular database of
| choice installed on your machine before you begin development.
|
*/
return array(
    'default' => 'mysql',
    'connections' => array(
        'mysql' => array(
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'database1',
            'username' => 'root',
            'password' => 'x5stZny/',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        )
    ),
);