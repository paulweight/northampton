<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("marketing/JaduAdverts.php");
	
	// Get all the job categories
	$categories = getAllRecruitmentCategories();
	$categoryIDs = array();
	foreach ($categories as $category) {
		$categoryIDs[] = $category->id;
	}
	
	// Get the jobs
	$jobs = getAllRecruitmentJobsInCategories($categoryIDs, true, true, true);
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Current vacancies';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Current vacancies</span></li>';
	
	include("recruit_jobs.html.php");
?>