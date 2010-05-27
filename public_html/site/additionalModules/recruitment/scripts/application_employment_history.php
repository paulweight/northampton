<?php
    include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");
	include_once("recruitment/JaduRecruitmentApplicationCurrentEmployment.php");
	include_once("recruitment/JaduRecruitmentApplicationEmploymentHistory.php");
	
	if (isset($_SESSION['userID'])) {
    	if (isset($_SESSION['userID'])){
    		$user = getUser($userID);
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
	    	    header("Location: application_details.php?appID=$app->id");
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
	    			header("Location: application_experience.php?appID=$appID#anchor");
	    			exit;
	    		}
	    		elseif (isset($_POST['saveExit'])) {
					header("Location: application_details.php?appID=$appID#anchor");
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
	
	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

		<div id="anchor"></div>
		<!-- Breadcrumb --><!-- googleoff:all -->
		<div id="bc">
			<span class="bcb">&raquo;</span> 
			<a href="http://<?php print $DOMAIN; ?>/site/">Home</a> | <a href="user_home.php">Your Account</a> | <a href="application_details.php?appID=<?php print $app->id; ?>">Application details</a> | Employment
		</div>
		<!-- END Breadcrumb --><!-- googleon:all -->

		<h1>Application for employment</h1>
		
		<p class="first"><span class="h">Position:</span> <?php print $job->title;?> <span class="h">Closing Date:</span> <?php print date("l jS F y", $job->closingDate);?></p>
		
		
		<!-- Step / progress box -->
		<?php include_once("../includes/application_sections.php"); ?>
		<!-- END Step / progress box -->

		<p class="first">Fields marked * are mandatory.

		<!-- Creates the slim central column -->
		<div id="jobs_centre">

		<h2><span class="h">Step 5:</span> Employment</h2>
		
<?php
	if (sizeof($missing) > 0) {
?>				
		<!-- ERROR -->
		<div class="joberror">
			<p>Please ensure fields marked with ! are entered correctly</p>
		</div>
		<!-- END ERROR -->
<?php
	}
?>

		<!-- Begin form area -->
		 <form action="application_employment_history.php?appID=<?php print $_GET['appID']; ?>#anchor" method="post" enctype="x-www-form-encoded" class="jobs_form">
	<?php
		if (isset($_GET['empID'])) {
	?>
			<input type="hidden" name="empID" value="<?php print $_GET['empID']; ?>">
	<?php
		}
		if ($app->submitted != 1) {
	?>
			<!-- Current Employment -->
			<div class="jobs_subheader">Employment History <?php if (sizeof($empHistory) < 1) { ?>(Latest First)<?php } ?></div>

			<!-- post -->
			<div><label for="employer"><?php if ($missing['employer']) { ?><span class="star">! <?php } ?>Name of Previous Employer<?php if ($missing['employer']) { ?></span><?php } ?> * </label><input id="employer" type="text" name="employer" class="jobs_form" value="<?php print $emp->employer; ?>" /></div>
			<!-- END post -->
			
			<div class="form_line"></div>
			
			<!-- location -->
			<div><label for="employerAddress"><?php if ($missing['employerAddress']) { ?><span class="star">! <?php } ?>Address of Previous Employer<?php if ($missing['employerAddress']) { ?></span><?php } ?> *</label><textarea id="employerAddress" name="employerAddress" class="jobs_form" rows="5"><?php print $emp->employerAddress; ?></textarea></div>
			<!-- END location -->
			
			<div class="form_line"></div>
			
			<!-- post -->
			<div><label for="employerBusiness"><?php if ($missing['employerBusiness']) { ?><span class="star">! <?php } ?>Nature of Business<?php if ($missing['employerBusiness']) { ?></span><?php } ?> *</label><input id="employerBusiness" type="text" name="employerBusiness" class="jobs_form" value="<?php print $emp->employerBusiness; ?>" /></div>
			<!-- END post -->
			
			<div class="form_line"></div>
			
			<!-- from date -->	
			<div>
				<div class="multipleinput_label"><?php if ($missing['dateStarted']) { ?><span class="star">! <?php } ?>Start date <?php if ($missing['dateStarted']) { ?></span><?php } ?> * </div>
				<label for="start_day" class="multipleinput">&nbsp;dd <input id="start_day" type="text" name="start_day" value="<?php print $start_day;?>" size="2" maxlength="2" class="jobs_date" /></label>
				<label for="start_month" class="multipleinput">mm <input id="start_month" type="text" name="start_month" value="<?php print $start_month;?>" size="2" maxlength="2" class="jobs_date" /></label>
				<label for="start_year" class="multipleinput">yyyy <input id="start_year" type="text" name="start_year" value="<?php print $start_year;?>" size="4" maxlength="4" class="jobs_date" /></label>
			<div class="clear"></div>
			</div>
			<!-- END from date -->
			
			<!-- end date -->	
			<div>
				<div class="multipleinput_label"><?php if ($missing['dateFinished']) { ?><span class="star">! <?php } ?>End date <?php if ($missing['dateFinished']) { ?></span><?php } ?> * </div>
				<label for="finish_day" class="multipleinput">&nbsp;dd <input id="finish_day" type="text" name="finish_day" value="<?php print $finish_day;?>" size="2" maxlength="2" class="jobs_date" /></label>
				<label for="finish_month" class="multipleinput">mm <input id="finish_month" type="text" name="finish_month" value="<?php print $finish_month;?>" size="2" maxlength="2" class="jobs_date" /></label>
				<label for="finish_year" class="multipleinput">yyyy <input id="finish_year" type="text" name="finish_year" value="<?php print $finish_year;?>" size="4" maxlength="4" class="jobs_date" /></label>
			</div>
			<!-- END end date -->
			
			<div class="form_line"></div>
			
			<!-- post -->
			<div><label for="jobTitle"><?php if ($missing['jobTitle']) { ?><span class="star">! <?php } ?>Post held / Type of work done<?php if ($missing['jobTitle']) { ?></span><?php } ?> * </label><input id="jobTitle" type="text" name="jobTitle" class="jobs_form" value="<?php print $emp->jobTitle; ?>" /></div>
			<!-- END post -->
			
			<div class="form_line"></div>
			<!-- reason -->
			<div><label for="leavingReason"><?php if ($missing['leavingReason']) { ?><span class="star">! <?php } ?>Reason for leaving<?php if ($missing['leavingReason']) { ?></span><?php } ?> * </label><input id="leavingReason" type="text" name="leavingReason" class="jobs_form" value="<?php print $emp->leavingReason; ?>" /></div>
			<!-- END reason -->
			
			<div class="form_line"></div>
	<?php
		}	
	?>	
			<!-- Add button -->
			<div class="right">
		<?php
			if (isset($_GET['empID']) && $app->submitted != 1) {
		?>
				<input class="proceed_button" type="submit" name="update" value="Update" />
		<?php
			}
			elseif($app->submitted != 1) {
		?>
				<input class="proceed_button" type="submit" name="add" value="Add" />
		<?php
			}
		?>
			</div>
			<!-- END Add -->

			<div class="form_line"></div>
			
			<!-- Current -->
			<div class="jobs_subheader">Current Employment</div>
	<?php
		if ($curEmployment->employmentStatus == 'Unemployed') {
	?>
			<div class="first">Unemployed. <?php if ($app->submitted != 1) { ?><a class="small" href="application_employment_current.php?appID=<?php print $_GET['appID']; ?>&edit=true&changeStatus=true#anchor">Change Status</a> <?php } ?></div>
	<?php
		}
		else {
	?>
			<div class="first"><?php print $curEmployment->employer; ?>. <span class="b">Post held:</span> <?php print $curEmployment->jobTitle; ?>. <a class="small" href="application_employment_current.php?appID=<?php print $_GET['appID']; ?>&edit=true#anchor">Edit</a></div>
	<?php
		}
	?>
			<div class="form_line"></div>
			<!-- END Current -->
			
			<!-- History -->
			<div class="jobs_subheader">Employment History</div>
<?php
	if (sizeof($empHistory) > 0) {
		$i = 1;
		foreach ($empHistory as $e) {
?>
			<div class="first"><span class="b"><?php print $i++; ?>.</span><?php print $e->employer; ?>.<span class="b"> Post held:</span><?php print $e->jobTitle; ?>. <a class="small" href="application_employment_history.php?appID=<?php print $_GET['appID']; ?>&empID=<?php print $e->id; ?>#anchor">Edit</a> | <a href="application_employment_history.php?appID=<?php print $_GET['appID']; ?>&empID=<?php print $e->id; ?>&action=remove#anchor">Remove</a></div>
			<div class="form_line"></div>
			<!-- END History -->
<?php
		}
	}
	
	if ($app->submitted != 1) {
?>
			<p>When you are satisfied you have completed your Employment History, click the 'Save &amp; Proceed' button below to continue with the application.</p>
			<!-- Save and Proceed button -->
			<div class="center">
				<input class="proceed_button" type="submit" name="saveProceed" value="Save &amp; Proceed" />
				</form>
			</div>
			<!-- END Save and Proceed button -->
<?php
	}
?>

		<!-- ENDS the slim central column -->
		</div>
		
		<!-- save for later -->
		<?php include("../includes/savelater.php"); ?>
		<!-- END save for later -->
		</form>
			
		<p class="jobs_first"><?php print METADATA_GENERIC_COUNCIL_NAME;?> is an equal opportunities employer.</p>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>