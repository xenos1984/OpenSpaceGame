<?php
include_once("class/player.php");
include_once("class/session.php");
include_once("class/celb.php");

if($_REQUEST["nick"] == "")
	die("Empty user name.");

if(preg_match("/[^\w\s]/", $_REQUEST["nick"]))
	die("User name contains invalid characters. Only a-z, A-Z, 0-9, _ and whitespace are allowed.");

if(!preg_match("/\S/", $_REQUEST["nick"]))
	die("User name contains only whitespace.");

if(player::find($_REQUEST["nick"]))
	die("User name already exists.");

if($_REQUEST["pass1"] != $_REQUEST["pass2"])
	die("Passwords don't match.");

if(!($uid = player::create($_REQUEST["nick"], $_REQUEST["pass1"], $_REQUEST["email"])))
	die("Create user failed.");

$home = celb::randfree('...');
$home->owner = $uid->id;

$sid = session::create($uid->id);

if(!$sid)
	die("Failed to create session ID.");

setcookie('session', $sid->id);
setcookie('user', $uid->nick);

header("Location: view.php?view=overview&celb={$home->coords}");
?>
