<?php

//
//
//
//
//

class PDODB extends PDO 
{
    private $error;
	private $lastQuery;
	private $numRows;
    
    
	public function __construct($dsn, $user="", $passwd="", $timeout=30000) 
    {
		$options = array(
		  	//PDO::ATTR_PERSISTENT => true, 
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_TIMEOUT => $timeout,
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' );

		try 
        {
			parent::__construct($dsn, $user, $passwd, $options);
            $this->error = null;
		} 
        catch (PDOException $e) 
        {
			$this->error = $e->getMessage();
		}
	}    
    
    public function getLastQuery()
    {
        return $this->lastQuery;
    }
    
    public function getError()
    {
        return $this->error;
    }
    public function rowCount()
    {
        return $this->numRows;
    }
    
    
    public function executeQuery($query, $params=array())
    {
        try 
        {
            $_query = $this->prepare($query);
            $_query->execute($params);
			$this->numRows = $_query->rowCount();
            $this->error = null;
            $this->lastQuery = $_query->queryString;
            return $_query;
        } 
        catch (PDOException $e) 
        {
			$this->error = $e->getMessage();
            return null;
		}
    }
    
    public function executeReader($executed_query)
    {
        try 
        {
            $results = $executed_query->fetchAll(PDO::FETCH_BOTH);
            $this->error = null;
            return $results;
        } 
        catch (PDOException $e) 
        {
			$this->error = $e->getMessage();
            return null;
		}
    }
    
    public function fetchFirstRow($query, $params=array())
    {
        
		try 
        {
            $executed_query = $this->executeQuery($query, $params);
            $this->lastQuery = $query;
     
            $results = $executed_query->fetch(PDO::FETCH_BOTH);
            $this->error = null;
            return $results;
        } 
        catch (PDOException $e) 
        {
			$this->error = $e->getMessage();
            return null;
		}
    }

    public function executeNonQuery($query)
    {
        try 
        {
            $_rows = $this->exec($query);
            $this->error = null;
            $this->lastQuery = $query;
            return $_rows;
        } 
        catch (PDOException $e) 
        {
			$this->error = $e->getMessage();
            return null;
		}
    }
    
    
    /*

	// nonquery
    //$query = $db->executeQuery("INSERT INTO pdo_test VALUES (null,now(), :v);",array('v' => 'ABC'.rand().'X'));
    $query = $db->executeQuery("INSERT INTO pdo_test VALUES (null,now(), ?);",array('ABC'.rand().'X'));
    print("<pre>getLastQuery: ".$db->getLastQuery()."\nError: ".$db->GetError()."</pre><br>");
    print("inserted id: ".$db->lastInsertId()."<hr>");
    
    // select
    $query = $db->executeQuery("SELECT * FROM pdo_test WHERE val like :v LIMIT 10;",array('v'=>'%5%'));
    foreach($db->executeReader($query) as $row)
    {
        echo "ROW: ".$row['created']." ".$row['val']."<br>";
    }
    print("<pre>getLastQuery: ".$db->getLastQuery()."\nError: ".$db->GetError()."</pre><hr>");
    
    
    //procedura
    $query = $db->executeQuery("CALL `pdo_test_proc`();");
    foreach($db->executeReader($query) as $row)
    {
        echo "ROW: ".$row['result']."<br>";
    }
    print("<pre>getLastQuery: ".$db->getLastQuery()."\nError: ".$db->GetError()."</pre><hr>");

    //procedura drugi raz
    $firstRow = $db->fetchFirstRow("CALL `pdo_test_proc`();");
    echo "ROW: ".$firstRow['result']."<br>";
    print("<pre>getLastQuery: ".$db->getLastQuery()."\nError: ".$db->GetError()."</pre><hr>");
    

    //prosty select
    $firstRow = $db->fetchFirstRow("SELECT count(*) FROM `pdo_test`;");
    echo "ROW: ".$firstRow[0]."<br>";
    print("<pre>getLastQuery: ".$db->getLastQuery()."\nError: ".$db->GetError()."</pre><hr>"); 
	 * 	 
	 * */

}

?>