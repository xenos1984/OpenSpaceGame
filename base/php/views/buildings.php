<?php
include_once("class/view.php");
include_once("class/building.php");

class viewxml extends view
{
	public function __construct()
	{
		parent::__construct();

		$bldxml = $this->xmldoc->createElement('buildings');
		$bldxml = $this->root->appendChild($bldxml);
		$buildings = building::all();
		foreach($buildings as $building)
		{
			$bx = $this->xmldoc->createElement('building');
			$bx = $bldxml->appendChild($bx);

			$tr = trans::find($this->player->lang, $building->id);
			$bx->setAttribute('id', $building->id);
			$bx->setAttribute('name', $tr->name);
			$bx->setAttribute('time', $building->time);
			$bx->setAttribute('curlevel', 0);
			$bx->setAttribute('planlevel', 0);

			$costs = $building->costs();
			foreach($costs as $cost)
			{
				$cx = $this->xmldoc->createElement('cost');
				$cx = $bx->appendChild($cx);
				$cx->setAttribute('id', $cost['res']);
				$cx->setAttribute('value', $cost['value']);
			}

			$dx = $this->xmldoc->createElement('description');
			$dx = $bx->appendChild($dx);
			$dt = $this->xmldoc->createTextNode($tr->descr);
			$dt = $dx->appendChild($dt);
		}
	}
}
?>
