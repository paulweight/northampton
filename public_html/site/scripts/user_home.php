<?php
	include_once("utilities/JaduStatus.php");
	
	// Force onto SSL if required.
	if ((defined('SSL_ENABLED') && SSL_ENABLED) && PROTOCOL != 'https://') {
		header("Location: ".getSecureSiteRootURL().$_SERVER['REQUEST_URI']);
		exit;
	}
	
	include_once("JaduStyles.php"); 
	include_once('utilities/JaduModulePages.php');
	include_once("egov/JaduXFormsForm.php");
	include_once("egov/JaduXFormsUserForms.php");
	include_once("egov/JaduXFormsUserQuestionAnswers.php");
	
	$confirmRemove = false;

	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
			
		if (isset($_GET['userFormID'])) {
			$userForm = getXFormsUserForm($_GET['userFormID']);
			
			if ($userForm->userID != Jadu_Service_User::getInstance()->getSessionUserID()) {
				header ("Location: $ERROR_REDIRECT_PAGE");
				exit();
			}
			
			$allAnswers = getAllXFormsQuestionAnswersForForm ($userForm->id);
			$form = getXFormsForm($userForm->formID, true);
			
			if (isset($_GET['remove']) && $_GET['remove'] == "true") {
				deleteXFormsUserForm($userForm->id);
			}
		}
		elseif (isset($_GET['userAppID']) || isset($_POST['userAppID'])) {
			if (isset($_GET['remove']) && $_GET['remove'] == "true" && !isset($_POST['confirmRemove'])) {
				$app = getApplication($_GET['userAppID']);

				if ($app != null) {
					$confirmRemove = true;
				}
			}
			elseif(isset($_POST['confirmRemove'])) {
				$app = getApplication($_POST['userAppID']);

				// check that the logged in user owns this application
				if ($app->userID == Jadu_Service_User::getInstance()->getSessionUserID()) {
					deleteApplication($_POST['userAppID']);
				}
				unset($app);
			}
		}

		$allSubmittedUserForms = getAllXFormsUserFormsForUser (Jadu_Service_User::getInstance()->getSessionUserID(), true);
		$allUnsubmittedUserForms = getAllXFormsUserFormsForUser (Jadu_Service_User::getInstance()->getSessionUserID(), false);

		// directory entries
		$directoryEntries = array();
		if (getModulePageFromName('Directories')->id != -1) {
			include_once("directoryBuilder/JaduDirectoryEntries.php");
			include_once("directoryBuilder/JaduDirectoryUserEntries.php");
			include_once("directoryBuilder/JaduDirectories.php");
			
			$unapprovedDirectoryEntries = getAllDirectoryUserEntriesForUser(Jadu_Service_User::getInstance()->getSessionUserID());
			$liveDirectoryEntries = getAllDirectoryEntriesForUser(Jadu_Service_User::getInstance()->getSessionUserID());

			$directoryEntries = array_merge($liveDirectoryEntries, $unapprovedDirectoryEntries);
		}
	}
	else {
		header ("Location: $ERROR_REDIRECT_PAGE");
		exit();
	}

	$MAST_HEADING = 'Your Account';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Your account</span></li>';
	
	include("user_home.html.php");
?>
