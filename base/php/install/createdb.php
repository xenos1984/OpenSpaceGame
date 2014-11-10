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

maketable("build_on", array(
	array("celb", "VARCHAR(16)", "NOT NULL"),
	array("building", "VARCHAR(16)", "NOT NULL")));

maketable("building_costs", array(
	array("building", "VARCHAR(16)", "NOT NULL"),
	array("res", "VARCHAR(16)", "NOT NULL"),
	array("cost", "REAL"),
	array("factor", "REAL"),
	array("PRIMARY KEY", "(building, res)")));

maketable("building_storage", array(
	array("building", "VARCHAR(16)", "NOT NULL"),
	array("res", "VARCHAR(16)", "NOT NULL"),
	array("value", "REAL"),
	array("power", "REAL"),
	array("PRIMARY KEY", "(building, res)")));

maketable("building_product", array(
	array("building", "VARCHAR(16)", "NOT NULL"),
	array("res", "VARCHAR(16)", "NOT NULL"),
	array("value", "REAL"),
	array("power", "REAL"),
	array("PRIMARY KEY", "(building, res)")));

echo "</ul>\n";

?>
