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
	array("name", "VARCHAR(64)", "NOT NULL"),
	array("description", "TEXT")));

echo "</ul>\n";

?>
