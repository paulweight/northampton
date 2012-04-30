<?php 
	include_once("utilities/JaduStatus.php");
	
	// Force onto SSL if required.
	if ((defined('SSL_ENABLED') && SSL_ENABLED) && PROTOCOL != 'https://') {
		header("Location: ".getSecureSiteRootURL().$_SERVER['REQUEST_URI']);
		exit;
	}
	
	include_once("JaduStyles.php");
	
	$referer = null;
	if (isset($_REQUEST['referer']) && strpos($_REQUEST['referer'], DOMAIN) !== false && strpos($_REQUEST['referer'], 'logout') === false) {
		$referer = $_REQUEST['referer'];
	}
	else if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
		$referer = $_SERVER['HTTP_REFERER'];
	}

	if (isset($_GET['logout'])) {
		Jadu_Service_User::getInstance()->logSessionOut();
		
		if (defined('PHPBB_INTEGRATION') && PHPBB_INTEGRATION == true) {
			header('Location: http://' . DOMAIN . '/site/scripts/phpbb_login.php?logout=true');
			exit();
		}
	} 
	else if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
		if (isset($referer)) {
			header('Location: ' . $referer);
			exit();
		}
		else {
			header('Location: http://' . DOMAIN);
			exit();
		}

		header('Location: ' . getSiteRootURL());
	    exit();
	}
	
	if (isset($_GET['loginFailed'])) {
		$_GET['sign_in'] = 'true';
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Sign in to your account';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Sign in to your account</span></li>';
	
	include("signin.html.php");
?>