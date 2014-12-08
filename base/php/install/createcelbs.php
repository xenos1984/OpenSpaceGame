<?php
include_once("config.php");
include_once("class/db.php");

echo "<h2>Create celestial bodies</h2>\n";

$ss = $so = $sc = $to = $tc = 0;
$types = db::select_all('celbtypes', array(), array('id'));
$allok = true;

for($g = 1; $g <= config::CELB_GALAXIES; $g++)
{
	for($s = 1; $s <= config::CELB_SUNS; $s++)
	{
		$ss += db::insert('suns', array(
			'galaxy' => $g,
			'sun' => $s,
			'posX' => mt_rand() / mt_getrandmax(),
			'posY' => mt_rand() / mt_getrandmax(),
			'posZ' => mt_rand() / mt_getrandmax()));

		$no = mt_rand(config::CELB_MINORBITS, config::CELB_MAXORBITS);
		$to += $no;

		for($o = 1; $o <= $no; $o++)
		{
			$so += db::insert('orbits', array(
				'galaxy' => $g,
				'sun' => $s,
				'orbit' => $o,
				'radius' => 0.4 + 0.3 * pow(2, $o),
				'phase' => 2 * M_PI * mt_rand() / mt_getrandmax()));

			$nc = mt_rand(1, config::CELB_MAXCELBS);
			$tc += $nc;

			for($c = 1; $c <= $nc; $c++)
			{
				$sc += db::insert('celbs', array(
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
echo "<li>Total galaxies: " . config::CELB_GALAXIES . ".</li>\n";
echo "<li style=\"color:" . ($ss == config::CELB_GALAXIES * config::CELB_SUNS ? "green" : "red") . "\">Total solar systems: " . (config::CELB_GALAXIES * config::CELB_SUNS) . "; $ss successfully created.</li>\n";
echo "<li style=\"color:" . ($so == $to ? "green" : "red") . "\">Total orbits: $to; $so  successfully created.</li>\n";
echo "<li style=\"color:" . ($sc == $tc ? "green" : "red") . "\">Total celestial bodies: $tc; $sc successfully created.</li>\n";
echo "</ul>\n";
flush();
?>
