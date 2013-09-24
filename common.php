<?php
if (!defined('FORUM_ROOT'))
	exit;

// Enable error reporting
error_reporting(E_ALL);

// Try to connect to the database
try
{
	$dbh = new PDO('mysql:host=' . 'localhost' . ';dbname=' . 'FORUM', 'FORUM_admin', 'migtruelymig');
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
	print "Error!: " . $e->getMessage() . "<br/>";
	exit;
}

// Load the Facebook library
require FORUM_ROOT . 'lib/facebook-php-sdk/src/facebook.php';

$facebook = new Facebook(array(
  'appId'  => '276849219022102',
  'secret' => 'b7cc6bcf1d663435cea91f726a8e0cf3',
));

// Get User ID
$user = $facebook->getUser();

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.

if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}

// For CSRF prevention
if(!isset($_SESSION)){
	session_start();
}

if(!isset($_SESSION['csrfToken'])){
	//generate the token
	$token = md5(uniqid(rand(), TRUE));

	//set the token as a session
	$_SESSION['csrfToken'] = $token;
}

?>
