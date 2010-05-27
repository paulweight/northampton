<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");
	include_once("recruitment/JaduRecruitmentJobsDownloads.php");
	include_once("marketing/JaduAdverts.php");
	
	if (isset($userID)){
		$user = getUser($userID);
	}
	
	$job = getRecruitmentJob($id);
	$cat = getRecruitmentCategory($job->categoryID);
	
	if (isset($id) && $id != "") {
		$recruitmentCategory = getRecruitmentCategory($id);
		$jobs = getAllRecruitmentLiveJobsInCategory($id);
	}

	$startedApplication = -1;

	// see if the user has already got an application for this job
	if (isset($_GET['id']) && (isset($user))){
		$startedApplication = hasUserStartedApplication($user->id, $_GET['id']);
	}
	
	$downloads = getJobDownloads($_GET['id']);
	
	$breadcrumb = 'recruitDetails';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
	<title>Jobs at <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="jobs, recruitment, application, job, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="Jobs currently available at <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.title" lang="en" content="Jobs at <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />
	<meta name="DC.description" lang="en" content="Jobs currently available at <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
		
			<p class="first"><span class="b">This vacancy is:</span> <?php print $job->getTypeString();?></p>
			<p class="first"><span class="b">Salary:</span> <?php print htmlentities($job->salary);?></p>
			<p class="first"><span class="b">Location:</span> <?php print $job->location;?></p>
			<p class="first"><span class="b">Closing Date:</span> <?php print date("l jS F Y", $job->closingDate);?></p>
			<p><span class="b">Description:</span> <?php print nl2br(htmlentities( $job->description));?></p>
<?php
			if ($downloads) {
			global $DOMAIN;
?>
			<div class="infobox">
				<div class="infobox_h">IMPORTANT INFORMATION</div>
				<div class="first">Please download and read the following information before proceeding with your application:</div>
<?php 
			foreach ($downloads as $download) {
?>
				<p class="first"><a href="<?php print "http://" . $DOMAIN . "/downloads/".$download->filename; ?>"><?php print $download->title; ?></a> <?php print $extension;?></p>
			
<?php
			}
?>
			</div>
<?php
			}
?>
			<div class="clear"></div>
			
			<!-- <?php	if (($startedApplication == -1) && isset($userID)) { ?>
			<div class="infobox">
				<div class="infobox_h"><a href="application_details.php?appID=-1&jobID=<?php print $job->id;?>">APPLY FOR THIS JOB NOW</a></div>
				<p>Apply online and keep track of your application.  Your account details will be saved, making your next application even faster to complete.</p>
			<?php
			} 
			else if (!isset($userID)) {
			?>
			<div class="infobox">
				<div class="infobox_h"><a href="register.php">REGISTER FIRST TO APPLY</a></div>
				<p>To Apply for this job you need to be registered or signed-in.</p>
			<?php
			}
			else if(($startedApplication != -1) &&isset($userID)) {
			?>
			<div class="infobox">
				<div class="infobox_h"><a href="application_details.php?appID=<?php print $startedApplication;?>">VIEW APPLICATION DETAILS</a></div>
				<p>Continue with this application or view the completed form.</p>
			<?php
			}
			?>
			</div> -->
			
			<!-- The Contact box -->
			<?php include("../includes/contactbox.php"); ?>
			<!-- END of the Contact box -->
			
<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>