<?php
// Wrapper do łatwej obsługi MySQL poprzez mysqli


	class Database {

		public $link = NULL;
		public $errors = array();
		
		public function __construct($server = '',$user='',$pass='',$port='',$dbname='',$collation='') {
		
			if(!empty($server)) $this->connect($server, $user, $pass, $port, $dbname, $collation);
			
		}
		
		public function __destruct() {
		
			$this->close();
			
		}

		public function connect($server, $user, $pass, $port, $dbname, $collation) {
		
			$this->link = mysqli_connect($server, $user, $pass, $dbname, $port) or $errors[] = 'Database Error: '.mysqli_connect_error();
			$this->link->set_charset($collation);
			//$this->nonquery("SET NAMES 'utf8';");
			
		}
		
		public function close() {
		
			//$this->link->close();
			
		}

		public function info() {
		
			return mysqli_get_host_info($this->link);
			
		}

		public function es($text) {
		
			return $this->link->real_escape_string($text);
			
		}
		
		function prepareQuery($string, $vars = array()) {
		
			if(!is_array($vars)) $vars = array_slice(func_get_args(),1);
			
			foreach($vars as $var) {
				if(!isset($v)) $v = 0;
				++$v;
				$string = str_replace('{'.$v.'}',$this->es($var),$string);
			}
			return $string;
			
		} 
		
		public function query($command) {
		
			$result = $this->link->query($command);
			if ($result == NULL) throw new Exception("Query Error: ".$this->link->error." Query:".$command."");
			return $result;
			
		}
		
		public function nonquery($command) {
		
			if ($this->link->query($command) == NULL) throw new Exception("NonQuery Error: ".$this->link->error." Query:".$command."");
			
		}
		
		public function fetchfirst($command) {
		
			$result = $this->fetcharray($command);
			return $result; 
			
		}
		
		public function fetcharray($command) {
		
			$query = $this->link->query($command);
			$result = $query->fetch_array(MYSQLI_BOTH);
			$query->close();
			return $result;
			
		}
		
		public function LastInsertId() {
		
			$result = $this->fetchfirst("SELECT LAST_INSERT_ID();");
			return $result[0];
			
		}
		
	}

?>
