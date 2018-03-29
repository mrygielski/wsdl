<?php

	ini_set("soap.wsdl_cache_enabled", "0"); 

	include('nusoap/nusoap.php');

	$client = new soapclient('http://someurl/WSDL.php?wsdl',array('encoding' => 'UTF-8')); 
	 
	$x = $client->GetRankingBestTable(10);
    foreach($x->table as $r => $v) {
	
		echo $v->points."<br>";
	
	}
	
?>