<?php
require_once(__DIR__."/src/config.php");
require_once(__DIR__.'/src/db.php');
require_once(__DIR__.'/src/lib/restlib.php');
require_once(__DIR__."/src/lib/usercake/init.php");

require_once(__DIR__."/plugins/datatables/extensions/Editor/php/DataTables.php" );	

// Alias Editor classes so they are easy to use
use DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Validate;	

$user = null;

class Rest_Api extends Rest_Rest {
	public function __construct(){
		parent::__construct();				// Init parent contructor	
	}
	
	public function processApi()
	{		
		global $user;
		$current_user = UCUser::getCurrentUser();
		
		// Extract requested API
		// We use API keyword because action is reserved for datatables
		$func = isset($_REQUEST['api']) ? strtolower(trim(str_replace("/","",$_REQUEST['api']))) : null;	
		if (!$func && isset($_POST['api'])) $func = strtolower(trim(str_replace("/","",$_POST['api']))) ;	
		
		// Could not extract function, and is not a DELETE request nor a DOWNLOAD request
		if (!$func && $this->get_request_method() != "DELETE" && !(isset($_REQUEST) && isset($_REQUEST['download']))) {
			$this->response('',406);
		}
		
		// Extract API key
		if($current_user != NULL) // if logged in, we get it from current cookie
			$key = $current_user->Activationtoken();
		else {
			if (!isset($key) && isset($_REQUEST['token'])) 	$key = $_REQUEST['token'];
			if (!isset($key) && isset($_POST['token'])) 	$key = $_POST['token'];	
		}
					
		// Verify API key/ Save user id
		if (!isset($key)) $this->response('',401);
		$is_api_valid 	= UCUser::ValidateAPIKey($key); 
		$user 			= new UCUser(UCUser::GetByAPIKey($key));	
				
		// Go to selected route
		if (!$is_api_valid)											$this->response('',401);		
		else if((int)method_exists($this,$func) > 0)				$this->$func();		
		else														$this->unknown($func);
	}
	
	public function getParameter($key) {
		$key_as_header = 'HTTP_' . strtoupper(trim(str_replace("-","_",$key)));
		$value = isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;   // Search in request
		if (!$value && isset($_POST[$key])) $value = $_POST[$key];  // Search in post
		if (!$value && isset($_SERVER[$key_as_header])) $value = $_SERVER[$key_as_header]; // Search in headers
		return $value;
	}
	
	private function getDb() {
		return new Database(
			$GLOBALS["config"]["db"]["admin"]["host"], 
			$GLOBALS["config"]["db"]["admin"]["dbname"],
			$GLOBALS["config"]["db"]["admin"]["username"], 								
			$GLOBALS["config"]["db"]["admin"]["password"]
			
		);
	}
	
	//===========================================================================
	// Routes
	
	public function unknown($func) {  
        $this->response('',404);
        return false;
	}
	
	public function start_scan()
	{
		global $user;
		if($this->get_request_method() != "POST"){ $this->response('',406); return false; }		
		
		$action     = "start_scan";
		$id 		= $this->getParameter("id");
		if (!$id) { $this->response('id not found',406); return false; }
		
		$db 		= $this->getDb();
		$results 	= $db->PushAction($id, $user->Id(), $action);	
		$this->response("{}",200);
	}
	
	public function start_update()
	{
		global $user;
		if($this->get_request_method() != "POST"){ $this->response('',406); return false; }		
		
		$action     = "start_update";
		$id 		= $this->getParameter("id");
		if (!$id) { $this->response('id not found',406); return false; }
		
		$db 		= $this->getDb();
		$results 	= $db->PushAction($id, $user->Id(), $action);	
		$this->response("{}",200);
	}
	
	public function notify() 
	{
		if($this->get_request_method() != "POST"){ $this->response('',406); return false; }				
		
		$data 		= $this->getParameter("data");
		if (!$data) { $this->response('data not found',406); return false; }
		
		$data_decoded = json_decode($data);
		if (!$data_decoded) { $this->response('invalid data',406); return false; }		
		if (!isset($data_decoded->id) || !isset($data_decoded->os) || !isset($data_decoded->version)) { 
			$this->response('invalid data',406); return false; 
		}
		
		$id 		= $data_decoded->id;
		$os 		= $data_decoded->os;
		$status 	= isset($data_decoded->status) ? $data_decoded->status : 'Online';
		$program	= $data_decoded->program;		
		$version 	= $data_decoded->version;
		$available	= $data_decoded->available;
		$is_outdated = $data_decoded->is_outdated;		
		$ip			= $_SERVER['REMOTE_ADDR'];
		
		$db 		= $this->getDb();
		$results 	= $db->RegisterClient($id, $os, $status, $program, $version, $available, $is_outdated, $ip);	
		
		$actions	= $db->PopActions($id);
		$this->response(json_encode($actions),200);
	}
	
	public function get_agents()
	{		
		global $user, $db;
		// Build our Editor instance and process the data coming from _POST
		$inst = Editor::inst( $db, 'roguekiller_agents' )
			->fields(
				Field::inst( 'id' ),
				Field::inst( 'os' ),
				Field::inst( 'version' ),
				Field::inst( 'status' ),
				Field::inst( 'last_seen' ),
				Field::inst( 'ipv4' ),
				Field::inst( 'version_available' ),
				Field::inst( 'is_outdated' )
			);
		
		$inst->process( $_POST )
			 ->json();	
	}
}

// Initiiate Library
$api = new Rest_Api;
$api->processApi();

?>