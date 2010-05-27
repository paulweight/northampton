<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	//include_once("recruitment/JaduRecruitmentApplications.php");
	include_once("recruitment/JaduRecruitmentJobsDownloads.php");
	include_once("marketing/JaduAdverts.php");
	
	if (isset($_REQUEST['id']) && !is_numeric($_REQUEST['id'])) {
		header("Location:  recruit_jobs.php");
	}
	
	if (isset($userID)){
		$user = getUser($userID);
	}
	
	$job = getRecruitmentJob($id);
	if ($job->id > 0) {
		$cat = getRecruitmentCategory($job->categoryID);
	
		if (isset($id) && $id != "") {
			$recruitmentCategory = getRecruitmentCategory($id);
			$jobs = getAllRecruitmentLiveJobsInCategory($id);
		}
	}

/*	$startedApplication = -1;

	// see if the user has already got an application for this job
	if (isset($_GET['id']) && (isset($user))){
		$startedApplication = hasUserStartedApplication($user->id, $_GET['id']);
	}*/
	
	$downloads = getJobDownloads($_GET['id']);
	
	$breadcrumb = 'recruitDetails';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
	<title><?php print $job->title;?> | Jobs at <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="jobs, recruitment, application, job, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="Jobs currently available at <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.title" lang="en" content="Jobs at <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />
	<meta name="DC.description" lang="en" content="Jobs currently available at <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($job->id == null) {
?>
		<p class="first">Sorry this vacancy is no longer available</p>
<?php
	}
	
	else {
?>		
	<h2><?php print htmlentities($job->title);?></h2>
	<p class="first">This vacancy is: <?php print $job->getTypeString();?></p>
	<p class="first">Salary: <?php print htmlentities($job->salary);?></p>
	
<?php 
		if ($job->location != "") { 
?>	
	<p class="first">Location: <?php print $job->location;?></p>	
<?php 
		} 
?>	
	<p class="first">Closing Date: <?php print date("l jS F Y", $job->closingDate);?></p>
	<p><strong>Description:</strong> <?php print nl2br(htmlentities($job->description));?></p>
	
<?php
		if (sizeof($downloads) > 0 ) {
?>
	<div class="content_box">
		<h3>Important Information</h3>
		<p>Please download and read the following information before proceeding with your application:</p>
<?php 
			foreach ($downloads as $download) {
?>
		<p>&raquo; <a href="http://<?php print $DOMAIN; ?>/downloads/<?php print $download->filename; ?>"><?php print $download->title; ?></a> <?php print $extension;?></p>
<?php
			}
?>
	</div>
<?php
		}

		/* Set the close date to midnight of the NEXT day */
		$CloseDate=$job->closingDate+86400;

?>
	<div class="content_box">
		<ul>
<?php	
		if (($startedApplication == -1) && isset($userID) && $CloseDate > date("U")) { 
?>			
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/application_details.php?appID=-1&jobID=<?php print $job->id;?>">APPLY FOR THIS JOB NOW</a></li>
<?php
		} 
	
		else if (!isset($userID) && $CloseDate > date("U")) {
?>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/register.php">Register</a> - To Apply for a position you need to be registered.</li>											
<?php
		}
	
		else if(($startedApplication != -1) &&isset($userID)) {
?>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/application_details.php?appID=<?php print $startedApplication;?>">View application details</a></li>
<?php
		}
	
		else if ($job->closingDate <= date("U")) {
?>
			<li>Applications for this job <strong>have now closed</strong></li>
<?php
		}
?>
		</ul>
	</div>
<?php
	}
?>
	<p class="note"><?php print METADATA_GENERIC_COUNCIL_NAME;?> is an equal opportunities employer.</p>
			
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>