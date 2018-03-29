<?php

    $methodName = "";
    $db = null;
	
	// dodaje nowego uzytkownika
    function AddUser($device_id, $name, $type) {
        
        $errorInfo = '';
        $error = 0;
   
		try {
		
			$db = new PDODB("mysql:host=".MYSQL_API_HOST.";dbname=".MYSQL_API_DATABASE.";port=".MYSQL_API_PORT,MYSQL_API_USER,MYSQL_API_PASSWORD);
			$firstRow = $db->fetchFirstRow("SELECT count(*) c FROM `users` WHERE `device_id`='".$device_id."' AND `type`='".$type."';");
		
            if ((int)$firstRow['c'] == 0) {
			
			   $firstRow = $db->fetchFirstRow("SELECT MAX(`id`)+1 as 'free_id' FROM `users`;");
			   $firstRow["free_id"];
			
			   $name = $name.$firstRow["free_id"];
			
               $db->executeQuery("INSERT INTO `users` SET `device_id`='".$device_id."', `name`=:n, `type`='".$type."', `login_count`='1', `date_create`='".date("Y-m-d H:i:s")."';", array("n" => $name));
            
			} else {
			
				$db->executeQuery("UPDATE `users` SET `date_update`='".date("Y-m-d H:i:s")."', `login_count`=`login_count`+1 WHERE `device_id`='".$device_id."' AND `type`='".$type."';");
			
			}
		
		}
        
		catch (Exception $exception) { 
		
			$errorInfo.= ( DEBUG ? "Error (".$exception->getFile()." line ".$exception->getLine()."): " : "").$exception->getMessage(); 
			
		}
 
        if (strlen($errorInfo) > 0) $error = 1;
        return array (
			
			'outputInfo'    => array('error' => $error, 'info' => $errorInfo)
        
		);
    }	
	
	// dodaje nowego uzytkownika
    function GetUser($device_id, $type) {
        
        $errorInfo = '';
        $error = 0;
   
		try {
		
			$db = new PDODB("mysql:host=".MYSQL_API_HOST.";dbname=".MYSQL_API_DATABASE.";port=".MYSQL_API_PORT,MYSQL_API_USER,MYSQL_API_PASSWORD);
        
			$firstRow = $db->fetchFirstRow("SELECT `name` FROM `users` WHERE `device_id`='".$device_id."' AND `type`='".$type."';");
            $return = $firstRow["name"];
		
		}
        
		catch (Exception $exception) { 
		
			$errorInfo.= ( DEBUG ? "Error (".$exception->getFile()." line ".$exception->getLine()."): " : "").$exception->getMessage(); 
			
		}
 
        if (strlen($errorInfo) > 0) $error = 1;
        return $return;
    }	
	
	
	// zwraca pierwszy wolny id dla uzytkownika
   /* function GetFreeId() {
        
        $errorInfo = '';
        $error = 0;
   
		try {
		
			$db = new PDODB("mysql:host=".MYSQL_API_HOST.";dbname=".MYSQL_API_DATABASE.";port=".MYSQL_API_PORT,MYSQL_API_USER,MYSQL_API_PASSWORD);
        
			$firstRow = $db->fetchFirstRow("SELECT MAX(`id`)+1 as 'free_id' FROM `users`;");
            $return = $firstRow["free_id"];
		
		}
        
		catch (Exception $exception) { 
		
			$errorInfo.= ( DEBUG ? "Error (".$exception->getFile()." line ".$exception->getLine()."): " : "").$exception->getMessage(); 
			
		}
 
        if (strlen($errorInfo) > 0) $error = 1;
        return $return;
    }	*/
    	
	// aktualizuje nazwe uzytkownika
    function UpdateUser($device_id, $name, $type) {
        
        $errorInfo = '';
        $error = 0;
   
		try {
		
			$db = new PDODB("mysql:host=".MYSQL_API_HOST.";dbname=".MYSQL_API_DATABASE.";port=".MYSQL_API_PORT,MYSQL_API_USER,MYSQL_API_PASSWORD);
        
			$db->executeQuery("UPDATE `users` SET `name`=:n WHERE `device_id`='".$device_id."' AND `type`='".$type."';", array("n" => $name));
		
		}
        
		catch (Exception $exception) { 
		
			$errorInfo.= ( DEBUG ? "Error (".$exception->getFile()." line ".$exception->getLine()."): " : "").$exception->getMessage(); 
			
		}
 
        if (strlen($errorInfo) > 0) $error = 1;
        return array (
			
			'outputInfo'    => array('error' => $error, 'info' => $errorInfo)
        
		);
    }	
	
	// sprawdzanie punktow i zapisywanie na liscie wynikow
    function SavePoints($device_id, $type, $points) {
        
        $errorInfo = '';
        $error = 0;
		
		$new = 0;
		$record = 0;
   
		try {
		
			$db = new PDODB("mysql:host=".MYSQL_API_HOST.";dbname=".MYSQL_API_DATABASE.";port=".MYSQL_API_PORT,MYSQL_API_USER,MYSQL_API_PASSWORD);
        
			$firstRow = $db->fetchFirstRow("SELECT `id` FROM `users` WHERE `device_id`='".$device_id."' AND `type`='".$type."';");
            $userID = (int)$firstRow['id'];
		
			$firstRow = $db->fetchFirstRow("SELECT `points` FROM `hiscores` WHERE `id_user`='".$userID."';");
			$getPoints = (int)$firstRow['points'];
			$record = $getPoints;
			
			if ($db->rowCount() == 0) {

				$new = 1;
				$record = (int)$points;	
				$db->executeQuery("INSERT INTO `hiscores` SET `points`='".$points."', `id_user`='".$userID."', `date`='".date("Y-m-d H:i:s")."';");
			
			} else {
			
				// jesli punkty gracza sa wieksze niz aktualny stan ustaw nowy wynik
				if ((int)$points > $getPoints) {
				
					$new = 1;
					$record = (int)$points;
					$db->executeQuery("UPDATE `hiscores` SET `points`='".$points."', `date`='".date("Y-m-d H:i:s")."' WHERE `id_user`='".$userID."';");
				
				} 
				 
			}
			
			// sprawdzanie lacznej sumy "best of the best" i zapisywanie na liste
		/*	$firstRow = $db->fetchFirstRow("SELECT sum(`points`) as `points` FROM `hiscores` WHERE `id_user`='".$userID."';");
			$allPoints = (int)$firstRow['points'];
			
			$firstRow = $db->fetchFirstRow("SELECT count(`id_user`) as `count` FROM `points` WHERE `id_user`='".$userID."';");
            if ((int)$firstRow['count'] == 0) {
			
				if ($allPoints > 0) $db->executeQuery("INSERT INTO `points` SET `id_user`='".$userID."', `points`='".$allPoints."', `date`='".date("Y-m-d H:i:s")."';");
			
			} else {
			
				if ($allPoints > 0) $db->executeQuery("UPDATE `points` SET `points`='".$allPoints."', `date`='".date("Y-m-d H:i:s")."' WHERE `id_user`='".$userID."';");
			
			}*/
			
			// dodawanie punktow zdobytych w levelu
			$db->executeQuery("INSERT INTO `points` SET `id_user`='".$userID."', `points`='".$points."', `date`='".date("Y-m-d H:i:s")."';");
			/*$firstRow = $db->fetchFirstRow("SELECT count(`id_user`) as `count` FROM `points` WHERE `id_user`='".$userID."';");
            if ((int)$firstRow['count'] == 0) {
			
				$db->executeQuery("INSERT INTO `points` SET `id_user`='".$userID."', `points`='".$points."', `date`='".date("Y-m-d H:i:s")."';");
			
			} else {
			
				$firstRow = $db->fetchFirstRow("SELECT `points` FROM `points` WHERE `id_user`='".$userID."';");
			
				if ((int)$points > $firstRow['points']) $db->executeQuery("UPDATE `points` SET `points`='".$points."', `date`='".date("Y-m-d H:i:s")."' WHERE `id_user`='".$userID."';");
			
			}*/
		
		}
        
		catch (Exception $exception) { 
		
			$errorInfo.= ( DEBUG ? "Error (".$exception->getFile()." line ".$exception->getLine()."): " : "").$exception->getMessage(); 
			
		}
 
        if (strlen($errorInfo) > 0) $error = 1;
        return array("newRecord" => $new,
		             "record" => $record,
		             "allPoints" => 0
					 );
    }	

	// sprawdzanie punktow i zapisywanie na liscie wynikow
  /*  function GetPoints($chapter, $level, $limit) {
        
        $errorInfo = '';
        $error = 0;
		
		$new = 0;
		$record = 0;
   
		try {
		
		    $db = new PDODB("mysql:host=".MYSQL_API_HOST.";dbname=".MYSQL_API_DATABASE.";port=".MYSQL_API_PORT,MYSQL_API_USER,MYSQL_API_PASSWORD);
 
			$query = $db->executeQuery("SELECT `points` FROM `points` WHERE `chapter`='".$chapter."' AND `level`='".$level."';");

			$points = array();
			foreach($db->executeReader($query) as $row) {

				$points[] = array("points" => $row['points']);

			}
		
		}
        
		catch (Exception $exception) { 
		
			$errorInfo.= ( DEBUG ? "Error (".$exception->getFile()." line ".$exception->getLine()."): " : "").$exception->getMessage(); 
			
		}
 
        if (strlen($errorInfo) > 0) $error = 1;
        return array("table" => $points);
		
    }	*/
	
	
    function GetRankingTable($chapter, $level, $limit)
    {
        
		try {
		
		    $db = new PDODB("mysql:host=".MYSQL_API_HOST.";dbname=".MYSQL_API_DATABASE.";port=".MYSQL_API_PORT,MYSQL_API_USER,MYSQL_API_PASSWORD);
 
			//$query = $db->executeQuery("SELECT `p`.`points`, `u`.`name` FROM `points` `p` JOIN `users` `u` ON `u`.`id`=`p`.`id_user` WHERE `p`.`chapter`='".$chapter."' AND `p`.`level`='".$level."' ORDER by `p`.`points` DESC LIMIT ".$limit.";");
			$query = $db->executeQuery("SELECT `p`.`points`, `u`.`name` FROM `points` `p` JOIN `users` `u` ON `u`.`id`=`p`.`id_user` ORDER by `p`.`points` DESC LIMIT ".$limit.";");

			$table = array();
			foreach($db->executeReader($query) as $row) {

				$table[] = array("points" => $row['points'],
				                 "user" => $row['name']);

			}
		
		}
        
		catch (Exception $exception) { 
		
			$errorInfo.= ( DEBUG ? "Error (".$exception->getFile()." line ".$exception->getLine()."): " : "").$exception->getMessage(); 
			
		}
		
		
        return array
            (
                'table' => $table,
                'outputInfo' => ''
            );
    }

	
	
    function GetRankingBestTable($limit)
    {
        
		try {
		
		    $db = new PDODB("mysql:host=".MYSQL_API_HOST.";dbname=".MYSQL_API_DATABASE.";port=".MYSQL_API_PORT,MYSQL_API_USER,MYSQL_API_PASSWORD);
 
			$query = $db->executeQuery("SELECT `h`.`points`, `u`.`name` FROM `hiscores` `h` JOIN `users` `u` ON `u`.`id`=`h`.`id_user`  ORDER by `h`.`points` DESC LIMIT ".$limit.";");

			$table = array();
			foreach($db->executeReader($query) as $row) {

				$table[] = array("points" => $row['points'],
				                 "user" => $row['name']);

			}
		
		}
        
		catch (Exception $exception) { 
		
			$errorInfo.= ( DEBUG ? "Error (".$exception->getFile()." line ".$exception->getLine()."): " : "").$exception->getMessage(); 
			
		}
		
		
        return array
            (
                'table' => $table,
                'outputInfo' => ''
            );
    }

    			
		
	////////////////////////////////////////////////
	
    function Register_Methods($server, $namespace) {
      
		$server->register('AddUser',
		array('device_id'=>'xsd:string', 'name'=>'xsd:string', 'type'=>'xsd:string'),
		array('AddUser_return'=>'xsd:string'),
		$namespace,false,'rpc','literal','Dodaje nowego uzytkownika');
		
		$server->register('GetUser',
		array('device_id'=>'xsd:string', 'type'=>'xsd:string'),
		array('GetUser_Return' => 'xsd:string'),
		//array('return'=>'xsd:string'),
		$namespace,false,'rpc','literal','Zwraca nazwe uzytkownika');
  /*
		$server->register('GetFreeId',
		//array('device_id'=>'xsd:string', 'type'=>'xsd:string'),
		array('GetFreeId_Return' => 'xsd:int'),
		//array('return'=>'xsd:string'),
		$namespace,false,'rpc','literal','Zwraca pierwszy wolny id');
  */
		$server->register('SavePoints',
		array('device_id'=>'xsd:string', 'type'=>'xsd:string', 'points'=>'xsd:float'),
		array('SavePoints_Return' => 'tns:SavePointsOutput'),
		//array('return'=>'xsd:string'),
		$namespace,false,'rpc','literal','Zapisuje punkty i zwraca stan zapisu');
		
  
        $server->register('GetRankingTable',
            array('chapter'=>'xsd:int', 'level'=>'xsd:int', 'limit'=>'xsd:int'),
            array('GetRankingTable_return'=>'tns:RankingTable'), // To jest kluczowe aby ta nazwa była unikalna w skali WS
            $namespace,false,'rpc','literal','GetRankingTable');
 
 
   
        $server->register('GetRankingBestTable',
            array('limit'=>'xsd:int'),
            array('GetRankingBestTable_return'=>'tns:RankingBestTable'), // To jest kluczowe aby ta nazwa była unikalna w skali WS
            $namespace,false,'rpc','literal','GetRankingBestTable');
 
  
	/*
		$server->register('GetPoints',
		array('chapter'=>'xsd:int', 'level'=>'xsd:int', 'limit'=>'xsd:int'),
		array('GetPoints_return' => 'tns:Points'),
		$namespace,false,'rpc','literal','Zwraca punkty z podanego levelu');
*/
		$server->register('UpdateUser',
		array('device_id'=>'xsd:string', 'name'=>'xsd:string', 'type'=>'xsd:string'),
		array('UpdateUser_return'=>'xsd:string'),
		$namespace,false,'rpc','literal','Edycja uzytkownika');
  
  
    }

?>