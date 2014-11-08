<?php
	include_once("config.php");

	class db
	{
		private static $pdo;

		public static function init()
		{
			global $database;

			self::$pdo = new PDO($database["database"], $database["user"], $database["password"]);
		}

		private static function exec($cmd)
		{
			return(self::$pdo->exec($cmd));
		}

		private static function query($cmd)
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

		public static function create_object($table, $entries)
		{
			global $database;

			$keys = array_column($entries, 0);
			$params = array_map( function($s) { return ':' . $s; }, $keys);
			$stmt = self::$pdo->prepare("INSERT INTO $table (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $params) . ")");

			foreach($entries as $entry)
			{
				if(count($entry) == 4)
					$stmt->bindParam(':' . $entry[0], $entry[1], $entry[2], $entry[3]);
				else
					$stmt->bindParam(':' . $entry[0], $entry[1], $entry[2]);
			}

			return $stmt->execute();
		}
	}

	db::init();
?>
