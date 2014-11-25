<?php
include_once("config.php");
include_once("class/db.php");

function maketable($tabname, $content)
{
	global $database;

	echo "<li>Create table {$database['prefix']}$tabname... ";

	db::create_table($tabname, $content);

	$res = db::errorInfo();
	if($res[0] != "00000")
		echo '<span style="color:red">Error ' . $res[0] . '</span>: ' . $res[1] . ': ' . $res[2] . "</li>\n";
	else
		echo '<span style="color:lime">Success.</span></li>' . "\n";
}

echo "<h3>Create tables</h3>\n";
echo "<ul>\n";

// Universe information - translations

maketable("translation", array(
	array("lang", "VARCHAR(8)", "NOT NULL"),
	array("id", "VARCHAR(16)", "NOT NULL"),
	array("name", "VARCHAR(64)"),
	array("descr", "TEXT"),
	array("PRIMARY KEY", "(lang, id)")));

// Universe information - resources

maketable("resources", array(
	array("id", "VARCHAR(16)", "NOT NULL", "PRIMARY KEY"),
	array("value", "REAL"),
	array("storage", "REAL"),
	array("product", "REAL")));

// Universe information - buildings

maketable("buildings", array(
	array("id", "VARCHAR(16)", "NOT NULL", "PRIMARY KEY"),
	array("time", "REAL"),
	array("factor", "REAL")));

// Universe information - researches

maketable("researches", array(
	array("id", "VARCHAR(16)", "NOT NULL", "PRIMARY KEY"),
	array("time", "REAL"),
	array("factor", "REAL")));

// Universe information - buildings allowed on celb type

maketable("buildon", array(
	array("celb", "VARCHAR(16)", "NOT NULL"),
	array("building", "VARCHAR(16)", "NOT NULL")));

// Universe information - storage capacity of buildings

maketable("storages", array(
	array("building", "VARCHAR(16)", "NOT NULL"),
	array("res", "VARCHAR(16)", "NOT NULL"),
	array("value", "REAL"),
	array("power", "REAL"),
	array("PRIMARY KEY", "(building, res)")));

// Universe information - resource production of buildings

maketable("products", array(
	array("building", "VARCHAR(16)", "NOT NULL"),
	array("res", "VARCHAR(16)", "NOT NULL"),
	array("value", "REAL"),
	array("power", "REAL"),
	array("PRIMARY KEY", "(building, res)")));

// Universe information - costs for buildings and research (leveled objects)

maketable("br_costs", array(
	array("brid", "VARCHAR(16)", "NOT NULL"),
	array("res", "VARCHAR(16)", "NOT NULL"),
	array("value", "REAL"),
	array("factor", "REAL"),
	array("PRIMARY KEY", "(brid, res)")));

// Universe information - costs for ships, defense and missiles (mass objects)

maketable("sdm_costs", array(
	array("sdmid", "VARCHAR(16)", "NOT NULL"),
	array("res", "VARCHAR(16)", "NOT NULL"),
	array("cost", "REAL"),
	array("PRIMARY KEY", "(sdmid, res)")));

// Players

maketable("players", array(
	array("id", "BIGINT", "NOT NULL", "PRIMARY KEY", "AUTO_INCREMENT"),
	array("nick", "VARCHAR(32)", "NOT NULL", "UNIQUE"),
	array("pwhash", "VARCHAR(255)", "NOT NULL"),
	array("email", "VARCHAR(64)", "NOT NULL"),
	array("lang", "VARCHAR(8)"),
	array("css", "VARCHAR(256)", "NOT NULL"),
	array("xslt", "VARCHAR(256)", "NOT NULL"),
	array("umod", "BIGINT"),
	array("banned", "BIGINT")));

// Sessions

maketable("sessions", array(
	array("id", "VARCHAR(32)", "NOT NULL", "PRIMARY KEY"),
	array("user", "BIGINT", "NOT NULL"),
	array("login", "BIGINT", "NOT NULL"),
	array("last", "BIGINT", "NOT NULL"),
	array("curIP", "VARCHAR(16)"),
	array("lastIP", "VARCHAR(16)")));

// Celestial bodies - positions

maketable("suns", array(
	array("galaxy", "SMALLINT", "NOT NULL"),
	array("sun", "SMALLINT", "NOT NULL"),
	array("posX", "REAL"),
	array("posY", "REAL"),
	array("posZ", "REAL"),
	array("PRIMARY KEY", "(galaxy, sun)")));

maketable("orbits", array(
	array("galaxy", "SMALLINT", "NOT NULL"),
	array("sun", "SMALLINT", "NOT NULL"),
	array("orbit", "SMALLINT", "NOT NULL"),
	array("radius", "REAL"),
	array("phase", "REAL"),
	array("PRIMARY KEY", "(galaxy, sun, orbit)")));

maketable("celbs", array(
	array("galaxy", "SMALLINT", "NOT NULL"),
	array("sun", "SMALLINT", "NOT NULL"),
	array("orbit", "SMALLINT", "NOT NULL"),
	array("celb", "SMALLINT", "NOT NULL"),
	array("type", "VARCHAR(16)", "NOT NULL"),
	array("owner", "BIGINT"),
	array("name", "VARCHAR(32)"),
	array("PRIMARY KEY", "(galaxy, sun, orbit, celb)")));

echo "</ul>\n";

?>
