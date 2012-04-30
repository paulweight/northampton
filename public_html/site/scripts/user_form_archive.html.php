<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="home, homepage, index, root, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print DOMAIN; ?> is the online resource for <?php print encodeHtml(METADATA_GENERIC_NAME); ?> - with council services online" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Online - Tel: <?php print encodeHtml($address->telephone);?>" />
	<meta name="DC.description" lang="en" content="<?php print DOMAIN; ?> is the online resource for <?php print encodeHtml(METADATA_GENERIC_NAME); ?> - with council services online" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
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
			
			$completed = "Completed: " . $userForm->getDateFormatted('completedTimestamp', FORMAT_DATE_LONG);
			
			$status = "Status: ";
			if ($userForm->status == XFORMS_USER_FORM_PENDING_STATE) $status .= "Pending";
			else if ($userForm->status == XFORMS_USER_FORM_PROGRESSING_STATE) $status .= "In progress";
			else if ($userForm->status == XFORMS_USER_FORM_COMPLETED_APPROVED_STATE) $status .= "Completed and approved</span>";
			else if ($userForm->status == XFORMS_USER_FORM_COMPLETED_DECLINED_STATE) $status .= "Completed and declined</span>";
			else if ($userForm->status == XFORMS_USER_FORM_TERMINATED_STATE) $status .= "Terminated";
	?>

	<h3><?php print $actualForm->title;?></h3>
	<ul>
		<li><?php print $status;?> | <?php print $completed;?></li>
		<li><a href="<?php print getSiteRootURL() . buildUserFormURL($userForm->id);?>">View submitted form</a></li>	
	</ul>
	
<?php
		}

	}				
		
	if (sizeof($allSubmittedUserForms) == 0) {
?>
		<p>You have not yet submitted any forms to <?php print encodeHtml(METADATA_GENERIC_NAME); ?>.</p>
<?php
	}
?>	 

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>