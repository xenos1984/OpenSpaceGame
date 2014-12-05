<?php
include_once("class/session.php");
include_once("class/player.php");
include_once("class/celb.php");

class view
{
	protected $xmldoc;
	protected $root;
	protected $celb;
	protected $player;
	protected $session;

	public function __construct()
	{
		$this->xmldoc = new DOMDocument();
		$this->xmldoc->version = "1.0";
		$this->xmldoc->encoding = "utf8";
		$this->xmldoc->standalone = false;
		$this->xmldoc->resolveExternals = true;
		$this->xmldoc->substituteEntities = true;
		$this->xmldoc->validateOnParse = true;
		$this->root = $this->xmldoc->createElement('view');
		$this->root = $this->xmldoc->appendChild($this->root);

		$this->session = session::byid($_COOKIE['session']);
		$this->player = player::byid($this->session->user);
		$this->celb = celb::bycoord($_REQUEST['celb']);
	}
}
?>
