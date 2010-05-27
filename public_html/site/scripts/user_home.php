<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("egov/JaduXFormsForm.php");
	include_once("egov/JaduXFormsUserForms.php");
	include_once("egov/JaduXFormsUserQuestionAnswers.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	//include_once("recruitment/JaduRecruitmentApplications.php");
	
	$confirmRemove = false;

	if (isset($_SESSION['userID'])) {
			
		if (isset($_GET['userFormID'])) {
			$userForm = getXFormsUserForm($_GET['userFormID']);
			
			if ($userForm->userID != $_SESSION['userID']) {
				header ("Location: $ERROR_REDIRECT_PAGE");
				exit();
			}
			
			$allAnswers = getAllXFormsQuestionAnswersForForm ($userForm->id);
			$form = getXFormsForm($userForm->formID, true);
			
			if (isset($_POST['confirmRemove']) && $_POST['confirmRemove'] == "Yes, go ahead") {
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
				if ($app->userID == $_SESSION['userID']) {
					deleteApplication($_POST['userAppID']);
				}
				unset($app);
			}
		}

		$allSubmittedUserForms = getAllXFormsUserFormsForUser ($_SESSION['userID'], true);
		$allUnsubmittedUserForms = getAllXFormsUserFormsForUser ($_SESSION['userID'], false);		
		
		//$submittedJobApps = getSubmittedApplicationsForUser($_SESSION['userID'], 1);
		//$unsubmittedJobApps = getUnsubmittedApplicationsForUser($_SESSION['userID']);		
	}
	else {
		header ("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}

	$loginString = getLastLoginAsString($_SESSION['userID']);
	
	$breadcrumb = 'userHome';
	
?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Your account | <?php print METADATA_GENERIC_COUNCIL_NAME;?> - Personal details</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="account, regstration, user, profile, personal, details, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> User personal details" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Personal details" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> User personal details" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
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

		<p class="first">Are you sure you want to <em>delete</em> your application for <strong><?php print $job->title; ?>?</strong></p>
		<p class="first">There's no going back.</p>
		<p>
			<form action="<?php print $SECURE_SERVER;?>/site/scripts/user_home.php" method="post">
				<input type="hidden" name="userAppID" value="<?php print $_GET['userAppID']; ?>" />
				<input type="submit" name="confirmRemove" class="yesbutton" value="Yes, go ahead" />
				<input type="submit" name="declineRemove" class="nobutton" value="No, please don't!" />
			</form>
		<p>

<?php
		unset($app);
		unset($job);
	}
	if ($_GET['removeForm']) {
		$userForm = getXFormsUserForm($_GET['userFormID']);
		$actualForm = getXFormsForm($userForm->formID, false);
?>
		<p class="first">Are you sure you want to <strong>delete</strong> the form <strong><?php print $actualForm->title; ?></strong>?</p>
		<p class="first">There's no going back!</p>
		<p>
			<form action="<?php print $SECURE_SERVER;?>/site/scripts/user_home.php?userFormID=<?php print $_GET['userFormID']; ?>" method="post">
				<input type="submit" name="confirmRemove" class="yesbutton" value="Yes, go ahead" />
				<input type="submit" name="declineRemove" class="nobutton" value="No, please don't!" />
			</form>
		<p>
<?php
	}
	else {
?>
		
		<h2>Hello,
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
		</h2>
					
		<p class="first">Keep track of your activities and details right here.</p>
					
		<!-- Account options -->
		<div class="cate_info">
			<h2>Your personal details <?php if (isset($detailsChanged)) { ?><em>have been updated.</em><? } ?></h2>
			<ul>
				<li class="bllt"><a href="<?php print $SECURE_SERVER;?>/site/scripts/change_details.php">Change your account details</a></li>
				<li class="bllt"><a href="<?php print $SECURE_SERVER;?>/site/scripts/change_password.php">Change your password</a></li>
				<li class="bllt"><a href="http://<?php print $DOMAIN;?>/site/index.php?logout=true">Sign out</a></li>
			</ul>
		</div>

			
		<!-- Online Forms -->
		<div class="cate_info">
		<h2>Your online forms</h2>
			
<?php 
		if (sizeof($allSubmittedUserForms) > 0) {
?>
			<ul>
				<li class="bllt"><a href="http://<?php print $DOMAIN;?>/site/scripts/user_form_archive.php">Forms you have submitted</a></li>
			</ul>
<?php		
		}

		if (sizeof($allUnsubmittedUserForms) > 0) {
?>	
						
			<h2>Awaiting completion</h2>
<?php
			foreach ($allUnsubmittedUserForms as $userForm) {
				$actualForm = getXFormsForm($userForm->formID, false);
?>
			
			<ul class="UserList">
				<li><h3><a href="<?php print $SECURE_SERVER;?>/site/scripts/xforms_form.php?formID=<?php print $actualForm->id;?>"><?php print $actualForm->title;?></a></h3></li>
				<li class="userComplete"><a href="<?php print $SECURE_SERVER;?>/site/scripts/xforms_form.php?formID=<?php print $actualForm->id;?>">Complete it</a></li>
				<li class="userDelete"><a href="<?php print $SECURE_SERVER;?>/site/scripts/user_home.php?userFormID=<?php print $userForm->id;?>&amp;removeForm=true">Remove it</a></li>
			</ul>
<?php
			}
?>
			
<?php
		}

		if (sizeof($allSubmittedUserForms) == 0 && sizeof($allUnsubmittedUserForms) == 0) {
?>
			<p>You have <strong>no online forms in progress</strong><!-- or submitted.--></p>
<?php
		}
?>
				</div>
				<!-- END Online Forms -->
<?php
	}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>