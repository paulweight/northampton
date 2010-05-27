<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	
	include_once("egov/JaduXFormsForm.php");
	include_once("egov/JaduXFormsUserForms.php");
	include_once("egov/JaduXFormsUserQuestionAnswers.php");

	if (isset($_SESSION['userID'])) {
		$allSubmittedUserForms = getAllXFormsUserFormsForUser ($_SESSION['userID'], true);
		$allUnsubmittedUserForms = getAllXFormsUserFormsForUser ($_SESSION['userID'], false);

		if (isset($_GET['userFormID']) && is_numeric($_GET['userFormID'])) {
			$userForm = getXFormsUserForm($_GET['userFormID']);
			if ($userForm && $userForm->userID == $_SESSION['userID']) {
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
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $form->title;?> | Form archive | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="forms, form, application, archive, archives, <?php print $form->title;?>, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> online user forms archive" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> form archive - <?php print $form->title;?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> online user forms archive" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
						
			<table summary="This table displays the details of a submitted online form">
				<tr>
					<th>Form:</th>
					<td><?php print $form->title;?></td>
				</tr>
				<tr>
					<th>Completed:</th>
					<td><?php print $userForm->getDateFormatted();?></td>
				</tr>
				<tr>
					<th>Status:</th>
					<td>
<?php
						if ($userForm->status == XFORMS_USER_FORM_PENDING_STATE) print "<strong>Pending</strong>";
						else if ($userForm->status == XFORMS_USER_FORM_PROGRESSING_STATE) print "<strong>In Progress</strong>";
						else if ($userForm->status == XFORMS_USER_FORM_COMPLETED_APPROVED_STATE) print "<strong>Completed - Approved</strong>";
						else if ($userForm->status == XFORMS_USER_FORM_COMPLETED_DECLINED_STATE) print "<strong>Completed - Declined</strong>";
						else if ($userForm->status == XFORMS_USER_FORM_TERMINATED_STATE) print "<strong>Terminated</strong>";
?>
					</td>
				</tr>
			</table>

			<table>
<?php
			foreach ($allAnswers as $answer) {
?>
				<tr>
					<td>
<?php
						print $answer->question;
						if ($answer->elementNumber != -1) {
							print " <em>(".($answer->elementNumber+1).")</em>";
						}
?>
						
					</td>
					<td>
<?php
						if ($answer->answer == "")
							print "&nbsp;";
						else
							print nl2br($answer->answer);
?>
					</td>
				</tr>
<?php
			}
?>
			</table>
			
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
					 
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
