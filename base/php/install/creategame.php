<?php
include_once("config.php");
include_once("class/db.php");
include_once("class/trans.php");
include_once("class/resource.php");
include_once("class/building.php");

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

		trans::create($lang, $id, $name, $descr);
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

	resource::create($id, $value, $product, $storage);
}
echo '<span style="color:lime">Success.</span></li>' . "\n";

echo "<li>Create buildings... ";
$buildings = $xpath->query("/game/building");
foreach($buildings as $building)
{
	$id = $building->getAttribute("id");
	$time = $building->getAttribute("time");
	$factor = $building->getAttribute("factor");

	building::create($id, $time, $factor);/*
	$build = new building($building_ids[$idName]);

	$buildons = $xpath->query("buildon", $building);
	foreach($buildons as $buildon)
	{
		$orbType = $buildon->getAttribute("id");

		$build->create_build_on($orb_ids[$orbType]);
	}

	$costs = $xpath->query("cost", $building);
	foreach($costs as $cost)
	{
		$ressId = $cost->getAttribute("id");
		$value = $cost->getAttribute("value");
		$factor = $cost->getAttribute("factor");

		$build->create_cost($ress_ids[$ressId], $value, $factor);
	}

	$products = $xpath->query("product", $building);
	foreach($products as $product)
	{
		$ressId = $product->getAttribute("id");
		$value = $product->getAttribute("value") / 3600.0;
		$power = $product->getAttribute("power");

		$build->create_product($ress_ids[$ressId], $value, $power);
	}

	$storages = $xpath->query("storage", $building);
	foreach($storages as $storage)
	{
		$ressId = $storage->getAttribute("id");
		$value = $storage->getAttribute("value");
		$power = $storage->getAttribute("power");

		$build->create_storage($ress_ids[$ressId], $value, $power);
	}*/
}
echo '<span style="color:lime">Success.</span></li>' . "\n";

echo "</ul>\n";
?>
