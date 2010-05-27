<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("planXLive/JaduPlanXApplications.php");
	include_once("planXLive/JaduPlanXSiteVisits.php");
	include_once("planXLive/JaduPlanXConfiguration.php");
	include_once("planXLive/JaduPlanXConsultees.php");
	include_once("planXLive/JaduPlanXConstraints.php");
	include_once("planXLive/JaduPlanXApplicationDownloads.php");
	include_once("planXLive/JaduPlanXTrackedApplications.php");

	if (isset($_POST['appNumber'])) {
	   $appNumber = $_POST['appNumber'];
	   $app = getPlanningApplication($appNumber, "applicationNumber");
	}
	elseif (isset($_GET['appID'])) {
	   $app = getPlanningApplication($_GET['appID'], "id");
	}
	else {
        header("Location: ./planx_search.php?noResults=true");
        exit();
	}
	
	if ($app->id == '-1') {
        header("Location: ./planx_search.php?noResults=true");
        exit();
	}

	$config = getAllPlanXConfigurationValues();
	$uniqueField = $config['uniqueApplicationField']->value;

	$siteVisits = getAllSiteVisits($app->$uniqueField);
	$consultees = getAllConsultees($app->$uniqueField);
	$downloads = getDownloadsForApplication($app->$uniqueField);
	$constraints = getAllConstraintsForApplication($app->$uniqueField);
	
	$breadcrumb = 'planxDetails';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Planning Application</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of documents and pages organised within the following categories, Environment, Planning" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> online information | Environment | Planning" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of documents and pages organised within the following categories, Environment, Planning" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	
	<h2>Application Details</h2>

<?php 
		$decisionSwitch = $app->getFormattedValueForField('decisionType');
		if (empty($decisionSwitch)) {
?>
		<p><span class="comment"><a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_comment.php?appID=<?php print $app->id; ?>">Comment on this application</a></span></p>	
<?php
		}
		if (!isUserTrackingApplication($_SESSION['userID'], $app->id) || !isset($_SESSION['userID'])) {
?>
		<p><span class="user"><a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_track.php?appID=<?php print $app->id; ?>">Track this application</a></span></p>
<?php
		}
?>
		<table summary="Planning Application Details">
			<thead>
				<tr>
					<th>Field Name</th>
					<th>Information</th>
				</tr>
			</thead>
			<tbody>		
				<tr>
					<td>Application Type:</td>
					<td><?php print $app->getFormattedValueForField('applicationType'); ?></td>
				</tr>
				<tr>
					<td>Development Description:</td>
					<td><?php print $app->getFormattedValueForField('developmentDescription'); ?></td>
				</tr>
				<tr>
					<td>Development Address:</td>
					<td><?php print $app->getFormattedValueForField('developmentAddress'); ?></td>
				</tr>
				<tr>
					<td>Ward:</td>
					<td><?php print $app->getFormattedValueForField('ward'); ?></td>
				</tr>
				<tr>
					<td>Parish:</td>
					<td><?php print $app->getFormattedValueForField('parish'); ?></td>
				</tr>
				<tr>
					<td>Applicant Name:</td>
					<td><?php print $app->getFormattedValueForField('applicantName'); ?></td>
				</tr>
				<tr>
					<td>Applicant Address:</td>
					<td><?php print $app->getFormattedValueForField('applicantAddress'); ?></td>
				</tr>
				<tr>
					<td>Agent Name:</td>
					<td><?php print $app->getFormattedValueForField('agentName'); ?></td>
				</tr>
				<tr>
					<td>Agent Address:</td>
					<td><?php print $app->getFormattedValueForField('agentAddress'); ?></td>
				</tr>
				<tr>
					<td>Officer Name:</td>
					<td><?php print $app->getFormattedValueForField('officerName'); ?></td>
				</tr>
				<tr>
					<td>Officer Telephone Number:</td>
					<td><?php print $app->getFormattedValueForField('officerTelephoneNumber'); ?></td>
				</tr>
				<tr>
					<td>Officer Email Address:</td>
					<td><?php print $app->getFormattedValueForField('officerEmailAddress'); ?></td>
				</tr>
				<tr>
					<td>Date Received:</td>
					<td><?php print $app->getFormattedValueForField('receivedDate'); ?></td>
				</tr>
				<tr>
					<td>Date Registered:</td>
					<td><?php print $app->getFormattedValueForField('registeredDate'); ?></td>
				</tr>
				<tr>
					<td>Valid Date:</td>
					<td><?php print $app->getFormattedValueForField('validDate'); ?></td>
				</tr>
				<tr>
					<td>Committee Date:</td>
					<td><?php print $app->getFormattedValueForField('committeeDate'); ?></td>
				</tr>
				<tr>
					<td>Planning Status:</td>
					<td><?php print $app->getFormattedValueForField('planningStatus'); ?></td>
				</tr>
				<tr>
					<td>Decision Type:</td>
					<td><?php print $app->getFormattedValueForField('decisionType'); ?></td>
				</tr>
				<tr>
					<td>Decision Date:</td>
					<td><?php print $app->getFormattedValueForField('decisionDate'); ?></td>
				</tr>
			</tbody>
		</table>

<?php
		if (sizeof($consultees) > 0) {
?>
		<h3 id="pxconsultation">Consultation</h3>
		<table summary="Consultation on this application">
			<thead>
				<tr>
					<th>Consultee Address</th>
					<th>Date Consulted</th>
					<th>Consultation Expiry Date</th>
				</tr>
			</thead>
			<tbody>
<?php
			foreach ($consultees as $consultee) {
?>
				<tr>
					<td><?php print $consultee->address; ?></td>
					<td><?php print date($config['defaultDateFormat']->value, $consultee->dateConsulted); ?></td>
					<td><?php print date($config['defaultDateFormat']->value, $consultee->consultationExpiryDate); ?></td>
				</tr>
<?php
			}
?>
			</tbody>
		</table>
<?php
		}
?>

<?php
		if (sizeof($constraints) > 0) {
?>		
		<h3 id="pxconstraints">Constraints</h3>
		<table summary="Constraints on this application">
			<thead>
				<tr>
					<th>Constraint Name</th>
				</tr>
			</thead>
			<tbody>	
<?php
			foreach ($constraints as $constraint) {
?>
				<tr>
					<td><?php print $constraint->constraint; ?></td>
				</tr>
<?php
			}
?>
			</tbody>
		</table>

<?php
		}
?>

<?php
		if (sizeof($siteVisits) > 0) {
?>
		<h3 id="pxsitevisit">Site Visit</h3>
		<table summary="Site Visit information on this application">
			<thead>
				<tr>
					<th>Site Visit Date</th>
					<th>Comments</th>
				</tr>
			</thead>
			<tbody>
<?php
			foreach ($siteVisits as $visit) {
?>
				<tr>
					<td><?php print date($config['defaultDateFormat']->value, $visit->siteVisitDate); ?></td>
					<td><?php print $visit->comments; ?></td>
				</tr>
<?php
			}
?>
			</tbody>
		</table>
<?php
		}
?>

<?php
		if ($app->getFormattedValueForField('appealDateReceived') > 0) {
?>			
		<h3 id="pxappeals">Appeal</h3>	
		<table summary="Details of the Appeal on this Application">
			<thead>
				<tr>
					<th>Field Name</th>
					<th>Information</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Appeal Received:</td>
					<td><?php print $app->getFormattedValueForField('appealDateReceived'); ?></td>
				</tr>
				<tr>
					<td>Appeal Type:</td>
					<td><?php print $app->getFormattedValueForField('appealType'); ?></td>
				</tr>
				<tr>
					<td>Decision Type:</td>
					<td><?php print $app->getFormattedValueForField('appealDecision'); ?></td>
				</tr>
				<tr>
					<td>Decision Date:</td>
					<td><?php print $app->getFormattedValueForField('appealDecisionDate'); ?></td>
				</tr>
			</tbody>
		</table>
<?php
		}
?>

<?php
		if (sizeof($downloads) > 0) {
?>
		<h3 id="pxdownloads">Download</h3>	
		<table summary="download information on this application.">
			<thead>
				<tr>
					<th>Name</th>
					<th>Further Information</th>
				</tr>
			</thead>
			<tbody>
<?php
			foreach ($downloads as $download) {
?>
				<tr>
					<td><a href="http://<?php print $DOMAIN; ?>/planx_downloads/<?php print $download->filename; ?>"><?php print $download->title; ?></a></td>
					<td><?php print $download->filename; ?></td>
				</tr>
<?php
			}
?>
			</tbody>
		</table>
<?php
		}
?>

<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/closing.php"); ?>