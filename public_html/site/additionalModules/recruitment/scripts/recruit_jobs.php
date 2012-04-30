<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("marketing/JaduAdverts.php");
	
	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
		$user = Jadu_Service_User::getInstance()->getSessionUser();
	}
	
	$jobCategories = getAllRecruitmentCategories();
	
	$breadcrumb = 'recruitJobs';
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

<?php
	if (sizeof(getLatestLiveJobs(1, true)) < 0) {
?>
			<p class="first">There are currently no vacancies available at the council.</p>
<?php
	}
	else {
?>
			<p class="first">We currently have the following vacancies:</p>   
<?php
		foreach ($jobCategories as $jobCategory) {
			if (categoryIDHasJobs($jobCategory->id)) {
				$jobs = getAllRecruitmentLiveJobsInCategory($jobCategory->id);
				
?>		
			<div class="cate_wrap">
				<div class="h_3"><?php print encodeHtml($jobCategory->title); ?></div>
				
<?php
				foreach ($jobs as $job) {
				$job->title = encodeHtml($job->title);
?>
					<p><a href="recruit_details.php?id=<?php print $job->id; ?>"><?php print encodeHtml($job->title); ?></a> - Closing date: <?php print date("d F Y", $job->closingDate); ?></p>							
<?php
				}
?>
			</div>
<?php
			}
		}
	}
?>
		
		<!-- END of the Contact box -->
		<p class="first"><?php print encodeHtml(METADATA_GENERIC_NAME); ?> is an equal opportunities employer.</p>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>