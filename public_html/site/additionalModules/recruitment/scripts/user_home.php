<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	
	include_once("egov/JaduXFormsForm.php");
	include_once("egov/JaduXFormsUserForms.php");
	include_once("egov/JaduXFormsUserQuestionAnswers.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");

	$confirmRemove = false;

	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
			
		if (isset($_GET['userFormID'])) {
			$userForm = getXFormsUserForm($_GET['userFormID']);
			
			if ($userForm->userID != Jadu_Service_User::getInstance()->getSessionUserID()) {
				header ("Location: $ERROR_REDIRECT_PAGE");
				exit();
			}
			
			$allAnswers = getAllXFormsQuestionAnswersForForm ($userForm->id);
			$form = getXFormsForm($userForm->formID, true);
			
			if (isset($_GET['remove']) && $_GET['remove'] == "true") {
				deleteXFormsUserForm($userForm->id);
			}
		}
		elseif (isset($_GET['userAppID']) || isset($_POST['userAppID'])) {
		    if (isset($_GET['remove']) && $_GET['remove'] == "true" && !isset($_POST['confirmRemove'])) {
		    	$app = getApplication($_GET['userAppID']);
		    	
		    	if ($app != null) {
		    		$confirmRemove = true;
		    	}
			}
			elseif(isset($_POST['confirmRemove'])) {
				$app = getApplication($_POST['userAppID']);

				// check that the logged in user owns this application
				if ($app->userID == Jadu_Service_User::getInstance()->getSessionUserID()) {
					deleteApplication($_POST['userAppID']);
				}
				unset($app);
			}
		}

		$allSubmittedUserForms = getAllXFormsUserFormsForUser (Jadu_Service_User::getInstance()->getSessionUserID(), true);
		$allUnsubmittedUserForms = getAllXFormsUserFormsForUser (Jadu_Service_User::getInstance()->getSessionUserID(), false);
		
		$submittedJobApps = getSubmittedApplicationsForUser(Jadu_Service_User::getInstance()->getSessionUserID(), 1, 'date');
		$unsubmittedJobApps = getUnsubmittedApplicationsForUser(Jadu_Service_User::getInstance()->getSessionUserID());		
	}
	else {
		header ("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}

	$loginString = Jadu_Service_User::getInstance()->getLastLoginAsString(Jadu_Service_User::getInstance()->getSessionUserID());
	
	$breadcrumb = 'userHome';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html<?php if (TEXT_DIRECTION == 'rtl') print ' dir="rtl"'; ?> xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Personal details</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="account, regstration, user, profile, personal, details, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> User personal details" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Personal details" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> User personal details" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
						
<?php
	if ($confirmRemove) {
		$app = getApplication($_GET['userAppID']);
		$job = getRecruitmentJob($app->jobID);
?>

		<p class="first">Are you sure you want to <span class="warning">delete</span> your application for <strong><?php print encodeHtml($job->title); ?></strong></p>
		<p>
			<form action="<?php print getSiteRootURL(); ?>/site/scripts/user_home.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="userAppID" value="<?php print (int) $_GET['userAppID']; ?>" />
				<input type="submit" name="confirmRemove" class="button" value="Yes" />
				<input type="submit" name="declineRemove" class="button" value="No" />
			</form>
		<p>

<?php
		unset($app);
		unset($job);
	}
	else {
?>
		
		<h2>Hello, <em>
<?php 
	
		if ($user->salutation != "" && $user->surname != "") {
			print $user->salutation .  " ";
		}
	
		if ($user->forename != "") {
			print $user->forename . " ";
		}
		
		if  ($user->surname != "") {
			print $user->surname; 
		}
	
		if ($user->forename == "" && $user->surname == "") {
			print $user->email;
		}
	
?>
		</em></h2>
					
		<p class="first">Keep track of your activities and details right here.</p>
					
		<!-- Account options -->
		<div class="content_box">
			<h2>Your personal details <?php if (isset($detailsChanged)) { ?><em>have been updated.</em><?php } ?></h2>
			<ul class="list">
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/change_details.php">Change your details</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/change_password.php">Change your password</a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/site/index.php?logout=true">Sign out</a></li>
			</ul>
		</div>

<?php
		if (sizeof($submittedJobApps) > 0 || sizeof($unsubmittedJobApps) > 0) {
?>
		<div class="display_box">
			<h2>Your Job Applications</h2>			
<?php
			if (sizeof($submittedJobApps) > 0) {
?>
			<ul>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/user_job_archive.php" title="Job application archive.">Job application archive</a></li>
			</ul>
<?php
			}
			if (sizeof($unsubmittedJobApps) > 0) {
?>
			<h3>Awaiting completion</h3>
			<ul>
<?php
				foreach ($unsubmittedJobApps as $app) {
					$job = getRecruitmentJob($app->jobID);				
?>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/application_details.php?appID=<?php print $app->id;?>" title="<?php print encodeHtml($job->title); ?>."><?php print $job->title;?></a> : <a href="<?php print getSiteRootURL(); ?>/site/scripts/user_home.php?userAppID=<?php print $app->id; ?>&amp;remove=true"><span  class="remove">Remove</span></a></li>
<?php
				}
?>
			</ul>

<?php
			}
			if (sizeof($submittedJobApps) > 0) {
				$app = $submittedJobApps[0];
				$job = getRecruitmentJob($app->jobID);
?>
			<h3>Your most recently submitted application</h3>
			<ul>
				<li><a href="<?php print getSiteRootURL(); ?>/site/scripts/application_details.php?appID=<?php print $app->id;?>&amp;viewApp=true" title="<?php print encodeHtml($job->title);?>." target="_self"><?php print $job->title;?></a> : Completed: <?php print date("d M y",$app->dateSubmitted); ?>
					<br />Status: <?php if ($app->accepted == -1) print "Pending review"; elseif($app->accepted == 0) print "Unsuccessful"; else print "Progressed"; ?>
				</li>
			</ul>
<?php
			}				
?>
		</div>
<?php
		}
	}
?>
			<!-- END job application -->
			
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>