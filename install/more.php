<?php 
require_once(__DIR__."/../src/config.php");
require_once(__DIR__."/../src/db.php");

//=================================================================
//Signatures DB

function getDb() {
	return new Database(
		$GLOBALS["config"]["db"]["admin"]["host"], 
		$GLOBALS["config"]["db"]["admin"]["dbname"],
		$GLOBALS["config"]["db"]["admin"]["username"], 								
		$GLOBALS["config"]["db"]["admin"]["password"]
		
	);
}

function Install()
{
	$db = getDb();
	$success = true;
	
	$success &= $db->Create();
	
	return $success;
}

?>