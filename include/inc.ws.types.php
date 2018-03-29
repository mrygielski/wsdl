<?php
    
	function Register_Types($server) {

        $server->wsdl->addComplexType('SavePointsOutput','complexType','struct','sequence','',
            array
            ( 
                'newRecord' => array('type' => 'xsd:int'),
                'record' => array('type' => 'xsd:int'),
                'allPoints' => array('type' => 'xsd:int')
            )
        );
		
 
         $server->wsdl->addComplexType('TableRow','complexType','struct','sequence','',
            array
            ( 
                'points'        => array('type' => 'xsd:int'),
                'user'          => array('type' => 'xsd:string')
            ));        

        $server->wsdl->addComplexType('RankingTable','complexType','struct','sequence','',
            array
            ( 
                'table'         => array('type' => 'tns:TableRow', 'minOccurs' => '0', 'maxOccurs' => 'unbounded'),
                'outputInfo'    => array('type' => 'xsd:string')
            ));     


 
         $server->wsdl->addComplexType('BestTableRow','complexType','struct','sequence','',
            array
            ( 
                'points'        => array('type' => 'xsd:int'),
                'user'          => array('type' => 'xsd:string')
            ));        

        $server->wsdl->addComplexType('RankingBestTable','complexType','struct','sequence','',
            array
            ( 
                'table'         => array('type' => 'tns:BestTableRow', 'minOccurs' => '0', 'maxOccurs' => 'unbounded'),
                'outputInfo'    => array('type' => 'xsd:string')
            ));     



		/*
		
	 	  $server->wsdl->addComplexType('TableRow','complexType','struct','sequence','',
            array
            ( 
                'points'     => array('type' => 'xsd:int')
            ));  
		
		  $server->wsdl->addComplexType('Points','complexType','struct','sequence','',
            array
            ( 
                'table'         => array('type' => 'tns:TableRow', 'minOccurs' => '0', 'maxOccurs' => 'unbounded')
                
            )); 
		 */
    }

?>