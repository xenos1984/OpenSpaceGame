<?php
	include_once("class/db.php");

	class player
	{
		public static function create($nick, $password, $email)
		{
			$pwhash = password_hash($password, PASSWORD_DEFAULT);

			return db::insert('players', array(
				'nick' => $nick,
				'pwhash' => $pwhash,
				'email' => $email));
		}

		public static function checkpass($nick, $password)
		{
			$data = db::select_one('players', array('id', 'pwhash'), array('nick' => $nick));
			if(!$data)
				return false;
			if(!password_verify($password, $data['pwhash']))
				return false;
			return $data['id'];
		}

		public static function find($nick)
		{
			$data = db::select_one('players', array('id'), array('nick' => $nick));
			if(!$data)
				return false;
			return $data['id'];
		}
	}
?>
