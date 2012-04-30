<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");
	include_once("recruitment/JaduRecruitmentJobsDownloads.php");
	include_once("marketing/JaduAdverts.php");
	
	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()){
		$user = Jadu_Service_User::getInstance()->getSessionUser();
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
<html<?php if (TEXT_DIRECTION == 'rtl') print ' dir="rtl"'; ?> xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
	<title>Jobs at <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="jobs, recruitment, application, job, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="Jobs currently available at <?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />

	<meta name="DC.title" lang="en" content="Jobs at <?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />
	<meta name="DC.description" lang="en" content="Jobs currently available at <?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
		
			<p class="first"><span class="b">This vacancy is:</span> <?php print encodeHtml($job->getTypeString());?></p>
			<p class="first"><span class="b">Salary:</span> <?php print encodeHtml($job->salary); ?></p>
			<p class="first"><span class="b">Location:</span> <?php print encodeHtml($job->location); ?></p>
			<p class="first"><span class="b">Closing Date:</span> <?php print date("l jS F Y", $job->closingDate);?></p>
			<p><span class="b">Description:</span> <?php print nl2br(encodeHtml($job->description));?></p>
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
				<p class="first"><a href="<?php print "http://" . DOMAIN . "/downloads/".encodeHtml($download->filename); ?>"><?php print encodeHtml($download->title); ?></a> <?php print encodeHtml($extension); ?></p>
			
<?php
			}
?>
			</div>
<?php
			}
?>
			<div class="clear"></div>
			
			<!-- The Contact box -->
			<?php include("../includes/contactbox.php"); ?>
			<!-- END of the Contact box -->
			
<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>