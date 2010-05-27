<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("egov/JaduXFormsForm.php");
	include_once("egov/JaduXFormsUserForms.php");

	if (isset($_SESSION['userID'])) {
		$allSubmittedUserForms = getAllXFormsUserFormsForUser ($_SESSION['userID'], true);
	}
	else {
		header ("Location: $ERROR_REDIRECT_PAGE");
		exit();
	}
	
	$breadcrumb = 'userFormArchive';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Form archive | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="home, homepage, index, root, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print $DOMAIN;?> is the online resource for <?php print METADATA_GENERIC_COUNCIL_NAME;?> - with council services online" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online - Tel: <?php print $address->telephone;?>" />
	<meta name="DC.description" lang="en" content="<?php print $DOMAIN;?> is the online resource for <?php print METADATA_GENERIC_COUNCIL_NAME;?> - with council services online" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (sizeof($allSubmittedUserForms) > 0) {
?>

		<h2>Your submitted forms</h2>
			
<?php
		foreach ($allSubmittedUserForms as $userForm) {
			$actualForm = getXFormsForm($userForm->formID, false);
			
			$completed = "Completed: " . $userForm->getDateFormatted('completedTimestamp', 'jS F Y');
			
			$status = "Status: ";
			if ($userForm->status == XFORMS_USER_FORM_PENDING_STATE) $status .= "Pending";
			else if ($userForm->status == XFORMS_USER_FORM_PROGRESSING_STATE) $status .= "In Progress";
			else if ($userForm->status == XFORMS_USER_FORM_COMPLETED_APPROVED_STATE) $status .= "<strong>Completed - Approved</strong>";
			else if ($userForm->status == XFORMS_USER_FORM_COMPLETED_DECLINED_STATE) $status .= "<strong>Completed - Declined</strong>";
			else if ($userForm->status == XFORMS_USER_FORM_TERMINATED_STATE) $status .= "<strong>Terminated</strong>";
	?>
			<div class="cate_info">
				<ul class="UserList">
					<li><h3><?php print $actualForm->title;?></h3></li>
					<li><?php print $status;?> | <?php print $completed;?></li>
					<li class="userComplete"><a href="<?php print $SECURE_SERVER;?>/site/scripts/user_form_info.php?userFormID=<?php print $userForm->id;?>">View submitted form</a></li>	
				</ul>
			</div>
			
<?php
		}

	}				
		
	if (sizeof($allSubmittedUserForms) == 0 && sizeof($allUnsubmittedUserForms) == 0) {
?>
		<p class="first">You have not yet submitted any forms to <?php print METADATA_GENERIC_COUNCIL_NAME;?>.</p>
<?php
	}
?>


	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->


<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>