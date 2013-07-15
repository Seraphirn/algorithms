<?php 
	
	$dom = new DOMDocument();
	$dom->preserveWhiteSpace = false; 
	$dom->strictErrorChecking = false;

	$dom->loadHTMLFile("http://pogoda.yandex.ru/samara/details");	
	$xpath = new DOMXPath($dom);

	$temperatureCurrent = $xpath->query("//div[@class='b-thermometer__now']")->item(0)->nodeValue;
	//print($temperatureCurrent);
	//$temperatureCurrent = $xpath->query('/html/body/div[2]/table/tr/td/span')->item(0)->nodeValue;
	if(preg_match_all( '/([+-]?\s*\d+)/u', $temperatureCurrent, $matchCurrentTemp ))
	{
		print($matchCurrentTemp[0][0]);
		// $weather->setTemperatureCurrent($matchCurrentTemp[0][0]);
		// if(isset($matchCurrentTemp[0][1])) $weather->setTemperatureWater($matchCurrentTemp[0][1]);
	}
?>