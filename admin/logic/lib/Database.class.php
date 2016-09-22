<?php
	/*try{
	
		require_once('./config.inc.php');
	}
	
	catch(Exception $e)
	{
		require_once('../config.inc.php');
	}*/
	class Database{
		
		static function getConnection()
		{
			//$mysqli = 
			//$mysqli = new MySQLi('localhost','pms','pms','pms') or die("Connection Error");
			return $mysqli;
		}
	}
	
	
?>