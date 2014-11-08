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
			$cmd = "CREATE TABLE {$database['prefix']}$name (" .  implode(", ", $columns) . ")";
			return(self::exec($cmd));
		}
	}

	db::$pdo = new PDO($database["database"], $database["user"], $database["password"]);
?>