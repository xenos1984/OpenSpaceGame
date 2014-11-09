<?php
	include_once("class/db.php");

	class resource
	{
		public static function create($id, $value, $product, $storage)
		{
			db::create_object('resources', array(
				'id' => $id,
				'value' => $value,
				'product' => $product,
				'storage' => $storage));
		}
	}
?>
