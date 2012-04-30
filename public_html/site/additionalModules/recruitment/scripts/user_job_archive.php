<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");

	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
	    if (isset($_GET['remove']) && $_GET['remove'] == "true") {
            deleteApplication($_GET['userAppID']);
		}
	
		$submittedJobApps = getSubmittedApplicationsForUser(Jadu_Service_User::getInstance()->getSessionUserID(), 1);
		$unsubmittedJobApps = getSubmittedApplicationsForUser(Jadu_Service_User::getInstance()->getSessionUserID(), 0);
	} 
	else {
		header ("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}

	$breadcrumb = 'userJobArchive';	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html<?php if (TEXT_DIRECTION == 'rtl') print ' dir="rtl"'; ?> xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
	<title><?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Form archive</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="forms, form, application, archive, archives, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> online user forms archive" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> form archive" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> online user forms archive" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<?php include("../includes/opening.php"); ?>
		<!-- ####################################### -->
	
<?php
	if (sizeof($unsubmittedJobApps) > 0) {
?>	
			<h2>Awaiting completion</h2>
			<ul class="list">
			
<?php
		foreach ($unsubmittedJobApps as $app) {
			$job = getRecruitmentJob($app->jobID);				
?>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/application_details.php?appID=<?php print $app->id;?>"><?php print encodeHtml($job->title); ?></a> : <a href="user_home.php?userAppID=<?php print $app->id; ?>&amp;remove=true">Remove</a></li>
<?php
		}
?>
			</ul>
<?php
	}

	if (sizeof($submittedJobApps) > 0) {
?>

			<h2>Your submitted applications</h2>
			<ul class="list">
<?php
		foreach ($submittedJobApps as $app) {
			$job = getRecruitmentJob($app->jobID);				
?>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/application_details.php?appID=<?php print $app->id;?>&amp;viewApp=true"><?php print encodeHtml($job->title); ?></a> - Completed: <?php print date("d M y",$app->dateSubmitted); ?>, Status: <?php if ($app->accepted == -1) print "Pending review"; elseif($app->accepted == 0) print "Unsuccessful"; else print "Progressed"; ?></li>
<?php
		}
?>
			</ul>
<?php
	}				
		
	if (sizeof($submittedJobApps) == 0 && sizeof($unsubmittedJobApps) == 0) {
?>
		<p>You have not yet submitted any applications to <?php print encodeHtml(METADATA_GENERIC_NAME); ?>.</p>
<?php
	}
?>
				
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>