<?php
class XmlHelper
{
	function XmlHelper()
	{

	}

	public static function getElementsByTagName(DOMDocument $dom, $nodeName)
	{
		return $dom->getElementsByTagName($nodeName);
	}

	public static function getXmlNodeText(DOMDocument $dom, $nodeName)
	{
		$nodeList = $dom->getElementsByTagName($nodeName);

		if(!$nodeList)
		{
			return null;
		}

		$item = $nodeList->item(0);

		if(!$item)
		{
			return null;
		}

		return $item->textContent;
	}

	public static function buildXmlCdataNode(DOMDocument $dom, $nodeName, $nodeValue = null)
	{
		$node = $dom->createElement($nodeName);
		$node->appendChild($dom->createCDATASection($nodeValue));
		return $node;
	}

	public static function createDomDocument()
	{
		return new DOMDocument("1.0", "utf-8");
	}
    
    public static function createExceptionXml(Exception $ex, $showTrace = false)
    {
        $dom = XmlHelper::createDomDocument();
        $root = $dom->createElement("exception");
        $root->appendChild(XmlHelper::buildXmlCdataNode($dom, "message", $ex->getMessage()));

        if ($showTrace)
            $root->appendChild(XmlHelper::buildXmlCdataNode($dom, "trace", $ex->getTraceAsString()));

        $dom->appendChild($root);
        return $dom;
    }
}
?>