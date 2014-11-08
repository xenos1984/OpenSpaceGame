<?php
	include_once("class/db.php");

	class trans
	{
		public static function create($lang, $id, $name, $descr)
		{
			global $database;

			db::create_object("translation", array(
				array("lang", $lang, PDO::PARAM_STR, 8),
				array("id", $id, PDO::PARAM_STR, 16),
				array("name", $name, PDO::PARAM_STR, 64),
				array("descr", $descr, PDO::PARAM_STR)));
		}
	}
?>
