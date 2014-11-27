<?php
	include_once("class/db.php");

	class session
	{
		private $id, $user, $login, $last, $ipaddr;

		private function __construct($id, $user, $login, $last, $ipaddr)
		{
			$this->id = $id;
			$this->user = $user;
			$this->login = $login;
			$this->last = $last;
			$this->ipaddr = $ipaddr;
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

			db::delete('sessions', array('user' => $uid));

			if(!db::insert('sessions', array(
				'id' => $sid,
				'user' => $uid,
				'login' => $now,
				'last' => $now,
				'ipaddr' => $_SERVER['REMOTE_ADDR'])))
				return false;
			else
				return new session($sid, $uid, $now, $now, $_SERVER['REMOTE_ADDR']);
		}
	}
?>
