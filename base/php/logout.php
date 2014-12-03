<?php
include_once('class/session.php');

// Check for session cookie.
if(!array_key_exists('session', $_COOKIE))
	die("Session ID missing.");

// Check if session exists and get session object.
$sid = session::byid($_COOKIE['session']);
if(!$sid)
	die("Invalid session ID.");

$sid->delete();

setcookie('session', '', -1);
header("Location: index.php");
?>
