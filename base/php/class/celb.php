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
		default:
			return null;
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
}
?>
