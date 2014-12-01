<?php
include_once('class/session.php');
include_once('class/celb.php');

// Check for session cookie.
if(!array_key_exists('session', $_COOKIE))
	die("Session ID missing.");

// Check if session exists and get session object.
$sid = session::byid($_COOKIE['session']);
if(!$sid)
	die("Invalid session ID.");

// Check whether celestial body is given.
if(!array_key_exists('celb', $_REQUEST))
	die("Celestial body missing.");

// Check whether celestial body exists.
$celb = celb::bycoord($_REQUEST['celb']);
if(!$celb)
	die("Invalid celestial body.");

// Check whether session owner owns the given celestial body.
if($celb->owner != $sid->user)
	die("Celestial body does not belong to player.");

// Check whether we have a view type given.
if(!array_key_exists('view', $_REQUEST))
	die("View type missing.");

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
