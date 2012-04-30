<?php
	include_once("utilities/JaduStatus.php");
	
	// Force onto SSL if required.
	if ((defined('SSL_ENABLED') && SSL_ENABLED) && PROTOCOL != 'https://') {
		header("Location: ".getSecureSiteRootURL().$_SERVER['REQUEST_URI']);
		exit;
	}
	
	include_once("marketing/JaduUsers.php");
	include_once("JaduStyles.php");
	
	if (!Jadu_Service_User::getInstance()->canRegisterUser()) {
		header('Location: ' . buildSignInURL());
		exit();
	}

	$sentAuthEmail = false;
	if (isset($_GET['sendAuthEmail'])) {
		$user = Jadu_Service_User::getInstance()->getUserByIdentity($_GET['sendAuthEmail']);
		if ($user->id > 0 && !$user->isAuthenticated() && !$user->isEmailConfirmed()) {
			emailNewUser($user->id);
			$sentAuthEmail = true;
			$_GET['email'] = $_GET['sendAuthEmail'];
		}
	}
	else if (isset($_GET['auth']) && isset($_GET['email'])) {
		$user = Jadu_Service_User::getInstance()->getUserByIdentity($_GET['email']);
		if ($user->id > 0 && !$user->isAuthenticated() && $user->getAuthenticationHash() == $_GET['auth']) {
			setUserAuthenticated($user->id);
			emailNewUser($user->id);
			Jadu_Service_User::getInstance()->logSessionIn($user->id);
			header("Location: " . getSecureSiteRootURL() . buildRegisterAcceptURL());
			exit();
		}
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Registration';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Registration</span></li>';
	
	include("register_authorisation.html.php");
?>