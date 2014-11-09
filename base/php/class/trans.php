<?php
	include_once("class/db.php");

	class trans
	{
		public static function create($lang, $id, $name, $descr)
		{
			db::create_object('translation', array(
				'lang' => $lang,
				'id' => $id,
				'name' => $name,
				'descr' => $descr));
		}
	}
?>
