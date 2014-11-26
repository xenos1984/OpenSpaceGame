<?php
	include_once("class/db.php");

	class session
	{
		public static function create($uid)
		{
			$sid = str_replace(array('+', '/'), array('-', '_'), base64_encode(openssl_random_pseudo_bytes(24)));
			$now = time();

			if(!db::insert('sessions', array(
				'id' => $sid,
				'user' => $uid,
				'login' => $now,
				'last' => $now,
				'ipaddr' => $_SERVER['REMOTE_ADDR'])))
				return false;
			else
				return $sid;
		}
	}
?>
