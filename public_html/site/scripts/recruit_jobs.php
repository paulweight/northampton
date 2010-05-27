<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("marketing/JaduAdverts.php");
	
	if (isset($_SESSION['userID'])) {
		$user = getUser($_SESSION['userID']);
	}
	
	$jobCategories = getAllRecruitmentCategories();
	
	$breadcrumb = 'recruitJobs';
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
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (sizeof(getLatestLiveJobs(1, true)) < 0) {
?>
	<p class="first">There are currently <strong>no vacancies</strong> available at the council.</p>
<?php
	}
	else {
?>
	<p class="first">We currently have the following vacancies:</p>   
<?php
		foreach ($jobCategories as $jobCategory) {
			if (categoryIDHasJobs($jobCategory->id)) {
				$jobs = getAllRecruitmentLiveJobsInCategory($jobCategory->id);
				
				if(sizeof($jobs) > 0) {
?>		
	<div class="cate_info">
		<h2><?php print $jobCategory->title; ?></h2>
			<ul>
				
<?php
					foreach ($jobs as $job) {
					$job->title = htmlentities($job->title);
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/recruit_details.php?id=<?php print $job->id; ?>"><?php print $job->title; ?></a> - <span class="note">Closing date <strong><?php print date("d F Y", $job->closingDate); ?></strong></span></li>							
<?php
					}
?>
			
			</ul>
		</div>
<?php
				}
			}
		}
	}
?>
		
	<!-- END of the Contact box -->
	<p class="note"><?php print METADATA_GENERIC_COUNCIL_NAME;?> is an equal opportunities employer.</p>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>