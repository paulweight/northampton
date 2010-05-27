<?php
	session_start();
	include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");
	include_once("marketing/JaduUsers.php");
	include_once("hrlive/JaduHRLiveCategories.php");
	include_once("hrlive/JaduHRLiveJobs.php");
	include_once("hrlive/JaduHRLiveJobLocations.php");
	include_once("hrlive/JaduHRLiveJobPeriods.php");
	include_once("hrlive/JaduHRLiveApplications.php");
	include_once("hrlive/JaduHRLiveJobsDownloads.php");
	include_once("marketing/JaduAdverts.php");

	if (isset($userID)){
		$user = getUser($userID);
	}

	$job = getJob($id);
	
	$currentPeriod = getCurrentPeriodForJob($job->id);
	//print_r($currentPeriod);

	$startedApplication = -1;
	
	$location = getJobLocation($job->location);

	// see if the user has already got an application for this job
	if (isset($_GET['id']) && (isset($user))){
		$startedApplication = hasUserStartedApplication($user->id, $_GET['id'], $currentPeriod->id);
	}

	$downloads = getAllDownloadsForJob($_GET['id']);
	
	$breadcrumb = "recruitDetails";
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

                
	<p class="first"><strong>Salary:</strong> <?php ($job->salary_from == $job->salary_to) ? printf("&pound;%s", $job->salary_from) : printf("&pound;%s - &pound;%s", $job->salary_from, $job->salary_to);?></p>
	<p class="first"><strong>Location:</strong> <?php print $location->title;?></p>
	<p class="first"><strong>Closing Date:</strong> <?php print date("d M Y", $currentPeriod->closingDate);?></p>
		
		
	<div class="by_editor">
		<?php print nl2br($job->description);?>
	</div>

<?php
	if ($downloads) {
?>					
	<p class="first">Please download and read the following information before proceeding with your application:</p>
	<ul class="list">		
<?php 
		foreach ($downloads as $download) {
?>
		<li><a href="<?php print "http://" . $DOMAIN . "/hrlive_downloads/".$download->filename; ?>"><?php print $download->title; ?></a></li>
<?php
		}
	}
?>
	</ul>

<?php
	// do allow applications for certain jobs as they are applied for on other sites
	$excludeJobIDs = array(4,5,6,7,8,9);
	if (!in_array($job->id, $excludeJobIDs)) {
?>		
	<p class="first">
<?php	if (($startedApplication == -1) && isset($userID)) { ?>
		
		<a href="http://<?php print $DOMAIN;?>/site/scripts/application_details.php?appID=-1&amp;jobID=<?php print $job->id;?>">Apply for this job online.</a>
<?php
			} 
			else if (!isset($userID)) {
?>
		<a href="http://<?php print $DOMAIN;?>/site/scripts/register.php">Register</a> - To apply for a position online you need to register or sign-in. 					
<?php
			}
			else if(($startedApplication != -1) &&isset($userID)) {
?>
		<a href="http://<?php print $DOMAIN;?>/site/scripts/application_details.php?appID=<?php print $startedApplication;?>">View application details</a>
<?php
			}
?>
	</p>        
<?php
    }
?> 
		
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
<?php
	} 
?>


		<p><?php print METADATA_GENERIC_COUNCIL_NAME;?> is an equal opportunities employer.</p>
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>