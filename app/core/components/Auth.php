<?php namespace core\components;
use models;
use core\container;

Class Auth
{
	private $session;
	private $salt;
	private $db;
	private $config
	private $input;

	public function __construct(Session $session,Database $db ,$config)
	{
		$this->session = $session;
		$this->salt = $salt;
		$this->db = $db->getConnection();
		$c = Container::getInstance();
		$this->input = $c['input'];
	}

	public function login()
	{
		$model = if ($config['model'] === '') : 'User' ? $config['model'];
		$id = $config['id'];
		$pwd = if ($config['password'] === '') : 'password' ? $config['password'];
		$user = $model::where($id,'=', $input[$id])->take(1)->get();
		if ($user->$pwd == $this->encrypt($input))
		{
			$session->addUser($user->getAttributes());
			return $user;
		}
	
		return false;
	}

	public function logout()
	{
		$session->logout();
	}

	public function encrypt($pass)
	{
		return sha1(md5($pwd.$this->salt));
	}

}