<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduCategories.php");
	include_once("JaduMetadata.php");
	include_once("egov/JaduCL.php");
	include_once("eConsultation/JaduConsultations.php");
	
	$allNotCurrentConsultations = getAllNotCurrentConsultations (true, true);

	//	Determine how many years of data we are dealing with
	$yearArray = array();
	$consultationArray = array();
	$yearsConsultations = array();
	
	foreach ($allNotCurrentConsultations as $consultation) {
		if ($consultation->endDate > 0) {
			$year = date("Y", $consultation->endDate);
			if (!in_array($year, $yearArray)) {
				$yearArray[] = $year;
			}
			$consultationArray[$year][] = $consultation;
		}
	}

	sort($yearArray, SORT_NUMERIC);


	if(isset($_GET['viewYear'])) {
		$viewYear = (integer) $_GET['viewYear'];
	}
	else {
		$viewYear = date("Y");
		if (sizeof($yearArray) > 0) {
			$viewYear = $yearArray[sizeof($yearArray)-1];
		}
	}
	
	$yearsConsultations = $consultationArray[$viewYear];
	
	if (sizeof($yearsConsultations) <= 0) {
		if ($viewYear != date("Y")) {
			header("Location: consultation_closed.php");
			exit;
		}
		else {
			header("Location: consultation_open.php");
			exit;
		}
	}
	
	$breadcrumb = 'consultationClosed';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> closed consulations online</title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Consultation, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> closed consulations online" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> closed consulations online" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> closed consulations online" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ####################################### -->

	<p class="first">The Council is committed to engaging in effective community consultation.  Listening to the views and opinions of residents and using these to help to shape our policies and plans.</p>
	<p>Please come back and view the register regularly to:</p>
	<ul class="list">
		<li>View the details of consultations past, present and future</li>
		<li>Take part in more opportunities to have your say</li>
		<li>See the results of consultations.</li>		
	</ul>

	<p><a href="http://<?php print $DOMAIN;?>/site/scripts/consultation_open.php">View open consultations.</a></p>
			
<?php
	if (sizeof($yearArray) > 1) {
 		print '<p>View by year: ';
		foreach ($yearArray as $yearIndex => $year) {
			if ($year != $viewYear) { 
?>
		<a href="http://<?php print $DOMAIN;?>/site/scripts/consultation_closed.php?viewYear=<?php print $year;?>"><?php print $year;?></a>
<?php 
			} 
			else { 
				print $year;
			} 
			if ($yearIndex < sizeof($yearArray)-1) { 
				print " | "; 
			} 
		}
		print '</p>';
	}
?>

	<h2><?php print $viewYear;?></h2>

<?php 
	foreach ($yearsConsultations as $consultation) {
?>
	<div class="search_result">
		<h3><a href="<?php print CONSULTATIONS_PUBLIC_FOLDER.$consultation->folderName."/index.php";?>"><?php print $consultation->title; ?></a></h3>
<?php 
		if ($consultation->startDate > 0) {
			print "<p class=\"date\">Published: " . $consultation->getConsultationDate('start') . " Closing date for responses: " . $consultation->getConsultationDate('end') . "</p>";
		}
?>
	</div>
<?php
	}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>