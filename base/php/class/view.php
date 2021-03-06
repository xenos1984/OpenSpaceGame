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
			$px->setAttribute('tid', $celb->type);
			$tr = trans::find($this->player->lang, $celb->type);
			$px->setAttribute('tname', $tr->name);
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
			$rx->setAttribute('id', $res->id);
			$value = $this->celb->resvalues($res->id);
			$rx->setAttribute('present', (int)$value['present']);
			$rx->setAttribute('storage', (int)$value['storage']);
			$rx->setAttribute('production', (int)$value['production']);
		}
	}

	public final function validate()
	{
		return($this->xmldoc->schemaValidate("../xml/view.xsd", LIBXML_SCHEMA_CREATE));
	}

	public final function output()
	{
/*		header("Content-Type: text/xml; charset=UTF-8");
		echo $this->xmldoc->saveXML();
		die();
*/
		header("Content-Type: text/html; charset=UTF-8");

		// Load XSLT style.
		$xsl = new DOMDocument;
		$xsl->resolveExternals = true;
		$xsl->substituteEntities = true;
		$xsl->load($this->player->xslt . $_REQUEST['view'] . ".xsl");

		// Transform data to HTML.
		$proc = new XSLTProcessor;
		$proc->importStyleSheet($xsl);
		echo $proc->transformToXML($this->xmldoc);
	}
}
?>
