<?php
	include_once("config.php");

	class db
	{
		public static $pdo;

		public static function exec($cmd)
		{
			return(self::$pdo->exec($cmd));
		}

		public static function query($cmd)
		{
			return(self::$pdo->query($cmd));
		}

		public static function errorInfo()
		{
			return(self::$pdo->errorInfo());
		}

		public static function create_table($name, $columns)
		{
			global $database;

			$cols = array();
			foreach($columns as $column)
				$cols[] = implode(" ", $column);

			self::exec("DROP TABLE {$database['prefix']}$name");
			return(self::exec("CREATE TABLE {$database['prefix']}$name (" .  implode(", ", $cols) . ")"));
		}
	}

	db::$pdo = new PDO($database["database"], $database["user"], $database["password"]);
?>
