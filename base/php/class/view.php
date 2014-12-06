<?php
include_once("class/session.php");
include_once("class/player.php");
include_once("class/celb.php");
include_once("class/resource.php");
include_once("class/trans.php");

class view
{
	protected $xmldoc;
	protected $root;
	protected $celb;
	protected $player;
	protected $session;

	public function __construct()
	{
		$this->session = session::byid($_COOKIE['session']);
		$this->player = player::byid($this->session->user);
		$this->celb = celb::bycoord($_REQUEST['celb']);

		$this->xmldoc = new DOMDocument();
		$this->xmldoc->version = '1.0';
		$this->xmldoc->encoding = 'utf8';
		$this->xmldoc->standalone = false;
		$this->xmldoc->resolveExternals = true;
		$this->xmldoc->substituteEntities = true;
		$this->xmldoc->validateOnParse = true;
		$this->root = $this->xmldoc->createElement('view');
		$this->root = $this->xmldoc->appendChild($this->root);

		$this->root->setAttribute('servertime', time());
		$this->root->setAttribute('lang', $this->player->lang);
		$this->root->setAttribute('css', $this->player->css);
		$this->root->setAttribute('xslt', $this->player->xslt);

		$posxml = $this->xmldoc->createElement('positions');
		$posxml = $this->root->appendChild($posxml);
		$posxml->setAttribute('curg', $this->celb->galaxy);
		$posxml->setAttribute('curs', $this->celb->sun);
		$posxml->setAttribute('curo', $this->celb->orbit);
		$posxml->setAttribute('curc', $this->celb->celb);

		$celbs = celb::byowner($this->player->id);
		foreach($celbs as $celb)
		{
			$px = $this->xmldoc->createElement('position');
			$px = $posxml->appendChild($px);

			$px->setAttribute('name', $celb->name);
			$px->setAttribute('galaxy', $celb->galaxy);
			$px->setAttribute('sun', $celb->sun);
			$px->setAttribute('orbit', $celb->orbit);
			$px->setAttribute('celb', $celb->celb);
			$px->setAttribute('tid', '...');
			$px->setAttribute('tname', '...');
		}

		$resxml = $this->xmldoc->createElement('resources');
		$resxml = $this->root->appendChild($resxml);

		$ress = resource::all();
		foreach($ress as $res)
		{
			$rx = $this->xmldoc->createElement('resource');
			$rx = $resxml->appendChild($rx);

			$tr = trans::find($this->player->lang, $res->id);
			$rx->setAttribute('name', $tr->name);
			$rx->setAttribute('id', $res->name);
			$rx->setAttribute('present', 0);
			$rx->setAttribute('storage', 0);
			$rx->setAttribute('product', 0);
		}
	}
}
?>
