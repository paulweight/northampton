<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentJobsDownloads.php");
	include_once("marketing/JaduAdverts.php");
	
	if (!isset($_GET['jobID']) || $_GET['jobID'] < 1) {
		header('Location: ' . buildJobsURL());
	}
	
	// Default to approved/live
	$approvedOnly = true;
	$liveOnly = true;
	
	// Check whether an administrator is previewing the page
	if (isset($_GET['adminID']) && isset($_GET['preview']) && isset($_GET['expire'])) {
		include_once('utilities/JaduAdministrators.php');
		$approvedOnly = $liveOnly = !validateAdminPreviewHash(getAdministrator($_GET['adminID']), $_GET['preview'], $_GET['expire']);
	}
	
	$job = getRecruitmentJob($_GET['jobID'], true, $liveOnly, $approvedOnly);
	if ($job->id > 0) {
		$cat = getRecruitmentCategory($job->categoryID);
	}
	
	$downloads = getJobDownloads($_GET['jobID']);
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Current vacancies';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildJobsURL() . '">Current vacancies</a></li><li><span>'.encodeHtml($job->title).'</span></li>';
	
	include("recruit_details.html.php");
?>