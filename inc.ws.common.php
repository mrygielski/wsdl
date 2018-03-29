<?php

/////////
// Standardowe funkcje do wykorzystywania we wszystkich webservice'ach
/////////
require('include/inc.variables.php'); 
require('include/inc.mysqli.config.php'); 
require('include/inc.mysqli.php'); 
require('include/inc.functions.php'); 

    
function APIClientLog($db, $idUser, $methodName, $total_time, $error)
{
    //$db->nonquery("INSERT INTO `mm_soap_logs` (`created`,`id_user`,`api_version`,`api_webservice`,`method`,`execution_time`,`return`) VALUES (now(),".$db->es($idUser).",'".API_VERSION."','".WEBSERVICE_NAME."','".$db->es($methodName)."','".$db->es($total_time)."',".(strlen($error)==0 ? "NULL" : "'".$db->es($error)."'").");");
}



//////////////////////////////////////////
//////Metody standardowe dla WS
//////////////////////////////////////////

    
////////////////////////////////////////////////    

function RegisterCommonComplexTypes($server)
{
 
}


?>