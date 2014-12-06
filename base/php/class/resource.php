<?php
include_once("class/db.php");

class resource
{
	private $id, $value;

	private function __construct(&$data)
	{
		$this->id = $data['id'];
		$this->value = $data['value'];
	}

	public function __get($var)
	{
		switch($var)
		{
		case 'id':
			return $this->id;
		case 'value':
			return $this->value;
		default:
			return null;
		}
	}

	public static function all()
	{
		$data = db::select_all('resources', array(), array('*'), 'sort');
		if(!$data)
			return false;
		return array_map(function($x) { return new resource($x); }, $data);
	}
}
?>
