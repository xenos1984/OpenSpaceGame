<?php
include_once("class/player.php");
include_once("class/session.php");
include_once("class/celb.php");

if($_REQUEST['nick'] == "")
	die("Empty user name.");

if(preg_match("/[^\w\s]/", $_REQUEST['nick']))
	die("User name contains invalid characters. Only a-z, A-Z, 0-9, _ and whitespace are allowed.");

if(!preg_match("/\S/", $_REQUEST['nick']))
	die("User name contains only whitespace.");

if(player::find($_REQUEST['nick']))
	die("User name already exists.");

if($_REQUEST['pass1'] != $_REQUEST['pass2'])
	die("Passwords don't match.");

if($_REQUEST['home'] == "")
	die("Empty home name.");

if(preg_match("/[^\w\s]/", $_REQUEST['home']))
	die("Home name contains invalid characters. Only a-z, A-Z, 0-9, _ and whitespace are allowed.");

if(!preg_match("/\S/", $_REQUEST['home']))
	die("Home name contains only whitespace.");

if(!($home = celb::randfree($_REQUEST['celbtype'])))
	die("No free home planet of type {$_REQUEST['celbtype']} found.");

if(!($uid = player::create($_REQUEST['nick'], $_REQUEST['pass1'], $_REQUEST['email'])))
	die("Create user failed.");

if(!$home->sethome($uid->id, $_REQUEST['home']))
	die("Set home planet failed.");

if(!($sid = session::create($uid->id)))
	die("Failed to create session ID.");

setcookie('session', $sid->id);
setcookie('user', $uid->nick);

header("Location: view.php?view=buildings&celb={$home->coords}");
?>
