<?php
include_once("class/player.php");
include_once("class/session.php");

$uid = player::checkpass($_REQUEST['nick'], $_REQUEST['pass']);

if(!$uid)
	die("Invalid password or username!");

$sid = session::create($uid->id);

if(!$sid)
	die("Failed to create session ID.");

setcookie('session', $sid->id);
setcookie('user', $uid->nick);

header("Location: view.php?view=overview");
?>
