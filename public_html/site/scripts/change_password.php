<?php
	include_once('utilities/JaduStatus.php');
	
	// Force onto SSL if required.
	if ((defined('SSL_ENABLED') && SSL_ENABLED) && PROTOCOL != 'https://') {
		header("Location: ".getSecureSiteRootURL().$_SERVER['REQUEST_URI']);
		exit;
	}
	
	include_once('JaduStyles.php');
	include_once('JaduLibraryFunctions.php');
	include_once('marketing/JaduUsers.php');
	include_once('marketing/JaduPHPBB3.php');
	
	if (!Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
		header("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}
	
	if (!Jadu_Service_User::getInstance()->canUpdateUserPassword()) {
		header('Location: ' . buildUserHomeURL());
		exit();
	}
	
	$error_array = array();
	$showMessage = false;
	
	if (isset($_POST['oldPassword']) && isset($_POST['password']) && isset($_POST['passwordConfirm'])) {
		$user = Jadu_Service_User::getInstance()->getSessionUser();
		
		if ($user->password != getPasswordHash($_POST['oldPassword'])) {
			$error_array['oldPassword'] = true;
		}
				
		if ($_POST['passwordConfirm'] != $_POST['password']) {
			$error_array['passwordsDifferent'] = true;
		}

		$passLength = mb_strlen($_POST['password']);
		if ($passLength < 6 || $passLength > 30) {
			$error_array['passwordLength'] = true;
		}
		
		if (sizeof($error_array) == 0) {
			Jadu_Service_User::getInstance()->updateUserPassword($user->id, $_POST['password']);
			$showMessage = true;
			
			if (isset($_POST['forced'])) {
				$user->forcePasswordReset = 0;
				updateUser($user);
			}	
			
			if (defined('PHPBB_INTEGRATION') && PHPBB_INTEGRATION == true) {
			    $user->password = $_POST['password'];
			    updatePHPBBPassword($user);
			}
		}
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Change password';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSecureSiteRootURL() .buildUserHomeURL() .'">Your account</a></li><li><span>Change password</span></li>';
	
	include("change_password.html.php");
?>