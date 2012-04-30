<?php
	include_once("utilities/JaduStatus.php");
	
	// Force onto SSL if required.
	if ((defined('SSL_ENABLED') && SSL_ENABLED) && PROTOCOL != 'https://') {
		header("Location: ".getSecureSiteRootURL().$_SERVER['REQUEST_URI']);
		exit;
	}
	
	include_once("JaduStyles.php"); 
	
	if (!Jadu_Service_User::getInstance()->canUpdateUserPassword()) {
		header('Location: ' . buildSignInURL());
		exit();
	}
	
	$error = false;
	if (!isset($_POST['email']) || !preg_match('/[0-9A-Za-z\.\-_]{1,127}@[0-9A-Za-z\.\-_]{1,127}/', $_POST['email'])) {
		$error = true;
	}
	
	if (isset($_POST['getPassword']) && !$error) {
		$user = Jadu_Service_User::getInstance()->getUserByIdentity($_POST['email']);
		if (strtolower($user->email) == strtolower($_POST['email'])) {
			Jadu_Service_User::getInstance()->sendUserPassword($_POST['email']);
			unset($user);
		}
		else {
			$error = true;
		}
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Password reminder';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Password reminder</span></li>';

	include("forgot_password.html.php");
?>