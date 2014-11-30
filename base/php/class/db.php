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

	public static function insert($table, $entries)
	{
		global $database;

		$keys = array_keys($entries);
		$params = array_map(function($s) { return ':' . $s; }, $keys);
		$stmt = self::$pdo->prepare("INSERT INTO {$database['prefix']}$table (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $params) . ")");

		foreach($entries as $key => $value)
			$stmt->bindValue(':' . $key, $value);

		return $stmt->execute();
	}

	public static function delete($table, $conditions)
	{
		global $database;

		$where = array_map(function($s) { return $s . ' = :' . $s; }, array_keys($conditions));
		$stmt = self::$pdo->prepare("DELETE FROM {$database['prefix']}$table WHERE " . implode(" AND ", $where));

		foreach($conditions as $key => $value)
			$stmt->bindValue(':' . $key, $value);

		return $stmt->execute();
	}

	public static function update($table, $conditions, $entries)
	{
		global $database;

		$set = array_map(function($s) { return $s . ' = :' . $s; }, array_keys($entries));
		$where = array_map(function($s) { return $s . ' = :' . $s; }, array_keys($conditions));
		$stmt = self::$pdo->prepare("UPDATE {$database['prefix']}$table SET " . implode(", ", $set) . " WHERE " . implode(" AND ", $where));

		foreach($entries as $key => $value)
			$stmt->bindValue(':' . $key, $value);
		foreach($conditions as $key => $value)
			$stmt->bindValue(':' . $key, $value);

		return $stmt->execute();
	}

	public static function select_one($table, $conditions, $columns = array('*'), $order = null, $offset = null)
	{
		global $database;

		$where = array_map(function($s) { return $s . ' = :' . $s; }, array_keys($conditions));
		$cmd = "SELECT " . implode(", ", $columns) . " FROM {$database['prefix']}$table WHERE " . implode(" AND ", $where);
		if(isset($order))
			$cmd .= " ORDER BY $order";
		$cmd .= " LIMIT 1";
		if(isset($offset))
			$cmd .= " OFFSET :offset";
		$stmt = self::$pdo->prepare($cmd);

		foreach($conditions as $key => $value)
			$stmt->bindValue(':' . $key, $value);
		if(isset($offset))
			$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

		if(!($stmt->execute()))
			return false;

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public static function select_all($table, $conditions, $columns = array('*'), $order = null, $limit = null, $offset = null)
	{
		global $database;

		$where = array_map(function($s) { return $s . ' = :' . $s; }, array_keys($conditions));
		$cmd = "SELECT " . implode(", ", $columns) . " FROM {$database['prefix']}$table WHERE " . implode(" AND ", $where);
		if(isset($order))
			$cmd .= " ORDER BY $order";
		if(isset($limit))
			$cmd .= " LIMIT :limit";
		if(isset($offset))
			$cmd .= " OFFSET :offset";
		$stmt = self::$pdo->prepare($cmd);

		foreach($conditions as $key => $value)
			$stmt->bindValue(':' . $key, $value);
		if(isset($limit))
			$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		if(isset($offset))
			$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

		if(!($stmt->execute()))
			return false;

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}

db::init();
?>
