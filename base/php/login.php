<?php
include_once("class/player.php");
include_once("class/session.php");
include_once("class/celb.php");

$uid = player::checkpass($_REQUEST['nick'], $_REQUEST['pass']);
if(!$uid)
	die("Invalid password or username!");

$sid = session::create($uid->id);
if(!$sid)
	die("Failed to create session ID.");

$celbs = celb::byowner($uid->id);
if((!$celbs) || (!count($celbs)))
	die("Could not find celestial bodies.");

setcookie('session', $sid->id);
setcookie('user', $uid->nick);

header("Location: view.php?view=buildings&celb=" . $celbs[0]->coords);
?>
