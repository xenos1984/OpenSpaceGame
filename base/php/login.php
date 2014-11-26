<?php
	include_once("class/player.php");

	$uid = player::checkpass($_REQUEST['nick'], $_REQUEST['pass']);

	if(!$uid)
		die("Invalid password or username!");

	die("Test: User $uid logged in.");
 ?>
