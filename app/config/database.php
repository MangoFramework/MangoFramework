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
<<<<<<< HEAD
            'database' => 'bibliotheque',
=======
            'database' => 'test',
>>>>>>> bf25304f770426d9a51fa53713d8be75eaecf859
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        )
    ),
);