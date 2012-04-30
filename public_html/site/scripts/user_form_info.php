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
	include_once("egov/JaduXFormsUserQuestionAnswers.php");

	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
		$allSubmittedUserForms = getAllXFormsUserFormsForUser (Jadu_Service_User::getInstance()->getSessionUserID(), true);
		$allUnsubmittedUserForms = getAllXFormsUserFormsForUser (Jadu_Service_User::getInstance()->getSessionUserID(), false);

		if (isset($_GET['userFormID']) && is_numeric($_GET['userFormID'])) {
			$userForm = getXFormsUserForm($_GET['userFormID']);
			if ($userForm && $userForm->userID == Jadu_Service_User::getInstance()->getSessionUserID()) {
				$allAnswers = getAllXFormsQuestionAnswersForForm ($userForm->id);
				$form = getXFormsForm($userForm->formID, false);
			}
			else {
				header ("Location: $ERROR_REDIRECT_PAGE");
				exit;
			}
		}
		else {
			header ("Location: $ERROR_REDIRECT_PAGE");
			exit;
		}
				
	}
	else {
		header ("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}
	
	$breadcrumb = 'userFormInfo';
	
	include("user_form_info.html.php");
?>
