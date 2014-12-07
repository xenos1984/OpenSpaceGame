<?php
include_once("class/db.php");

class celb
{
	private $galaxy, $sun, $orbit, $celb, $type, $owner, $name;

	private function __construct(&$data)
	{
		$this->galaxy = $data['galaxy'];
		$this->sun = $data['sun'];
		$this->orbit = $data['orbit'];
		$this->celb = $data['celb'];
		$this->type = $data['type'];
		$this->owner = $data['owner'];
		$this->name = $data['name'];
	}

	public function __get($var)
	{
		switch($var)
		{
		case 'galaxy':
			return $this->galaxy;
		case 'sun':
			return $this->sun;
		case 'orbit':
			return $this->orbit;
		case 'celb':
			return $this->celb;
		case 'type':
			return $this->type;
		case 'owner':
			return $this->owner;
		case 'name':
			return $this->name;
		case 'coords':
			return "{$this->galaxy}:{$this->sun}:{$this->orbit}:{$this->celb}";
		default:
			return null;
		}
	}

	public function __set($var, $value)
	{
		switch($var)
		{
		case 'owner':
			db::update('celbs', array('galaxy' => $this->galaxy, 'sun' => $this->sun, 'orbit' => $this->orbit, 'celb' => $this->celb), array('owner' => $value));
			$this->owner = $value;
			break;
		case 'name':
			db::update('celbs', array('galaxy' => $this->galaxy, 'sun' => $this->sun, 'orbit' => $this->orbit, 'celb' => $this->celb), array('name' => $value));
			$this->name = $value;
			break;
		}
	}

	public static function bypos($galaxy, $sun, $orbit, $celb)
	{
		$data = db::select_one('celbs', array('galaxy' => $galaxy, 'sun' => $sun, 'orbit' => $orbit, 'celb' => $celb));
		if(!$data)
			return false;
		return new celb($data);
	}

	public static function bycoord($coord)
	{
		if(!preg_match('/(\d+):(\d+):(\d+):(\d+)/i', $coord, $result))
			return false;
		return self::bypos($result[1], $result[2], $result[3], $result[4]);
	}

	public static function byowner($uid)
	{
		$data = db::select_all('celbs', array('owner' => $uid));
		if(!$data)
			return false;
		return array_map(function($x) { return new celb($x); }, $data);
	}

	public static function randfree($type)
	{
		$cnt = db::select_one('celbs', array('type' => $type, 'owner' => 0), array('COUNT(*)'));
		if(!$cnt)
			return false;
		$index = mt_rand(0, $cnt['COUNT(*)'] - 1);
		$data = db::select_one('celbs', array('type' => $type, 'owner' => 0), array('*'), null, $index);
		if(!$data)
			return false;
		return new celb($data);
	}

	public function sethome($uid, $name = config::DEF_HOME)
	{
		$this->owner = $uid;
		$this->name = $name;
		return db::update('celbs', array('galaxy' => $this->galaxy, 'sun' => $this->sun, 'orbit' => $this->orbit, 'celb' => $this->celb), array('owner' => $uid, 'name' => $name));
	}
}
?>
