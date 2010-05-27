<?php
	include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");
	include_once("recruitment/JaduRecruitmentApplicationPersonalDetails.php");
	include_once("recruitment/JaduRecruitmentApplicationReferences.php");
	include_once("recruitment/JaduRecruitmentApplications.php");

	if (isset($_SESSION['userID'])) {
       	if (isset($userID)){
	    	$user = getUser($userID);
     	}

    	if (isset($user) && isset($_GET['appID'])){

	    	$appID = $_GET['appID'];
	    	
    		if (isset($_POST['submit'])){
    			submitApplication($appID);
    			$app = getApplication($appID);
    			$job = getRecruitmentJob($app->jobID);
    			$_GET['viewApp'] = true;
    		}
	
	    	// if there's no application then create a new one
    		if ($appID == -1){
    			// create new application
    			$app = createNewApplication($user->id, $_GET['jobID']);
    			$job = getRecruitmentJob($app->jobID);
    			$appID = $app->id;
    		}

	    	// otherwise get the application details
    		else{
    			// get the application details
    			$app = getApplication($appID);
    			$job = getRecruitmentJob($app->jobID);
    			$appID = $app->id;
    		}
    		
    		if ($user->id != $app->userID){
    			header("Location: recruit_details.php?id=$job->id");
	    		exit;
    		}
    	}

    	$cat = getRecruitmentCategory($job->categoryID);

    	$alreadySubmitted = hasApplicationBeenSubmitted($appID);

    	$displaySubmit = allSectionsComplete($appID) && !$alreadySubmitted;

    	$viewAppString = '';

    	if (isset($_GET['viewApp']) || $app->submitted == 1){
    	    $viewAppString = '&viewApp=true';
    	}
    }
    else {
    	header ("Location: $ERROR_REDIRECT_PAGE");
    	exit;
    }
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Jobs</title>

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
            
	<h2>Position: <?php print $job->title;?> Closing Date: <?php print date("l jS F y", $job->closingDate);?></h2>

	<p class="first">Complete all the steps below. As each step is fully completed a green tick will appear by it. When all steps are fully complete you will be able to submit the application.</p>
                      
	<!-- Step / progress box -->
	<div class="display_box">
		<h3>Application Steps</h3>
		<ul id="appstep">
			<li><img src="http://<?php print $DOMAIN; ?>/site/images/application.gif" alt="" /> Application Home (This page)</li>
			<li><img src="http://<?php print $DOMAIN; ?>/site/images/<?php if ($app->instructionsViewed == 1) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if ($app->instructionsViewed == 1) print "Completed"; else print "Incomplete"; ?>"/> STEP 1 - <a href="application_instructions.php?appID=<?php print $app->id; ?>#anchor">Read Application Notes</a></li>
			<li><img src="http://<?php print $DOMAIN; ?>/site/images/<?php if (isPersonalDetailsComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isPersonalDetailsComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> STEP 2 - <a  href="application_personal.php?appID=<?php print $app->id; ?>#anchor">Personal Details</a></li>
			<li><img src="http://<?php print $DOMAIN; ?>/site/images/<?php if (isEqualOpsComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isEqualOpsComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> STEP 3 - <a  href="application_equal_ops.php?appID=<?php print $app->id; ?>#anchor">Equal Opportunities</a></li>
			<li><img src="http://<?php print $DOMAIN; ?>/site/images/<?php if (isEducationComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isEducationComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> STEP 4 - <a  href="application_education.php?appID=<?php print $app->id; ?>"#anchor>Education</a></li>
			<li><img src="http://<?php print $DOMAIN; ?>/site/images/<?php if (isCurrentEmploymentComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isCurrentEmploymentComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> STEP 5 - <a href="application_employment_current.php?appID=<?php print $app->id; ?>"#anchor>Employment</a></li>
			<li><img src="http://<?php print $DOMAIN; ?>/site/images/<?php if (isExperienceComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isExperienceComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> STEP 6 - <a  href="application_experience.php?appID=<?php print $app->id; ?>"#anchor>Supporting Information</a></li>
			<li><img src="http://<?php print $DOMAIN; ?>/site/images/<?php if (areReferencesComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (areReferencesComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> STEP 7 - <a  href="application_references.php?appID=<?php print $app->id; ?>"#anchor>References</a></li>
		</ul>
	</div>
	<!-- END Step / progress box -->
                
<?php
	if ($displaySubmit) {
?>	
	<!-- On application completion, show this next section -->
	<p class="first">You have now <strong>completed</strong> all sections of the application. Before submiting, we advise you double <strong>check each section</strong> and its details. Once you are happy with your application, click the submit button below.</p>
<?php
	}

	if (!$displaySubmit && !$alreadySubmitted) {
?>	
	<form method="post" action="application_instructions.php?appID=<?php print $appID; ?>#anchor" class="basic_form" >
		<p class="center">
			<input class="button" type="submit" name="submit" value="Begin: Step 1" />
		</p>
	</form>
<?php
	}
	if ($displaySubmit) {
?>
	 <!-- On application completion, show this next section -->
	<form method="post" action="application_details.php?appID=<?php print $app->id; ?>" class="basic_form">
		<p class="center">
			<input class="button" type="submit" name="submit" value="Submit Your Application" />
		</p>
	</form>
	<!-- end SUBMIT BUTTON --> 
<?php
	}
	if ($alreadySubmitted) {
		if ($app->accepted == -1) {
?>
	<h2>Thank you for your application.</h2>
	<p class="first">We will review your application and get in touch to update you on its progress.</p>
                    
<?php
		}
		if ($app->accepted == 0) {
?>
	<p>We have reviewed your application and on this occasion we will not be progressing your application any further.</p>
<?php
		}
		if ($app->accepted == 1) {
?>
	<p>We have reviewed your application and decided to take it to the next stage in the process. We will be in touch shortly to advise you what happens next.</p>
<?php
		}
	}
?>
                
	<p class="first"><?php print METADATA_GENERIC_COUNCIL_NAME;?> is an equal opportunities employer.</p>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>