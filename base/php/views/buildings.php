<?php

class buildingsXML
{
	public function generateXML()
	{
		$time = time();
		$xml = new DomDocument();
		$xml->loadXML(<<<EOD
<?xml version="1.0" encoding="utf-8"?>
<view lang="en" servertime="$time" session="1234567890abcdef" css="../../styles/css/default/">
<positions curg="1" curs="1" curo="1" curc="1">
<position name="Thanata" galaxy="1" solsys="1" orbit="1" celb="1" tid="wat" tname="Water planet"/>
<position name="Jolanthea" galaxy="2" solsys="2" orbit="2" celb="2" tid="gas" tname="Gas planet"/>
<position name="Pyrosia" galaxy="3" solsys="3" orbit="3" celb="3" tid="sto" tname="Stone planet"/>
</positions>
<resources>
<resource id="tit" name="Titanium" present="3600" storage="10000" produced="100"/>
<resource id="sil" name="Silicon" present="2000" storage="4000" produced="40"/>
<resource id="hyd" name="Hydrogen" present="1500" storage="1000" produced="10"/>
</resources>
<buildings>
<building id="titm" name="Titanium mine" time="3600" curlevel="2" planlevel="3">
<cost id="tit" value="1800"/>
<cost id="sil" value="900"/>
</building>
</buildings>
</view>
EOD
);
		return $xml;
	}
}

$xmlgen = new buildingsXML();

?>
