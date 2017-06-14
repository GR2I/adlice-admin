<?php	

require_once(__DIR__.'/lib/querybuilder.php');

class Database
{
	private $host;
	private $name;
	private $user;
	private $pass;
	private $mysqli;
	private $last_error;
	
	const status_wait = 0;
	const status_approved = 1;
	const status_completed = 2;
	const status_rejected = 3;	
	const type_fp = 0;
	const type_new = 1; 	
	const type_bug = 2;
	
	public function __construct($db_host, $db_name, $db_user, $db_pass) 
	{
		$this->host 		= $db_host;
		$this->name 		= $db_name;
		$this->user 		= $db_user;
		$this->pass 		= $db_pass;
		$this->mysqli 		= NULL;
		$this->last_error 	= 0;
		
		$this->Connect();
	}
	
	public function __destruct() 
	{
		if ( $this->mysqli != NULL )
		{
			$this->mysqli->close();
			$this->mysqli = NULL;
		}
	}
	
	private function Connect() 
	{		
		// Create a new mysqli object with database connection parameters
		$this->mysqli = new mysqli($this->host, $this->user, $this->pass, $this->name);
		if($this->mysqli->connect_errno) 
		{
			$this->last_error 	= $this->mysqli->connect_errno;
			$this->mysqli 		= NULL;
			return False;
		}
		return True;
	}
	
	private static function utf8_encode_deep(&$input) 
	{
		if (is_string($input)) {
			$input = utf8_encode($input);
		} else if (is_array($input)) {
			foreach ($input as &$value) {
				self::utf8_encode_deep($value);
			}
			unset($value);
		} else if (is_object($input)) {
			$vars = array_keys(get_object_vars($input));
			foreach ($vars as $var) {
				self::utf8_encode_deep($input->$var);
			}
		}
	}
	
	public function escape_string($str){
		return $this->mysqli->real_escape_string($str);	
	}
	
	//========================================================== 
	// PUBLIC part
	
	public function IsConnected() {
		return $this->mysqli != NULL;
	}
	
	public function LastError() {
		return $this->last_error;
	}
	
	public function Execute(QueryBuilder $queryobj)
	{
		$query 	= $queryobj->build();
		$stmt 	= $this->mysqli->query($query);
		$results = array();
		while (is_object($stmt) && $result = $stmt->fetch_assoc()) {
			$results[] = $result;	
		}
		if (is_object($stmt)) $stmt->close();		
		return $results;
	}
	
	public function ExecuteQuery($query)
	{
		$stmt = $this->mysqli->prepare($query);
		if($stmt->execute()) {
			return True;
		}
		return False;
	}
	
	//==================================================
	
	public function GetActions($id)
	{
		$stmt = $this->mysqli->prepare("SELECT client,user,action FROM roguekiller_actions WHERE client=?" );
		$stmt->bind_param("s", $id);
		$stmt->execute();
		$stmt->bind_result($client, $user, $action);
		$results = array();
		while ($result = $stmt->fetch()) {
			$results[] = array("client" => $client, "user" => $user, "action" => $action);
		}
		$stmt->close();	
		return $results;
	}
	
	public function RemoveActions($id)
	{
		$stmt = $this->mysqli->prepare("DELETE FROM roguekiller_actions WHERE client=?");
		$stmt->bind_param("s", $id);
		$stmt->execute();
		$stmt->close();	
		return true;
	}
	
	public function PopActions($id)
	{
		$results = $this->GetActions($id);
		$this->RemoveActions($id);
		return $results;
	}
	
	public function PushAction($id, $user, $action) 
	{		
		$stmt = $this->mysqli->prepare("INSERT INTO roguekiller_actions (client, user, action) VALUES (?,?,?) ON DUPLICATE KEY UPDATE action=?");
		$stmt->bind_param("ssss", $id, $user, $action, $action);
		$stmt->execute();
		$stmt->close();	
		return true;
	}
	
	public function RegisterClient($id, $os, $status, $program, $version, $version_available, $is_outdated, $ip) 
	{		
		$stmt = $this->mysqli->prepare("INSERT INTO roguekiller_agents (id, os, version, status, last_seen, ipv4, version_available, is_outdated) VALUES (?,?,?,?,NOW(),?,?,?) 
				ON DUPLICATE KEY UPDATE os=?, version=?, status=?, last_seen=NOW(), ipv4=?, version_available=?, is_outdated=?");
		$stmt->bind_param("ssssssisssssi", $id, $os, $version, $status, $ip, $version_available, $is_outdated, $os, $version, $status, $ip, $version_available, $is_outdated);
		$stmt->execute();
		$stmt->close();	
		return true;
	}
	
	public function Create()
	{
		$success = true;
		
		$rk_actions_sql = "
		CREATE TABLE `roguekiller_actions` (
		  `client` varchar(64) NOT NULL,
		  `user` int(11) NOT NULL,
		  `action` text NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;
		";
		
		$stmt = $this->mysqli->prepare($rk_actions_sql);
		if($stmt->execute())
		{
			echo "<p>roguekiller_actions table created.....</p>";
		}
		else
		{
			echo "<p>Error constructing roguekiller_actions table.</p>";
			$success = false;
		}
		
		$rk_actions_sql = "
		ALTER TABLE `roguekiller_actions`
  			ADD UNIQUE KEY `client` (`client`,`user`);
		";	
		
		$stmt = $this->mysqli->prepare($rk_actions_sql);
		if($stmt->execute())
		{
			echo "<p>samples table created.....</p>";
		}
		else
		{
			echo "<p>Error constructing samples table.</p>";
			$success = false;
		}
		
		//=========================================
		
		$rk_agents_sql = "
		CREATE TABLE `roguekiller_agents` (
		  `id` varchar(64) NOT NULL,
		  `os` text NOT NULL,
		  `version` text NOT NULL,
		  `status` text NOT NULL,
		  `last_seen` datetime NOT NULL,
		  `ipv4` varchar(16) NOT NULL,
		  `version_available` text NOT NULL,
		  `is_outdated` int(11) NOT NULL DEFAULT '0'
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;
		";	
		
		$stmt = $this->mysqli->prepare($rk_agents_sql);
		if($stmt->execute())
		{
			echo "<p>roguekiller_agents table created.....</p>";
		}
		else
		{
			echo "<p>Error constructing roguekiller_agents table.</p>";
			$success = false;
		}
		
		$rk_agents_sql = "
		ALTER TABLE `roguekiller_agents`
		  ADD PRIMARY KEY (`id`),
		  ADD KEY `last_seen` (`last_seen`);
		";	
		
		$stmt = $this->mysqli->prepare($rk_agents_sql);
		if($stmt->execute())
		{
			echo "<p>roguekiller_agents table created.....</p>";
		}
		else
		{
			echo "<p>Error constructing roguekiller_agents table.</p>";
			$success = false;
		}
		
		//=========================================
		
		return $success;
	}
}

?>