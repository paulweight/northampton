<?php
	session_start();
	include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");
	include_once("marketing/JaduUsers.php");
	include_once("hrlive/JaduHRLiveCategories.php");
	include_once("hrlive/JaduHRLiveJobsToCategories.php");
	include_once("hrlive/JaduHRLiveJobsToRoles.php");
	include_once("hrlive/JaduHRLiveJobs.php");
	include_once("hrlive/JaduHRLiveJobTypes.php");
	include_once("hrlive/JaduHRLiveJobPeriods.php");
	include_once("hrlive/JaduHRLiveEmailAlerts.php");

	if (isset($userID)) {
		$user = getUser($userID);
	}

	$catID = 1;
	$category = "";

	if (isset($_GET['catID'])) {
		$catID = $_GET['catID'];
		$category = getBespokeCategory($_GET['catID']);
	}

	$cats = getAllChildrenCategoriesOfCategory($catID);
	
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

		<!-- MAIN JOB SEARCH AREA -->                
<?php
			if (sizeof(getAllCurrentlyLiveJobs()) > 0) {
				if (!empty($category)) {
					$jcs = getAllJobsToCategoriesForCategory($category->id);
					$jobs = array();
					if (sizeof($jcs) > 0) {
						foreach ($jcs as $jc) {
							$j = getJob($jc->jobID);
							$period = getCurrentPeriodForJob($j->id);
							if ($period->closingDate >= mktime(0,0,0,date('m'), date('d'), date('Y')) && $j->isLive()) {
								$jobs[] = $j;
							}
						}
					}
					if (sizeof($jobs) > 0) {
?>
						<h3>Jobs in <?php print $category->title; ?></h3>
<?php
						foreach ($jobs as $job) {
							$type = getJobType($job->typeID);
							$period = getCurrentPeriodForJob($job->id);
?>
							<p class="slim"> &raquo; <a href="http://<?php print $DOMAIN; ?>/site/scripts/recruit_details.php?id=<?php print $job->id; ?>" title="<?php print htmlentities($job->title); ?>"><?php print htmlentities($job->title); ?></a> <br />
							<span class="note"><?php print "$type->title, closing date: " . date("d M Y", $period->closingDate); ?></span></p>
<?php
						}
					}
				}

				foreach ($cats as $cat) {
					$subCats = getAllChildrenCategoriesOfCategory ($cat->id, true);
					$jcs = getAllJobsToCategoriesForCategory($cat->id);
					
					foreach ($subCats as $subCat) {
						$jcs = array_merge(getAllJobsToCategoriesForCategory($subCat->id), $jcs);
					}
					
					$jobs = array();
					$jobIDsUsed = array();
					if (sizeof($jcs) > 0) {
						foreach ($jcs as $jc) {
							$j = getJob($jc->jobID);
							$period = getCurrentPeriodForJob($j->id);
							if ($period->closingDate >= mktime(0,0,0,date('m'), date('d'), date('Y')) && $j->isLive() && !in_array($j->id, $jobIDsUsed)) {
								$jobs[] = $j;
								$jobIDsUsed[] = $j->id;
							}
						}
					}
					if (sizeof($jobs) > 0) {
?>
					<div class="search_result">
						<h3><span><a href="http://<?php print $DOMAIN; ?>/site/scripts/recruit_list.php?catID=<?php print $cat->id; ?>" title="<?php print $cat->title; ?>"><?php print $cat->title; ?></a><span></h3>
<?php
						foreach ($subCats as $subCat) {
							$jcs = getAllJobsToCategoriesForCategory($subCat->id);
							$tmp_jobs = array();
							if (sizeof($jcs) > 0) {
								foreach ($jcs as $jc) {
									$j = getJob($jc->jobID);
									$period = getCurrentPeriodForJob($j->id);
									if ($period->closingDate >= mktime(0,0,0,date('m'), date('d'), date('Y')) && $j->isLive()) {
										$tmp_jobs[] = $j;
									}
								}
							}
							if (sizeof($tmp_jobs) > 0) {
?>
							<p><a href="http://<?php print $DOMAIN; ?>/site/scripts/recruit_list.php?catID=<?php print $subCat->id; ?>" title="<?php print $subCat->title; ?>"><?php print $subCat->title; ?></a></p>
<?php
							}
						}
						print '<ul class="list">';
						foreach ($jobs as $job) {
						    $period = getCurrentPeriodForJob($job->id);
?>
							<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/recruit_details.php?id=<?php print $job->id; ?>" title="<?php print htmlentities($job->title); ?>"><?php print htmlentities($job->title); ?></a> - <span class="note"><?php print "$type->title closing date: " . date("d M Y", $period->closingDate); ?></span></list>
<?php
						}
						print '</ul></div>';
					}
				}
			}
			else {
?>
				<p class="first">There are currently no jobs available at <?php print METADATA_GENERIC_COUNCIL_NAME; ?>.</p>
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