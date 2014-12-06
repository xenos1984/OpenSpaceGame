<?php
include_once("class/db.php");

class building
{
	private $id, $time, $factor;

	private function __construct(&$data)
	{
		$this->id = $data['id'];
		$this->time = $data['time'];
		$this->factor = $data['factor'];
	}

	public function __get($var)
	{
		switch($var)
		{
		case 'id':
			return $this->id;
		case 'time':
			return $this->time;
		case 'factor':
			return $this->factor;
		default:
			return null;
		}
	}

	public static function all()
	{
		$data = db::select_all('buildings', array());
		if(!$data)
			return false;
		return array_map(function($x) { return new building($x); }, $data);
	}
}
?>
