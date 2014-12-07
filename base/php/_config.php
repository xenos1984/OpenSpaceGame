<?php
class config
{
	// Database settings

	// Username
	const DB_USER = '';
	// Password
	const DB_PASSWORD = '';
	// PDO database string
	const DB_DATABASE = '';
	// Table name prefix
	const DB_PREFIX = '';

	// Settings for celestial bodies

	// Number of galaxies
	const CELB_GALAXIES = 9;
	// Number of solar systems per galaxy
	const CELB_SUNS = 999;
	// Minimum number of orbits per solar system
	const CELB_MINORBITS = 8;
	// Maximum number of orbits per solar system
	const CELB_MAXORBITS = 12;
	// Maximum number of celestial bodies per orbit
	const CELB_MAXCELBS = 3;

	// Default settings

	// Default language
	const DEF_LANG = 'en-US';
	// Default CSS style
	const DEF_CSS = '../../styles/css/default/';
	// Default XSLT style
	const DEF_XSLT = '../../styles/xslt/default/';
	// Default name for home planet
	const DEF_HOME = 'Home planet';
	// Default name for colony
	const DEF_COLONY = 'Colony';
}
?>
