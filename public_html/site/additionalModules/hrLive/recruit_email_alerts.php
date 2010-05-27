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
	include_once("hrlive/JaduHRLiveJobRoles.php");
	include_once("marketing/JaduAdverts.php");

	if (isset($_SESSION['userID'])) {
		$user = getUser($_SESSION['userID']);
	}
	
	$breadcrumb = "recruitEmailAlerts";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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

			
	<p class="first">If you set up an email alert you can relax while we automatically send you the jobs matching your search criteria when they are published. We will do the searching for you. An email alert keeps an eye on the jobs posted onto our site 24 hours a day. You will be sent all new jobs that match the search criteria that you select.</p>

	<h3>Your Alerts</h3>
	<p class="first">To create and manage an email alert you must first sign-in to the site. If you're not registered yet you can <a title="Register" href="http://<?php print $DOMAIN; ?>/site/scripts/register.php">register here</a>.</p>

	<p><strong>Creating a job alert is easy:</strong></p>
	
	<ol>
		<li>Select your criteria from the jobs home page and click <strong>search</strong>.</li>
		<li>Any jobs matching your criteria will be displayed. Click the <strong>'create an email alert link'</strong>.</li>
		<li>That's it!  You'll now receive up to the minute information on job vancancies.</li>
	</ol>
	
	<p>When you are signed-in to the site you will be able to edit your email alert or stop email alerts altogether from the option in the right-hand column.</p>
	
	<ul class="list">
		<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/recruit_jobs.php?editAlert=true">Start creating an email alert!</a></li>
	</ul>
	
		<p><?php print METADATA_GENERIC_COUNCIL_NAME;?> is an equal opportunities employer.</p>
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>