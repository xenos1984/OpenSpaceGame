<?php
include_once("config.php");
include_once("class/db.php");

echo "<h2>Create universe configuration</h2>\n";

$xml = new DOMDocument;
$xml->resolveExternals = true;
$xml->substituteEntities = true;
$xml->load("../../games/demo.xml");

if(!$xml->schemaValidate("../xml/game.xsd", LIBXML_SCHEMA_CREATE))
	echo "<p style=\"color:red\">Error: invalid game file.</p>\n";

$xpath = new DOMXPath($xml);

echo "<ul>\n";

echo "<li>Create translations...<ul>";
$allok = true;
$languages = $xpath->query("/game/translations");
foreach($languages as $language)
{
	$lang = $language->getAttribute("lang");
	$translations = $xpath->query("translation", $language);
	foreach($translations as $translation)
	{
		$id = $translation->getAttribute("id");
		$name = $translation->getAttribute("name");
		$descr = $xpath->evaluate("text()", $translation)->item(0)->wholeText;

		$ok = db::insert('translation', array(
			'lang' => $lang,
			'id' => $id,
			'name' => $name,
			'descr' => $descr));
		echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">$id:$lang &rarr; $name</li>\n";
		$allok &= $ok;
	}
}
echo '</ul>... ';
if($allok)
	echo '<span style="color:green">Success.</span></li>' . "\n";
else
	echo '<span style="color:red">Errors.</span></li>' . "\n";

echo "<li>Create resources...<ul>";
$allok = true;
$ress = $xpath->query("/game/resource");
foreach($ress as $res)
{
	$id = $res->getAttribute("id");
	$value = $res->getAttribute("value");
	$product = $res->getAttribute("product") / 3600.0;
	$storage = $res->getAttribute("storage");

	$ok = db::insert('resources', array(
		'id' => $id,
		'value' => $value,
		'product' => $product,
		'storage' => $storage));
	echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">$id($value, $product, $storage)</li>\n";
	$allok &= $ok;
}
echo '</ul>... ';
if($allok)
	echo '<span style="color:green">Success.</span></li>' . "\n";
else
	echo '<span style="color:red">Errors.</span></li>' . "\n";

echo "<li>Create buildings...<ul>";
$allok = true;
$buildings = $xpath->query("/game/building");
foreach($buildings as $building)
{
	$id = $building->getAttribute("id");
	$time = $building->getAttribute("time");
	$factor = $building->getAttribute("factor");

	$ok = db::insert('buildings', array(
		'id' => $id,
		'time' => $time,
		'factor' => $factor));
	echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">$id($time, $factor)<ul>";
	$allok &= $ok;

	$buildons = $xpath->query("buildon", $building);
	foreach($buildons as $buildon)
	{
		$celb = $buildon->getAttribute("id");

		$ok = db::insert('buildon', array(
			'building' => $id,
			'celb' => $celb));
		echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">&rarr; $celb</li>\n";
		$allok &= $ok;
	}

	$costs = $xpath->query("cost", $building);
	foreach($costs as $cost)
	{
		$res = $cost->getAttribute("id");
		$value = $cost->getAttribute("value");
		$factor = $cost->getAttribute("factor");

		$ok = db::insert('br_costs', array(
			'brid' => $id,
			'res' => $res,
			'value' => $value,
			'factor' => $factor));
		echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">&larr; $res($value, $factor)</li>\n";
		$allok &= $ok;
	}

	$products = $xpath->query("product", $building);
	foreach($products as $product)
	{
		$res = $product->getAttribute("id");
		$value = $product->getAttribute("value") / 3600.0;
		$power = $product->getAttribute("power");

		$ok = db::insert('products', array(
			'building' => $id,
			'res' => $res,
			'value' => $value,
			'power' => $power));
		echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">&uarr; $res($value, $power)</li>\n";
		$allok &= $ok;
	}

	$storages = $xpath->query("storage", $building);
	foreach($storages as $storage)
	{
		$res = $storage->getAttribute("id");
		$value = $storage->getAttribute("value");
		$power = $storage->getAttribute("power");

		$ok = db::insert('storages', array(
			'building' => $id,
			'res' => $res,
			'value' => $value,
			'power' => $power));
		echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">&darr; $res($value, $power)</li>\n";
		$allok &= $ok;
	}
	echo "</ul></li>\n";
}
echo '</ul>... ';
if($allok)
	echo '<span style="color:green">Success.</span></li>' . "\n";
else
	echo '<span style="color:red">Errors.</span></li>' . "\n";

echo "</ul>\n";
?>
