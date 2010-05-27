<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduCategories.php");
	include_once("JaduMetadata.php");
	include_once("egov/JaduCL.php");
	include_once("eConsultation/JaduConsultations.php");
	
	$allCurrentConsultations = getAllCurrentPublicConsultations (true, true);
	$notCurrentConsultations = getAllNotCurrentConsultations (true, true);
	
	$breadcrumb = 'consultationOpen';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> open consulations online</title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Consultation, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> open consulations online" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> open consulations online" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> open consulations online" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			

	<p class="first">The Council is committed to engaging in effective community consultation.  Listening to the views and opinions of residents and using these to help to shape our policies and plans.</p>
	<p>Please come back and view the register regularly to:</p>
	<ul class="list">
		<li>View the details of consultations past, present and future</li>
		<li>Take part in more opportunities to have your say</li>
		<li>See the results of consultations.</li>		
	</ul>
<?php 	
	if (sizeof($notCurrentConsultations) > 0) { 
?>
	<p><a href="http://<?php print $DOMAIN;?>/site/scripts/consultation_closed.php">View closed consultations.</a></p>

<?php 
	} 
	
	if (sizeof($allCurrentConsultations) < 1) {
		print "<p class=\"first\">There are no consultations currently in progress.</p>";
	}
			
	foreach ($allCurrentConsultations as $consultation) {
?>
	<div class="search_result">
		<h3><a href="<?php print CONSULTATIONS_PUBLIC_FOLDER.$consultation->folderName."/index.php";?>"><?php print $consultation->title; ?></a></h3>
<?php
		if ($consultation->startDate > 0) {
			print "<p class=\"date\">Published:" . $consultation->getConsultationDate('start') . ".  Closing date for responses: " . $consultation->getConsultationDate('end') . "</p>";
		}
?>
		<div class="byEditor">
			<?php // print $consultation->description;?>
<?php
			if ($consultation->allowNotificationSignups > 0) {
?>
			<p><a href="http://<?php print $DOMAIN;?>/site/scripts/consultation_notification.php?consultationID=<?php print $consultation->id;?>">Sign-up for email alerts on this consultation </a></p>
<?php
			}
?>
		</div>
	</div>
<?php
	}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>