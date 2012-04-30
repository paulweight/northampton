<?php
	include_once("utilities/JaduStatus.php");
	
	// Force onto SSL if required.
	if ((defined('SSL_ENABLED') && SSL_ENABLED) && PROTOCOL != 'https://') {
		header("Location: ".getSecureSiteRootURL().$_SERVER['REQUEST_URI']);
		exit;
	}
	
	include_once("JaduStyles.php"); 
	include_once("egov/JaduXFormsForm.php");
	include_once("egov/JaduXFormsUserForms.php");

	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
		$allSubmittedUserForms = getAllXFormsUserFormsForUser(Jadu_Service_User::getInstance()->getSessionUserID(), true);
	}
	else {
		header ("Location: $ERROR_REDIRECT_PAGE");
		exit();
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Online Form archive';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSecureSiteRootURL() . buildUserHomeURL().'">Your Account</a></li><li class="bc_end"><span>Online form archive</span></li>';
	
	include("user_form_archive.html.php");
?>