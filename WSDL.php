<?php 

	/****************************************************/
	// 
	//  WSDL v1.0
	//
	//**************************************************/
	//  
	//  Us³uga wymiany danych pomiedzy sklepem a aplikacj¹
	//  Author: Micha³ Rygielski 
	//  Date: 2013-09-19
	//
	/****************************************************/	

	
	require('include/inc.variables.php'); 
	require('include/inc.mysqli.config.php'); 
	//require('include/inc.mysqli.php'); 
	require('include/pdo.db.php');
	require('include/inc.functions.php');      
	require('include/inc.ws.types.php'); 
    require('include/inc.ws.methods.php'); 

    // Tworzenie serwera
    require_once("nusoap/nusoap.php");
    $namespace = full_url()."?wsdl";
    
	$server = new soap_server();
    $server->configureWSDL("WSDL");
    $server->soap_defencoding = 'utf-8'; 
    $server->soap_http_encoding = 'utf-8'; 
    $server->xml_excoding = 'utf-8'; 
    $server->decode_utf8 = false;
	
    Register_Types($server);
    Register_Methods($server, $namespace);
        
    $server->service(isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '');
    exit();    
    
?> 