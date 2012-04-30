<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");

	$cookieDomain = DOMAIN;

	if (isset($_POST["saveButton"])) {
		if (isset($_POST["colourScheme"])) {
			setcookie("userColourscheme", $_POST["colourScheme"], time() + 3600, "/", $cookieDomain);
			$_COOKIE['userColourscheme'] = $_POST["colourScheme"];
		}

		if (isset($_POST["fontSize"])) {	
			setcookie("userFontsize", $_POST["fontSize"], time() + 3600, "/", $cookieDomain);	
			$_COOKIE['userFontsize'] = $_POST["fontSize"];
		}
	
		if (isset($_POST["fontChoice"])) {	
			setcookie("userFontchoice", $_POST["fontChoice"], time() + 3600, "/", $cookieDomain);	
			$_COOKIE['userFontchoice'] = $_POST["fontChoice"];
		}
		
		if (isset($_POST["letterSpacing"])) {	
			setcookie("userLetterspacing", $_POST["letterSpacing"], time() + 3600, "/", $cookieDomain);	
			$_COOKIE['userLetterspacing'] = $_POST["letterSpacing"];
		}	
	
		if (isset($_POST["userLayout"])) {	
			setcookie("userLayout", $_POST["Layout"], time() + 3600, "/", $cookieDomain);	
			$_COOKIE['userLayout'] = $_POST["Layout"];	
		}
	}

	if (isset($_POST["resetButton"])) {	
		setcookie("userColourscheme", "", time() - 3600, "/", $cookieDomain);
		unset($_COOKIE['userColourscheme']);
		setcookie("userFontsize", "", time() - 3600, "/", $cookieDomain);
		unset($_COOKIE['userFontsize']);		
		setcookie("userFontchoice", "", time() - 3600, "/", $cookieDomain);	
		unset($_COOKIE['userFontchoice']);	
		setcookie("userLetterspacing", "", time() - 3600, "/", $cookieDomain);	
		unset($_COOKIE['userLetterspacing']);		
		setcookie("userLayout", "", time() - 3600, "/", $cookieDomain);	
		unset($_COOKIE['userLayout']);		
	}
		
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Change colours and fonts';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Change colours and fonts</span></li>';
	
	include("user_settings.html.php");
?>