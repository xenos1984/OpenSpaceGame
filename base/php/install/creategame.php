<?php
include_once("config.php");
include_once("class/db.php");
include_once("class/trans.php");

echo "<h3>Create universe configuration</h3>\n";

$xml = new DOMDocument;
$xml->resolveExternals = true;
$xml->substituteEntities = true;
$xml->load("../../games/demo.xml");

if(!$xml->schemaValidate("../xml/game.xsd"))
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

echo "</ul>\n";

?>
