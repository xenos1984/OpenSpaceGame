<?php
include_once("config.php");
include_once("class/db.php");

echo "<h2>Create celestial bodies</h2>\n";

$to = $tc = 0;

for($g = 1; $g <= $celbinfo['galaxies']; $g++)
{
	for($s = 1; $s <= $celbinfo['suns']; $s++)
	{
		db::insert('suns', array(
			'galaxy' => $g,
			'sun' => $s,
			'posX' => mt_rand() / mt_getrandmax(),
			'posY' => mt_rand() / mt_getrandmax(),
			'posZ' => mt_rand() / mt_getrandmax()));

		$no = mt_rand($celbinfo['minorbits'], $celbinfo['maxorbits']);
		$to += $no;

		for($o = 1; $o <= $no; $o++)
		{
			db::insert('orbits', array(
				'galaxy' => $g,
				'sun' => $s,
				'orbit' => $o,
				'radius' => 0.4 + 0.3 * pow(2, $o),
				'phase' => 2 * M_PI * mt_rand() / mt_getrandmax()));

			$nc = mt_rand(1, $celbinfo['celbs']);
			$tc += $nc;

			for($c = 1; $c <= $nc; $c++)
			{
				db::insert('celbs', array(
					'galaxy' => $g,
					'sun' => $s,
					'orbit' => $o,
					'celb' => $c,
					'type' => '...',
					'owner' => 0,
					'name' => ''));
			}
		}
	}
}

echo "<ul>\n";
echo "<li>Total galaxies: {$celbinfo['galaxies']}</li>\n";
echo "<li>Total solar systems: " . ($celbinfo['galaxies'] * $celbinfo['suns']) . "</li>\n";
echo "<li>Total orbits: $to</li>\n";
echo "<li>Total celestial bodies: $tc</li>\n";
echo "</ul>\n";
?>
