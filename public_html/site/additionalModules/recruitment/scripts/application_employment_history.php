<?php
    include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");
	include_once("recruitment/JaduRecruitmentApplicationCurrentEmployment.php");
	include_once("recruitment/JaduRecruitmentApplicationEmploymentHistory.php");
	
	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
    	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()){
    		$user = Jadu_Service_User::getInstance()->getSessionUser();
    	}
	
    	if (isset($user) && isset($_GET['appID'])){
	
    		$appID = $_GET['appID'];

    		// get the application
    		$app = getApplication($appID);
    		$job = getRecruitmentJob($app->jobID);
		   	$cat = getRecruitmentCategory($job->categoryID);
		   	
		   	// if the user id of app doesn't match the user id logged in kick them out
    		if ($user->id != $app->userID) {
    		    header("Location: recruit_details.php?id=$job->id");
    		    exit;
    		}
    		
	    	if ($app->submitted == 1 && (!isset($_GET['viewApp']))) {
	    	    header("Location: ".buildJobApplicationURL('details', $app->id));
	    	    exit;
			}

    		if (isset($_POST['saveProceed']) || isset($_POST['saveExit']) || 
    			isset($_POST['add']) || isset($_POST['update']) || isset($_GET['empID'])){

    			// get all the new details
    			$emp->applicationID 	= $_GET['appID'];
				$emp->jobTitle 			= $_POST['jobTitle'];
				$emp->employer 	 		= $_POST['employer'];
				$emp->employerAddress 	= $_POST['employerAddress'];
				$emp->dateStarted 		= $_POST['start_day'] . "-" . $_POST['start_month'] . "-" . $_POST['start_year'];
				$emp->dateFinished 		= $_POST['finish_day'] . "-" . $_POST['finish_month'] . "-" . $_POST['finish_year'];
				$emp->employerBusiness 	= $_POST['employerBusiness'];
				$emp->salary 			= $_POST['salary'];
				$emp->leavingReason 	= $_POST['leavingReason'];
				
    			if (isset($_POST['add'])) {
    				// see if there's anything missing
	    			$missing = getMissingEmploymentHistoryDetails($emp);
					$emp->id = addEmploymentHistory($emp);
					
					if (sizeof($missing) > 0) {
						$_GET['empID'] = $emp->id;
					}
					unset($emp);
				}
				elseif (isset($_POST['update'])) {
					$emp->id = $_POST['empID'];
					// see if there's anything missing
	    			$missing = getMissingEmploymentHistoryDetails($emp);
					updateEmploymentHistory($emp);
					if (sizeof($missing) > 0) {
						$_GET['empID'] = $emp->id;
					}
					else {
						unset($emp);
					}
				}

    			if (isset($_POST['saveProceed']) && sizeof($missing) < 1) {
	    			header("Location: ". buildJobApplicationURL('experience', $appID));
	    			exit;
	    		}
	    		elseif (isset($_POST['saveExit'])) {
					header("Location: ".buildJobApplicationURL('details', $appID));
					exit;
	    		}
	    		
	    		if (isset($_GET['empID'])) {
	    			if ($_GET['action'] == 'remove') {
	    				deleteSingleEmploymentHistory($_GET['empID']);
	    				unset($_GET['empID']);
	    			}
	    			else {	    			
	    				$emp = getEmploymentHistory($_GET['empID']);
	    			}
	    		}
	    	}
	    	
	    	// get any personal info details if they have already been entered    		
    		$empHistory = getEmploymentHistoryForApplication($app->id);
    		
    		// get current employment details
    		$curEmployment = getCurrentEmployment($_GET['appID']);

	    	$start = explode("-", $emp->dateStarted);
	    	$start_day = $start[0];
	    	$start_month = $start[1];
	    	$start_year = $start[2];
	    	
	    	$finish = explode("-", $emp->dateFinished);
	    	$finish_day = $finish[0];
	    	$finish_month = $finish[1];
	    	$finish_year = $finish[2];
    	}
	}
	else {
		header ("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}
	
	$breadcrumb = 'application_employment_history';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html<?php if (TEXT_DIRECTION == 'rtl') print ' dir="rtl"'; ?> xmlns="http://www.w3.org/1999/xhtml">
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

		<?php include('../includes/application_sections.php'); ?>
		
<?php
	if (sizeof($missing) > 0) {
?>				
		<!-- ERROR -->
		<h2 class="warning">Please ensure fields marked with ! are entered correctly</h2>
		<!-- END ERROR -->
<?php
	}
?>
		<h3>Employment history</h3>
			<table>
				<tr>
					<th>Employer</th>
					<th>Job title</th>
					<th>Actions</th>
				</tr>
<?php
		if ($curEmployment->employmentStatus == 'Unemployed') {
?>
			<tr>
				<td colspan="3">
					Unemployed. 
<?php 
			if ($app->submitted != 1) { 
?>
				<a href="<?php print getSiteRootURL() . buildJobApplicationURL('employmentCurrent', $_GET['appID'], 'changeStatus'); ?>">Change Status</a> 
<?php 
			} 
?>
				</td>
			</tr>
<?php
		}
		else {
?>
			<tr>
				<td><?php print encodeHtml($curEmployment->employer); ?></td>
				<td><?php print encodeHtml($curEmployment->jobTitle); ?></td>
				<td><a href="<?php print getSiteRootURL() .  buildJobApplicationURL('employmentCurrent', $_GET['appID'], 'edit'); ?>">Edit</a></td>
			</tr>
<?php
		}

		if (sizeof($empHistory) > 0) {
			foreach ($empHistory as $e) {
?>
			<tr>
				<td><?php print encodeHtml($e->employer); ?></td>
				<td><?php print encodeHtml($e->jobTitle); ?></td>
				<td><a href="<?php print getSiteRootURL() . buildJobApplicationURL('employmentHistory', $_GET['appID'], $e->id); ?>">Edit</a> | <a href="<?php print getSiteRootURL() . buildJobApplicationURL('employmentHistory', $_GET['appID'], $e->id, 'remove'); ?>">Remove</a></td>
			</tr>
			<!-- END History -->
<?php
			}
		}
?>
		</table>

		 <form action="<?php print getSiteRootURL() . buildJobApplicationURL('employmentHistory', $_GET['appID']); ?>" method="post" enctype="multipart/form-data" enctype="x-www-form-encoded" class="basic_form">
<?php
		if (isset($_GET['empID'])) {
?>
			<input type="hidden" name="empID" value="<?php print (int) $_GET['empID']; ?>">
<?php
		}
		if ($app->submitted != 1) {
?>
			<h3>Add another</h3>

			<p>
				<label for="employer"><?php if ($missing['employer']) { ?><span class="star">! <?php } ?>Name of Previous Employer<?php if ($missing['employer']) { ?></span><?php } ?> * </label>
				<input id="employer" type="text" name="employer" class="field" value="<?php print encodeHtml($emp->employer); ?>" />
				<span class="clear"></span>
			</p>

			<p>
				<label for="employerAddress"><?php if ($missing['employerAddress']) { ?><span class="star">! <?php } ?>Address of Previous Employer<?php if ($missing['employerAddress']) { ?></span><?php } ?> *</label>
				<textarea id="employerAddress" name="employerAddress" class="field" rows="5"><?php print encodeHtml($emp->employerAddress); ?></textarea>
			</p>

			<p>
				<label for="employerBusiness"><?php if ($missing['employerBusiness']) { ?><span class="star">! <?php } ?>Nature of Business<?php if ($missing['employerBusiness']) { ?></span><?php } ?> *</label>
				<input id="employerBusiness" type="text" name="employerBusiness" class="field" value="<?php print encodeHtml($emp->employerBusiness); ?>" />
			</p>

			<p class="date_birth">
				<label><?php if ($missing['dateStarted']) { ?><span class="star">! <?php } ?>Start date <?php if ($missing['dateStarted']) { ?></span><?php } ?> * </label>
				<label for="start_day"><em>dd</em> <input id="start_day" type="text" name="start_day" value="<?php print $start_day;?>" size="2" maxlength="2" class="dob" /></label>
				<label for="start_month"><em>mm</em> <input id="start_month" type="text" name="start_month" value="<?php print $start_month;?>" size="2" maxlength="2" class="dob" /></label>
				<label for="start_year"><em>yyyy</em> <input id="start_year" type="text" name="start_year" value="<?php print $start_year;?>" size="4" maxlength="4" class="dob" /></label>
				<span class="clear"></span>
			</p>

			<p class="date_birth">
				<label><?php if ($missing['dateFinished']) { ?><span class="star">! <?php } ?>End date <?php if ($missing['dateFinished']) { ?></span><?php } ?> * </label>
				<label for="finish_day"><em>dd</em> <input id="finish_day" type="text" name="finish_day" value="<?php print $finish_day;?>" size="2" maxlength="2" class="dob" /></label>
				<label for="finish_month"><em>mm</em> <input id="finish_month" type="text" name="finish_month" value="<?php print $finish_month;?>" size="2" maxlength="2" class="dob" /></label>
				<label for="finish_year"><em>yyyy</em> <input id="finish_year" type="text" name="finish_year" value="<?php print $finish_year;?>" size="4" maxlength="4" class="dob" /></label>
				<span class="clear"></span>
			</p>

			<p>
				<label for="jobTitle"><?php if ($missing['jobTitle']) { ?><span class="star">! <?php } ?>Post held / Type of work done<?php if ($missing['jobTitle']) { ?></span><?php } ?> * </label>
				<input id="jobTitle" type="text" name="jobTitle" class="field" value="<?php print encodeHtml($emp->jobTitle); ?>" />
				<span class="clear"></span>
			</p>

			<p>
				<label for="leavingReason"><?php if ($missing['leavingReason']) { ?><span class="star">! <?php } ?>Reason for leaving<?php if ($missing['leavingReason']) { ?></span><?php } ?> * </label>
				<input id="leavingReason" type="text" name="leavingReason" class="field" value="<?php print encodeHtml($emp->leavingReason); ?>" />
			</p>

<?php
		}	
		if (isset($_GET['empID']) && $app->submitted != 1) {
?>
			<p class="center">
				<input class="button" type="submit" name="update" value="Update" />
			</p>
<?php
		}
		elseif($app->submitted != 1) {
?>
			<p class="center">
				<input class="button" type="submit" name="add" value="Add" />
			</p>
<?php
		}
?>
<?php
	if ($app->submitted != 1) {
?>
			<p>When you are satisfied you have completed your Employment History, click the 'Save &amp; Proceed' button below to continue with the application.</p>
			<!-- Save and Proceed button -->
			<p class="center">
				<input class="button" type="submit" name="saveProceed" value="Save &amp; Proceed" />
			</p>
<?php
	}
?>		
		<!-- save for later -->
		<?php include("../includes/savelater.php"); ?>
		<!-- END save for later -->
		</form>
			
		<p class="note"><?php print encodeHtml(METADATA_GENERIC_NAME); ?> is an equal opportunities employer.</p>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>