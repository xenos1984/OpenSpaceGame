<?php
include_once("config.php");

class db
{
	private static $pdo;

	public static function init()
	{
		self::$pdo = new PDO(config::DB_DATABASE, config::DB_USER, config::DB_PASSWORD);
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
		$cols = array();
		foreach($columns as $column)
			$cols[] = implode(" ", $column);

		self::exec("DROP TABLE " . config::DB_PREFIX . $name);
		return(self::exec("CREATE TABLE " . config::DB_PREFIX . "$name (" .  implode(", ", $cols) . ")"));
	}

	public static function insert($table, $entries)
	{
		$keys = array_keys($entries);
		$params = array_map(function($s) { return ':' . $s; }, $keys);
		$stmt = self::$pdo->prepare("INSERT INTO " . config::DB_PREFIX . "$table (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $params) . ")");

		foreach($entries as $key => $value)
			$stmt->bindValue(':' . $key, $value);

		return $stmt->execute();
	}

	public static function delete($table, $conditions)
	{
		$where = array_map(function($s) { return $s . ' = :' . $s; }, array_keys($conditions));
		$stmt = self::$pdo->prepare("DELETE FROM " . config::DB_PREFIX . "$table WHERE " . implode(" AND ", $where));

		foreach($conditions as $key => $value)
			$stmt->bindValue(':' . $key, $value);

		return $stmt->execute();
	}

	public static function update($table, $conditions, $entries)
	{
		$set = array_map(function($s) { return $s . ' = :' . $s; }, array_keys($entries));
		$where = array_map(function($s) { return $s . ' = :' . $s; }, array_keys($conditions));
		$stmt = self::$pdo->prepare("UPDATE " . config::DB_PREFIX . "$table SET " . implode(", ", $set) . " WHERE " . implode(" AND ", $where));

		foreach($entries as $key => $value)
			$stmt->bindValue(':' . $key, $value);
		foreach($conditions as $key => $value)
			$stmt->bindValue(':' . $key, $value);

		return $stmt->execute();
	}

	public static function select_one($table, $conditions, $columns = array('*'), $order = null, $offset = null)
	{
		$where = array_map(function($s) { return $s . ' = :' . $s; }, array_keys($conditions));
		$cmd = "SELECT " . implode(", ", $columns) . " FROM " . config::DB_PREFIX . "$table WHERE " . implode(" AND ", $where);
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
		$cmd = "SELECT " . implode(", ", $columns) . " FROM " . config::DB_PREFIX . $table;
		if(count($conditions))
		{
			$where = array_map(function($s) { return $s . ' = :' . $s; }, array_keys($conditions));
			$cmd .= " WHERE " . implode(" AND ", $where);
		}
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
