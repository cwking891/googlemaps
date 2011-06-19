<?php
/* Definitions */
define("DS", DIRECTORY_SEPARATOR);
define("br", "<br/>");

/* Set up directories */
$documentroot = $_SERVER['DOCUMENT_ROOT'];
$appdir = "/googlemaps";
$include_path = str_replace("/", DS,
      ";$documentroot" .$appdir .DS ."include"
    . ";$documentroot" .$appdir .DS ."classes");
ini_set('include_path', ini_get('include_path') . $include_path);
    
/* Include php and sql functions */
require_once ("sqlLibrary.php");
require_once ("functionLibrary.php");

/* Connect to database */
date_default_timezone_set('America/Los_Angeles');
require_once ("sqldatabaseconnect.php");

/* Start session */
ini_set('session.gc_maxlifetime', 5400);
error_reporting(E_ALL | E_STRICT);
session_start();

//Application-specific Initialization for every page
require_once("Model.php");
require_once("User.php");

//Check for signed-in user
$user = new User();
if (isset($_SESSION['signin_id'])) {
    $where = array('signin_id' => $_SESSION['signin_id'], 
                   'password' => $_SESSION['password']);
	if ($user->get($where)) {
    	$user->set_logged_in(true);
    }
}

//print_r($user);

// Set up some useful page names
// TODO: Use FullPathName for referer
$home = "$appdir/home.php";
$page = filename($_SERVER['PHP_SELF']);
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = filename($_SERVER['HTTP_REFERER']);
} else {
    $referer = $home;
}

if (isset($_SESSION[$page])) {
    $r = $_SESSION[$page];
    unset($_SESSION[$page]);
} else {
    $r = Array();
}

/* Write page visitor record */
$ip = $_SERVER['REMOTE_ADDR'];
if (isset($_SESSION['host'])) {
	$host = $_SESSION['host'];
} else {
	$host = gethostbyaddr($ip);
	$_SESSION['host'] = $host;
}
$query = 'INSERT INTO visitor (ip,page,host,visits,firstVisit,userAgent) VALUES("'
 .$ip .'",  "' .$page .'", "' .$host .'", 1, ' .date("YmdHis") .', "'
 .$_SERVER["HTTP_USER_AGENT"] .'")
 ON DUPLICATE KEY UPDATE visits=visits+1, lastVisit=' .date("YmdHis") .', userAgent="' 
 .$_SERVER["HTTP_USER_AGENT"] .'"';
$result = sql($query);

?>