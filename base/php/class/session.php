<?php
include_once("class/db.php");

class session
{
	private $id, $user, $login, $last, $ipaddr;

	private function __construct(&$data)
	{
		$this->id = $data['id'];
		$this->user = $data['user'];
		$this->login = $data['login'];
		$this->last = $data['last'];
		$this->ipaddr = $data['ipaddr'];
	}

	public function __get($var)
	{
		switch($var)
		{
		case 'id':
			return $this->id;
		case 'user':
			return $this->user;
		case 'login':
			return $this->login;
		case 'last':
			return $this->last;
		case 'ipaddr':
			return $this->ipaddr;
		default:
			return null;
		}
	}

	public static function create($uid)
	{
		$sid = str_replace(array('+', '/'), array('-', '_'), base64_encode(openssl_random_pseudo_bytes(24)));
		$now = time();
		$data = array('id' => $sid, 'user' => $uid, 'login' => $now, 'last' => $now, 'ipaddr' => $_SERVER['REMOTE_ADDR']);

		db::delete('sessions', array('user' => $uid));
		if(!db::insert('sessions', $data))
			return false;
		else
			return new session($data);
	}

	public static function byid($sid)
	{
		$data = db::select_one('sessions', array('id' => $sid));
		if(!$data)
			return false;
		return new session($data);
	}
}
?>
