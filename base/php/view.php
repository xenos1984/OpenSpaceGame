<?php
include_once('class/session.php');

if(!array_key_exists('session', $_REQUEST))
	die("Session ID missing.");

$sid = session::byid($_REQUEST['session']);
if(!$sid)
	die("Invalid session ID.");

print_r($sid);
die();

// Check whether the requested XML data generator exists.
if(!file_exists("views/" . $_REQUEST['view'] . ".php"))
	die("View file " . $_REQUEST['view'] . ".php not found.");

// Include it and generate XML data.
include("views/" . $_REQUEST['view'] . ".php");
$xml = $xmlgen->generateXML();
if(!$xml->schemaValidate("../xml/view.xsd", LIBXML_SCHEMA_CREATE))
	die("ERROR: Invalid XML generated!");

// Load XSLT style.
$xsl = new DOMDocument;
$xsl->resolveExternals = true;
$xsl->substituteEntities = true;
$xsl->load("../../styles/xslt/default/" . $_REQUEST['view'] . ".xsl");

// Transform data to HTML.
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl);
$output = $proc->transformToXML($xml);

// Output data.
header("Content-Type: text/html; charset=UTF-8");
echo $output;
?>
