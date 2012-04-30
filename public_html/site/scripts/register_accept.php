<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");

	if (!Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
		header("Location: $ERROR_REDIRECT_PAGE");
		exit();
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Registration complete';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSecureSiteRootURL() . buildUserHomeURL().'">Your account</a></li><li><span>Registration complete</span></li>';

	include("register_accept.html.php");
?>