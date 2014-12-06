<?php
include_once("class/db.php");

class trans
{
	private $lang, $id, $name, $descr;

	private function __construct(&$data)
	{
		$this->lang = $data['lang'];
		$this->id = $data['id'];
		$this->name = $data['name'];
		$this->descr = $data['descr'];
	}

	public function __get($var)
	{
		switch($var)
		{
		case 'lang':
			return $this->lang;
		case 'id':
			return $this->id;
		case 'name':
			return $this->name;
		case 'descr':
			return $this->descr;
		default:
			return null;
		}
	}

	public static function find($lang, $id)
	{
		$data = db::select_one('translation', array('lang' => $lang, 'id' => $id));
		if(!$data)
			return false;
		return new trans($data);
	}
}
?>
