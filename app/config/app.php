<?php
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

return array(
    'providers' => array(
    ),
    //Session drivers : native or database
    'session' => 'native',
    //Authentification Configuration, default : 
    //Model : User
    //id : id
    //password : password
    'auth' => array(
    	'model' => '',
    	'id' => 'email',
    	'password' => ''
    	'salt' => ''
	)
);