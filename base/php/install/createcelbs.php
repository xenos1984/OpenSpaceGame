<?php
include_once("config.php");
include_once("class/db.php");

echo "<h2>Create celestial bodies</h2>\n";

$to = $tc = 0;
$types = db::select_all('celbtypes', array(), array('id'));

for($g = 1; $g <= config::CELB_GALAXIES; $g++)
{
	for($s = 1; $s <= config::CELB_SUNS; $s++)
	{
		db::insert('suns', array(
			'galaxy' => $g,
			'sun' => $s,
			'posX' => mt_rand() / mt_getrandmax(),
			'posY' => mt_rand() / mt_getrandmax(),
			'posZ' => mt_rand() / mt_getrandmax()));

		$no = mt_rand(config::CELB_MINORBITS, config::CELB_MAXORBITS);
		$to += $no;

		for($o = 1; $o <= $no; $o++)
		{
			db::insert('orbits', array(
				'galaxy' => $g,
				'sun' => $s,
				'orbit' => $o,
				'radius' => 0.4 + 0.3 * pow(2, $o),
				'phase' => 2 * M_PI * mt_rand() / mt_getrandmax()));

			$nc = mt_rand(1, config::CELB_MAXCELBS);
			$tc += $nc;

			for($c = 1; $c <= $nc; $c++)
			{
				db::insert('celbs', array(
					'galaxy' => $g,
					'sun' => $s,
					'orbit' => $o,
					'celb' => $c,
					'type' => $types[mt_rand(0, count($types) - 1)]['id'],
					'owner' => 0,
					'name' => ''));
			}
		}
	}
}

echo "<ul>\n";
echo "<li>Total galaxies: " . config::CELB_GALAXIES . "</li>\n";
echo "<li>Total solar systems: " . (config::CELB_GALAXIES * config::CELB_SUNS) . "</li>\n";
echo "<li>Total orbits: $to</li>\n";
echo "<li>Total celestial bodies: $tc</li>\n";
echo "</ul>\n";
flush();
?>
