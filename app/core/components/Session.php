<?php namespace core\components;
use core\Container;
class Session 
{
  public function __construct($config)
  {
  	if ($config == 'native')
  		session_start();

		$c = Container::getInstance();
    
		if (isset($c['inputs']['block']) && $c['inputs']['block'] == "FALSE")
			session_write_close();
  }

  public function addData($key, $value)
  {

  	$_SESSION['data'][$key] = $value;
  	
  }

  public function getData($key)
  {
  	if (array_key_exists($key, $_SESSION['data'])) {
  		return $_SESSION['data'][$key];
  	}
  	elseif (array_key_exists($key,$_SESSION['flash'])) {
  		$data = $_SESSION['flash'][$key];
  		unset($_SESSION['flash'][$key]);
  		return $data;
  	}
  	else {
  		return NULL;
  	}
  }

  public function removeData($key)
  {
  	if (array_key_exists($key, $_SESSION['data'])) {
  		unset($_SESSION['data'][$key]);
  	}
  }
  
  public function Flash($key, $value)
  {
  	$_SESSION['flash'][$key] = $value;
  }

  public function addUser($data)
  {
  	$_SESSION['user'] = $data;
  }

  public function getUser($key = NULL)
  {
  	if (is_null($key)) {
  		return $_SESSION['user'];
  	}
  	elseif (array_key_exists($key, $_SESSION['user'])) {
  		return $_SESSION['user'][$key];
  	}
  	else {
  		return NULL;
  	}
  }

  public function logout()
  {
  	$_SESSION['user'] = array();
  }


}