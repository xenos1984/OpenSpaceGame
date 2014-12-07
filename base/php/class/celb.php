<?php
include_once("class/db.php");
include_once("class/resource.php");
include_once("class/formula.php");

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
		if(!db::update('celbs', array('galaxy' => $this->galaxy, 'sun' => $this->sun, 'orbit' => $this->orbit, 'celb' => $this->celb), array('owner' => $uid, 'name' => $name)))
			return false;

		$now = time();
		$ress = resource::all();
		foreach($ress as $res)
		{
			if($ps = db::select_one('prodstore', array('celb' => $this->type, 'res' => $res->id)))
			{
				$fp = unserialize($ps['production']);
				$fs = unserialize($ps['storage']);
				$prod = $fp->evaluate(array());
				$store = $fs->evaluate(array());
			}
			else
			{
				$prod = 0;
				$store = 0;
			}
			db::insert('celb_ress', array('galaxy' => $this->galaxy, 'sun' => $this->sun, 'orbit' => $this->orbit, 'celb' => $this->celb, 'res' => $res->id, 'present' => $res->value, 'production' => $prod, 'storage' => $store, 'time' => $now));
		}
		return true;
	}

	public function resvalues($res = null)
	{
		$now = time();

		if(is_null($res))
		{
			$data = select_all('celb_ress', array('galaxy' => $this->galaxy, 'sun' => $this->sun, 'orbit' => $this->orbit, 'celb' => $this->celb));
			foreach($data as &$item)
			{
				$item['present'] = max($item['present'], min($item['storage'], $item['present'] + $item['production'] * ($now - $item['time'])));
				$item['time'] = $now;
			}
		}
		else
		{
			$data = select_one('celb_ress', array('galaxy' => $this->galaxy, 'sun' => $this->sun, 'orbit' => $this->orbit, 'celb' => $this->celb, 'res' => $res));
			$data['present'] = max($data['present'], min($data['storage'], $data['present'] + $data['production'] * ($now - $data['time'])));
			$data['time'] = $now;
		}
	}
}
?>
