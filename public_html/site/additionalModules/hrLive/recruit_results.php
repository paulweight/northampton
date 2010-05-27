<?php
	session_start();
	include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");
	include_once("marketing/JaduUsers.php");
	include_once("hrlive/JaduHRLiveCategories.php");
	include_once("hrlive/JaduHRLiveJobsToCategories.php");
	include_once("hrlive/JaduHRLiveJobsToRoles.php");
	include_once("hrlive/JaduHRLiveJobs.php");
	include_once("hrlive/JaduHRLiveEmailAlerts.php");
	include_once("hrlive/JaduHRLiveJobTypes.php");
	include_once("hrlive/JaduHRLiveJobPeriods.php");

	if (isset($userID)) {
		$user = getUser($userID);
	}

	$jobs_1 = array();
	$jobs_2 = array();
	
	if (!empty($_GET['categoryIDs'])) {
		$jcs = array();
		foreach ($_GET['categoryIDs'] as $catID) {
			if (!empty($catID)) {
				$jcs = array_merge($jcs, getAllJobsToCategoriesForCategory($catID));
			}
		}
		foreach ($jcs as $jc) {
			$jobs_2[] = getJob($jc->jobID);
		}
	}

	if (!empty($_GET['roleIDs'])) {
		$jrs = array();
		foreach ($_GET['roleIDs'] as $roleID) {
			if (!empty($roleID)) {
				$jrs = array_merge($jrs, getAllJobToRolesForRole($roleID));
			}
		}
		foreach ($jrs as $jr) {
			$jobs_1[] = getJob($jr->jobID);
		}
	}

	$tmp_jobs = getAllJobs($_GET['type'], $_GET['jobkeyword'], 1,  $_GET['posted'], $_GET['company'], $_GET['salary']);

	if (sizeof($jobs_2) > 0) {
		$tmp_jobs = getAllMatchingJobsInArrays($tmp_jobs, $jobs_2);
	}

	if (sizeof($jobs_1) > 0) {
		$tmp_jobs = getAllMatchingJobsInArrays($tmp_jobs, $jobs_1);
	}

	$jobs = array();

	foreach ($tmp_jobs as $job) {
		$type = getJobType($job->typeID);
		$period = getCurrentPeriodForJob($job->id);
		if ($period->closingDate >= mktime(0,0,0,date('m'), date('d'), date('Y'))) {
			$jobs[] = $job;
		}
	}
	
	$breadcrumb = "recruitList";
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

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Job vacancies;Employment, jobs and careers" />
	<meta name="DC.subject" lang="en" content="Job vacancies;Jobs and careers;Recruitment" />

</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
		if (sizeof($jobs) > 0) {
?>
			<p class="first">Your search found the following vacancies:</p>
<?php
		}
		else {
?>
			<h3>Your search returned no matching vacancies.</h3>
<?php
        	}
?>

			<!-- RESULTS -->        
<?php
		if (sizeof($jobs) > 0) {
			foreach ($jobs as $job) {
				$type = getJobType($job->typeID);
				$period = getCurrentPeriodForJob($job->id);
?>
				
			<div class="search_result">
				<h3><a href="http://<?php print $DOMAIN; ?>/site/scripts/recruit_details.php?id=<?php print $job->id; ?>"><?php print $job->title; ?></a></h3>
				<p><?php print "$type->title, closing date: " . date("d M Y", $period->closingDate); ?></p>
			</div>
<?php
			}
		}
?>
                
<?php
		if (isset($_SESSION['userID']) && sizeof($jobs) > 0) {
?>
			
			<h3>Use this search to <a href="http://<?php print $DOMAIN; ?>/site/scripts/recruit_jobs.php?editAlert=true&<?php print $_SERVER['QUERY_STRING']; ?>">create an email alert</a></h3>
<?php
		}
		elseif(sizeof($jobs) > 0) {
?>
			<p class="first">Log in or register to sign up for email alerts based on these search results.</p>
<?php
			}
?>

            <!-- END Search Jobs box -->			

		
<?php 
	if (isset($_SESSION['userID'])) { 
?>
	
			<h3>Job email alerts</h3>
	
<?php
		if (getEmailAlert($_SESSION['userID']) != null) {
			$alert = getEmailAlert($_SESSION['userID']);
?>
			<p><em>You are signed up for email alerts</em>.</p>
			<ul class="list">
			
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/recruit_jobs.php?<?php print $alert->getQueryString(); ?>">Edit email alerts</a></li>
			
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/recruit_jobs.php?stopAlerts=true">Stop email alerts</a></li>
			</ul>
	
<?php 
		}
?>
			
			<p>Receive <strong>email alerts</strong> based upon search queries.  <strong>We mail you</strong> when your job comes online.</p> 
			
			<p>Find out how to <a href="http://<?php print $DOMAIN;?>/site/scripts/recruit_email_alerts.php" >create email alerts</a>.</p>
	</div>			
<?php
	} 
?>


	<p><?php print METADATA_GENERIC_COUNCIL_NAME;?> is an equal opportunities employer.</p>
	
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>