<?php
include_once("config.php");
include_once("class/db.php");

echo "<h3>Create universe configuration</h3>\n";

$xml = new DOMDocument;
$xml->resolveExternals = true;
$xml->substituteEntities = true;
$xml->load("../../games/demo.xml");

if(!$xml->schemaValidate("../xml/game.xsd", LIBXML_SCHEMA_CREATE))
	echo "<p style=\"color:red\">Error: invalid game file.</p>\n";

$xpath = new DOMXPath($xml);

echo "<ul>\n";

echo "<li>Create translations... ";
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

		db::create_object('translation', array(
			'lang' => $lang,
			'id' => $id,
			'name' => $name,
			'descr' => $descr));
	}
}
echo '<span style="color:lime">Success.</span></li>' . "\n";

echo "<li>Create resources... ";
$ress = $xpath->query("/game/resource");
foreach($ress as $res)
{
	$id = $res->getAttribute("id");
	$value = $res->getAttribute("value");
	$product = $res->getAttribute("product") / 3600.0;
	$storage = $res->getAttribute("storage");

	db::create_object('resources', array(
		'id' => $id,
		'value' => $value,
		'product' => $product,
		'storage' => $storage));
}
echo '<span style="color:lime">Success.</span></li>' . "\n";

echo "<li>Create buildings... ";
$buildings = $xpath->query("/game/building");
foreach($buildings as $building)
{
	$id = $building->getAttribute("id");
	$time = $building->getAttribute("time");
	$factor = $building->getAttribute("factor");

	db::create_object('buildings', array(
		'id' => $id,
		'time' => $time,
		'factor' => $factor));

	$buildons = $xpath->query("buildon", $building);
	foreach($buildons as $buildon)
	{
		$celb = $buildon->getAttribute("id");

		db::create_object('buildon', array(
			'building' => $id,
			'celb' => $celb));
	}

	$costs = $xpath->query("cost", $building);
	foreach($costs as $cost)
	{
		$res = $cost->getAttribute("id");
		$value = $cost->getAttribute("value");
		$factor = $cost->getAttribute("factor");

		db::create_object('br_costs', array(
			'brid' => $id,
			'res' => $res,
			'value' => $value,
			'factor' => $factor));
	}

	$products = $xpath->query("product", $building);
	foreach($products as $product)
	{
		$res = $product->getAttribute("id");
		$value = $product->getAttribute("value") / 3600.0;
		$power = $product->getAttribute("power");

		db::create_object('products', array(
			'building' => $id,
			'res' => $res,
			'value' => $value,
			'power' => $power));
	}

	$storages = $xpath->query("storage", $building);
	foreach($storages as $storage)
	{
		$res = $storage->getAttribute("id");
		$value = $storage->getAttribute("value");
		$power = $storage->getAttribute("power");

		db::create_object('storages', array(
			'building' => $id,
			'res' => $res,
			'value' => $value,
			'power' => $power));
	}
}
echo '<span style="color:lime">Success.</span></li>' . "\n";

echo "</ul>\n";
?>
