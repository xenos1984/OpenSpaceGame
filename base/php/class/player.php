<?php
	include_once("class/db.php");

	class player
	{
		private $id, $nick, $pwhash, $email, $lang, $css, $xslt, $umod, $banned;

		private function __construct(&$data)
		{
			$this->id = $data['id'];
			$this->nick = $data['nick'];
			$this->pwhash = $data['pwhash'];
			$this->email = $data['email'];
			$this->lang = $data['lang'];
			$this->css = $data['css'];
			$this->xslt = $data['xslt'];
			$this->umod = $data['umod'];
			$this->banned = $data['banned'];
		}

		public function __get($var)
		{
			switch($var)
			{
			case 'id':
				return $this->id;
			case 'nick':
				return $this->nick;
			case 'pwhash':
				return $this->pwhash;
			case 'email':
				return $this->email;
			case 'lang':
				return $this->lang;
			case 'css':
				return $this->css;
			case 'xslt':
				return $this->xslt;
			case 'umod':
				return $this->umod;
			case 'banned':
				return $this->banned;
			default:
				return null;
			}
		}

		public static function create($nick, $password, $email)
		{
			$pwhash = password_hash($password, PASSWORD_DEFAULT);

			db::insert('players', array(
				'nick' => $nick,
				'pwhash' => $pwhash,
				'email' => $email));

			return self::find($nick);
		}

		public static function checkpass($nick, $password)
		{
			$data = db::select_one('players', array('nick' => $nick));
			if(!$data)
				return false;
			if(!password_verify($password, $data['pwhash']))
				return false;
			return new player($data);
		}

		public static function find($nick)
		{
			$data = db::select_one('players', array('nick' => $nick));
			if(!$data)
				return false;
			return new player($data);
		}
	}
?>
