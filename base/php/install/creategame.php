<?php
include_once("config.php");
include_once("class/db.php");
include_once("class/formula.php");

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
	$lang = $language->getAttribute('lang');
	$translations = $xpath->query("translation", $language);
	foreach($translations as $translation)
	{
		$id = $translation->getAttribute('id');
		$name = $translation->getAttribute('name');
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

echo "<li>Create celestial body types...<ul>";
$allok = true;
$celbs = $xpath->query("/game/celbtype");
foreach($celbs as $celb)
{
	$id = $celb->getAttribute('id');
	$start = (($celb->getAttribute('start') == '1') || ($celb->getAttribute('start') == 'true') ? 1 : 0);

	$ok = db::insert('celbtypes', array(
		'id' => $id,
		'start' => $start));
	echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">$id($start)</li>\n";
	$allok &= $ok;
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
	$id = $res->getAttribute('id');
	$value = $res->getAttribute('value');

	$ok = db::insert('resources', array(
		'id' => $id,
		'value' => $value));
	echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">$id($value)</li>\n";
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
	$id = $building->getAttribute('id');
	$time = $building->getAttribute('time');
	$factor = $building->getAttribute('factor');

	$ok = db::insert('buildings', array(
		'id' => $id,
		'time' => $time,
		'factor' => $factor));
	echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">$id($time, $factor)<ul>";
	$allok &= $ok;

	$buildons = $xpath->query("buildon", $building);
	foreach($buildons as $buildon)
	{
		$celb = $buildon->getAttribute('id');

		$ok = db::insert('buildon', array(
			'building' => $id,
			'celb' => $celb));
		echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">&rarr; $celb</li>\n";
		$allok &= $ok;
	}

	$costs = $xpath->query("cost", $building);
	foreach($costs as $cost)
	{
		$res = $cost->getAttribute('id');
		$value = $cost->getAttribute('value');
		$factor = $cost->getAttribute('factor');

		$ok = db::insert('br_costs', array(
			'brid' => $id,
			'res' => $res,
			'value' => $value,
			'factor' => $factor));
		echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">&larr; $res($value, $factor)</li>\n";
		$allok &= $ok;
	}
	echo "</ul></li>\n";
}
echo '</ul>... ';
if($allok)
	echo '<span style="color:green">Success.</span></li>' . "\n";
else
	echo '<span style="color:red">Errors.</span></li>' . "\n";

echo "<li>Create researches...<ul>";
$allok = true;
$researches = $xpath->query("/game/research");
foreach($researches as $research)
{
	$id = $research->getAttribute('id');
	$time = $research->getAttribute('time');
	$factor = $research->getAttribute('factor');

	$ok = db::insert('researches', array(
		'id' => $id,
		'time' => $time,
		'factor' => $factor));
	echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">$id($time, $factor)<ul>";
	$allok &= $ok;

	$costs = $xpath->query("cost", $research);
	foreach($costs as $cost)
	{
		$res = $cost->getAttribute('id');
		$value = $cost->getAttribute('value');
		$factor = $cost->getAttribute('factor');

		$ok = db::insert('br_costs', array(
			'brid' => $id,
			'res' => $res,
			'value' => $value,
			'factor' => $factor));
		echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">&larr; $res($value, $factor)</li>\n";
		$allok &= $ok;
	}
	echo "</ul></li>\n";
}
echo '</ul>... ';
if($allok)
	echo '<span style="color:green">Success.</span></li>' . "\n";
else
	echo '<span style="color:red">Errors.</span></li>' . "\n";

echo "<li>Create resource productions and storages...<ul>";
$allok = true;
$cts = $xpath->query("/game/celbtype");
$ress = $xpath->query("/game/resource");
$zero = serialize(new formula_constant(0));
foreach($cts as $ct)
{
	foreach($ress as $res)
	{
		$allok &= db::insert('prodstore', array(
			'celb' => $ct->getAttribute('id'),
			'res' => $res->getAttribute('id'),
			'production' => $zero,
			'storage' => $zero));
	}
}
$prods = $xpath->query("/game/production");
foreach($prods as $prod)
{
	$res = $prod->getAttribute('resource');
	$ct = $prod->getAttribute('celbtype');
	$fn = $xpath->query("./child::*", $prod);
	$formula = formula::fromxml($fn->item(0));
	$ok = db::update('prodstore', array('celb' => $ct, 'res' => $res), array('production' => serialize($formula)));
	echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">$ct &rarr; $res</li>\n";
	$allok &= $ok;
}
$stores = $xpath->query("/game/storage");
foreach($stores as $store)
{
	$res = $store->getAttribute('resource');
	$ct = $store->getAttribute('celbtype');
	$fn = $xpath->query("./child::*", $store);
	$formula = formula::fromxml($fn->item(0));
	$ok = db::update('prodstore', array('celb' => $ct, 'res' => $res), array('storage' => serialize($formula)));
	echo "<li style=\"color:" . ($ok ? 'green' : 'red') . "\">$ct &larr; $res</li>\n";
	$allok &= $ok;
}
echo '</ul>... ';
if($allok)
	echo '<span style="color:green">Success.</span></li>' . "\n";
else
	echo '<span style="color:red">Errors.</span></li>' . "\n";

echo "</ul>\n";
flush();
?>
