<?php

return array(
    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    | The providers register in this configuration can be loaded anywhere in your application via the Container make() method.
    | You provid the full name of the Class with namespaces with aliases and use the alias with the make() method.
    | 
    | Example : 
    | 'Alias' => 'my\Path\Class'
    | 
    | Can be loaded like : 
    | $loadedClass = Container::make('Alias');
    |
    */
    'providers' => array(
    ),
    /*
    |--------------------------------------------------------------------------
    | Session driver
    |--------------------------------------------------------------------------
    | Here you can set the driver for session handler : native, database, or redis
    | 
    */

    'session' => 'native',
    /*
    |--------------------------------------------------------------------------
    | Authentification
    |--------------------------------------------------------------------------
    | Configure your authentification : the model assiocated with the authentification, the id is the unique field where you want to auth with, 
    | finally the password is the field name of the password.
    | By default : 
    | Model : User
    | id : email
    | password : password
    */
    'auth' => array(
    	'model' => '',
    	'id' => 'email',
    	'password' => ''
    	'salt' => ''
	)
);