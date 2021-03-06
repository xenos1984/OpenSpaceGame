<?php
include_once("config.php");
include_once("class/db.php");

function maketable($tabname, $content)
{
	echo "<li>Create table " . config::DB_PREFIX . "$tabname... ";

	db::create_table($tabname, $content);

	$res = db::errorInfo();
	if($res[0] != "00000")
		echo '<span style="color:red">Error ' . $res[0] . '</span>: ' . $res[1] . ': ' . $res[2] . "</li>\n";
	else
		echo '<span style="color:green">Success.</span></li>' . "\n";
}

$mtime = microtime(true);
echo "<h2>Create tables</h2>\n";
echo "<ul>\n";

// Universe information - translations

maketable("translation", array(
	array("lang", "VARCHAR(8)", "NOT NULL"),
	array("id", "VARCHAR(8)", "NOT NULL"),
	array("name", "VARCHAR(64)"),
	array("descr", "TEXT"),
	array("PRIMARY KEY", "(lang, id)")));

// Universe information - celestial body types

maketable("celbtypes", array(
	array("sort", "SMALLINT", "NOT NULL", "PRIMARY KEY", "AUTO_INCREMENT"),
	array("id", "VARCHAR(8)", "NOT NULL", "UNIQUE"),
	array("start", "TINYINT")));

// Universe information - resources

maketable("resources", array(
	array("sort", "SMALLINT", "NOT NULL", "PRIMARY KEY", "AUTO_INCREMENT"),
	array("id", "VARCHAR(8)", "NOT NULL", "UNIQUE"),
	array("value", "REAL")));

// Universe information - buildings

maketable("buildings", array(
	array("sort", "SMALLINT", "NOT NULL", "PRIMARY KEY", "AUTO_INCREMENT"),
	array("id", "VARCHAR(8)", "NOT NULL", "UNIQUE"),
	array("time", "REAL"),
	array("factor", "REAL")));

// Universe information - researches

maketable("researches", array(
	array("sort", "SMALLINT", "NOT NULL", "PRIMARY KEY", "AUTO_INCREMENT"),
	array("id", "VARCHAR(8)", "NOT NULL", "UNIQUE"),
	array("time", "REAL"),
	array("factor", "REAL")));

// Universe information - buildings allowed on celb type

maketable("buildon", array(
	array("celb", "VARCHAR(8)", "NOT NULL"),
	array("building", "VARCHAR(8)", "NOT NULL")));

// Universe information - costs for buildings and research (leveled objects)

maketable("br_costs", array(
	array("brid", "VARCHAR(8)", "NOT NULL"),
	array("res", "VARCHAR(8)", "NOT NULL"),
	array("value", "REAL"),
	array("factor", "REAL"),
	array("PRIMARY KEY", "(brid, res)")));

// Universe information - costs for ships, defense and missiles (mass objects)

maketable("sdm_costs", array(
	array("sdmid", "VARCHAR(8)", "NOT NULL"),
	array("res", "VARCHAR(8)", "NOT NULL"),
	array("cost", "REAL"),
	array("PRIMARY KEY", "(sdmid, res)")));

// Universe information - production and storage formulas

maketable("prodstore", array(
	array("celb", "VARCHAR(8)", "NOT NULL"),
	array("res", "VARCHAR(8)", "NOT NULL"),
	array("production", "TEXT", "NOT NULL"),
	array("storage", "TEXT", "NOT NULL"),
	array("PRIMARY KEY", "(celb, res)")));

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

// Players - research

maketable("research_level", array(
	array("player", "BIGINT", "NOT NULL"),
	array("research", "VARCHAR(8)", "NOT NULL"),
	array("level", "SMALLINT"),
	array("PRIMARY KEY", "(player, research)")));

maketable("research_list", array(
	array("id", "BIGINT", "NOT NULL", "PRIMARY KEY", "AUTO_INCREMENT"),
	array("prev", "BIGINT"),
	array("next", "BIGINT"),
	array("player", "BIGINT", "NOT NULL"),
	array("galaxy", "SMALLINT", "NOT NULL"),
	array("sun", "SMALLINT", "NOT NULL"),
	array("orbit", "SMALLINT", "NOT NULL"),
	array("celb", "SMALLINT", "NOT NULL"),
	array("research", "VARCHAR(8)", "NOT NULL"),
	array("level", "SMALLINT"),
	array("finished", "BIGINT", "NOT NULL")));

// Sessions

maketable("sessions", array(
	array("id", "VARCHAR(32)", "NOT NULL", "PRIMARY KEY"),
	array("user", "BIGINT", "NOT NULL", "UNIQUE"),
	array("login", "BIGINT", "NOT NULL"),
	array("last", "BIGINT", "NOT NULL"),
	array("ipaddr", "VARCHAR(16)")));

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
	array("type", "VARCHAR(8)", "NOT NULL"),
	array("owner", "BIGINT"),
	array("name", "VARCHAR(32)"),
	array("PRIMARY KEY", "(galaxy, sun, orbit, celb)")));

// Celestial bodies - resources

maketable("celb_ress", array(
	array("galaxy", "SMALLINT", "NOT NULL"),
	array("sun", "SMALLINT", "NOT NULL"),
	array("orbit", "SMALLINT", "NOT NULL"),
	array("celb", "SMALLINT", "NOT NULL"),
	array("res", "VARCHAR(8)", "NOT NULL"),
	array("present", "REAL"),
	array("production", "REAL"),
	array("storage", "REAL"),
	array("time", "BIGINT", "NOT NULL"),
	array("PRIMARY KEY", "(galaxy, sun, orbit, celb, res)")));

// Celestial bodies - buildings

maketable("building_level", array(
	array("galaxy", "SMALLINT", "NOT NULL"),
	array("sun", "SMALLINT", "NOT NULL"),
	array("orbit", "SMALLINT", "NOT NULL"),
	array("celb", "SMALLINT", "NOT NULL"),
	array("building", "VARCHAR(8)", "NOT NULL"),
	array("level", "SMALLINT"),
	array("PRIMARY KEY", "(galaxy, sun, orbit, celb, building)")));

maketable("building_list", array(
	array("id", "BIGINT", "NOT NULL", "PRIMARY KEY", "AUTO_INCREMENT"),
	array("prev", "BIGINT"),
	array("next", "BIGINT"),
	array("galaxy", "SMALLINT", "NOT NULL"),
	array("sun", "SMALLINT", "NOT NULL"),
	array("orbit", "SMALLINT", "NOT NULL"),
	array("celb", "SMALLINT", "NOT NULL"),
	array("building", "VARCHAR(8)", "NOT NULL"),
	array("level", "SMALLINT"),
	array("finished", "BIGINT", "NOT NULL")));

$mtime = microtime(true) - $mtime;
echo "<li>Time taken: $mtime seconds.</li>\n";
echo "</ul>\n";
flush();
?>
