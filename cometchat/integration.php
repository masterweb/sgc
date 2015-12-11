<?php

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* ADVANCED */

define('SET_SESSION_NAME','');			// Session name
define('DO_NOT_START_SESSION','0');		// Set to 1 if you have already started the session
define('SWITCH_ENABLED','1');		
define('INCLUDE_JQUERY','1');	
define('FORCE_MAGIC_QUOTES','0');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* DATABASE */


//$db = include_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'settings'.DIRECTORY_SEPARATOR.'database.php');

// DO NOT EDIT DATABASE VALUES BELOW
// DO NOT EDIT DATABASE VALUES BELOW
// DO NOT EDIT DATABASE VALUES BELOW

define('DB_SERVER',"localhost");
define('DB_PORT','3306');			
define('DB_USERNAME','root');
define('DB_PASSWORD','');
define('DB_NAME','callcenter');
define('TABLE_PREFIX','');
define('DB_USERTABLE','usuarios');
define('DB_USERTABLE_NAME','nombres');
define('DB_USERTABLE_USERID','id');
define('DB_AVATARTABLE',"" );
define('DB_AVATARFIELD',"foto");

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* FUNCTIONS */

function getUserID(){ 
	$userid = 0;

	if (!empty($_SESSION['user'])){ 
		$userid = $_SESSION['user']; 
	}
	$userid = intval($userid); 
	return $userid; 
 } 



function getFriendsList($userid,$time) {
$sql = ("select DISTINCT usuarios.id userid, concat(usuarios.apellido,' ', usuarios.nombres,' ',c.nombre) username, usuarios.usuario link, usuarios.id avatar, cometchat_status.lastactivity lastactivity, cometchat_status.status, cometchat_status.message, cometchat_status.isdevice 
from usuarios left join cometchat_status on usuarios.id = cometchat_status.userid 
inner join gr_concesionarios c on usuarios.concesionario_id = c.id
order by username asc");
	
	return $sql;
}

function getFriendsIds($userid) {

	$sql = ("select group_concat(friends.fid) myfrndids from (select ".TABLE_PREFIX."user_membership.user_id fid from ".TABLE_PREFIX."user_membership where ".TABLE_PREFIX."user_membership.resource_id = '".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."' and active = 1) friends");
	
	return $sql;
}

function getUserDetails($userid) {
	$sql = ("select ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".TABLE_PREFIX.DB_USERTABLE.".username link, ".DB_AVATARFIELD." avatar, cometchat_status.lastactivity lastactivity, cometchat_status.status, cometchat_status.message, cometchat_status.isdevice from ".TABLE_PREFIX.DB_USERTABLE." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid ".DB_AVATARTABLE." where ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = '".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."'");

	return $sql;
}

function updateLastActivity($userid) {
	$sql = ("insert into cometchat_status (userid,lastactivity) values ('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."','".getTimeStamp()."') on duplicate key update lastactivity = '".getTimeStamp()."'");
	return $sql;
}

function getUserStatus($userid) {
	 $sql = ("select ".TABLE_PREFIX.DB_USERTABLE.".status message, cometchat_status.status from ".TABLE_PREFIX.DB_USERTABLE." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid where ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = '".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."'");

	 return $sql;
}

function fetchLink($link) {
	return BASE_URL."../profile/".$link;
}

function getAvatar($image) {
	return BASE_URL."../images/img_06.jpg";
	/*if (!empty($_SESSION['foto']) && isset($_SESSION['foto'])){ 
		return BASE_URL."../images/img_06.jpg";
	}else{
		
		return BASE_URL."../upload/perfiles/thumb/".$_SESSION['foto'];
	}*/
	

}

function getTimeStamp() {
	return time();
}

function processTime($time) {
	return $time;
}

if (!function_exists('getLink')) {
  	function getLink($userid) { return fetchLink($userid); }
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* HOOKS */

function hooks_statusupdate($userid,$statusmessage) {
	$sql = ("update ".TABLE_PREFIX.DB_USERTABLE." set status = '".mysqli_real_escape_string($GLOBALS['dbh'],$statusmessage)."', status_date = '".date("Y-m-d H:i:s",getTimeStamp())."' where ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = '".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."'");
 	$query = mysqli_query($GLOBALS['dbh'],$sql);
}

function hooks_forcefriends() {
	
}

function hooks_activityupdate($userid,$status) {

}

function hooks_message($userid,$to,$unsanitizedmessage) {
	
}

function hooks_updateLastActivity($userid) {
    
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* Cometchat V5.4 Socialengine Integration by QASEDAK Group */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////