<?php namespace core\components;
use models;
use core\container;

Class Auth
{
	private $session;
	private $db;
	private $config;
	private $input;

	public function __construct(Session $session,Database $db , $input, $config)
	{
		$this->session = $session;
		$this->config = $config;
		$this->db = $db->getConnection();
		$this->input = $input;
	}

	public function login()
	{
		$model = ($this->config['model'] === '') ? 'User' : $this->config['model'];
		$id = $config['id'];
		$pwd = ($this->config['password'] === '') ? 'password' : $this->config['password'];
		$user = $model::where($id,'=', $input[$id])->take(1)->get();
		if ($user->$pwd == $this->encrypt($this->input[$pwd]))
		{
			$this->session->addUser($user->getAttributes());
			return $user;
		}
	
		return false;
	}

	public function logout()
	{
		$this->session->logout();
	}

	public function encrypt($pass)
	{
		return sha1(md5($pwd.$this->$this->config['salt']));
	}

	public function isAuth()
	{
		$result = $this->session->getUser();
		return (!empty($result));
	}

}