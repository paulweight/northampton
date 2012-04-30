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

	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
       	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()){
	    	$user = Jadu_Service_User::getInstance()->getSessionUser();
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
    
    $breadcrumb = 'application_details';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html<?php if (TEXT_DIRECTION == 'rtl') print ' dir="rtl"'; ?> xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Jobs</title>

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
            

	<h2><strong>Closing Date:</strong> <?php print date("l jS F Y", $job->closingDate);?></h2>

<?php
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
	else {
?>
	<!-- Step / progress box -->
	<div class="download_box">
		<h3><?php print $job->title;?> Application</h3>
		<ol class="info_left noList">
			<li><a href="<?php print getSiteRootURL() . buildJobApplicationURL('details', $app->id); ?>">Application home</a> (this page)</li>
			<li><img src="<?php print getStaticContentRootURL(); ?>/site/images/<?php if ($app->instructionsViewed == 1) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if ($app->instructionsViewed == 1) print "Completed"; else print "Incomplete"; ?>"/> <a href="<?php print getSiteRootURL() . buildJobApplicationURL('instructions', $app->id); ?>">Read Application Notes</a></li>
			<li><img src="<?php print getStaticContentRootURL(); ?>/site/images/<?php if (isPersonalDetailsComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isPersonalDetailsComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> <a href="<?php print getSiteRootURL() . buildJobApplicationURL( 'personal', $app->id); ?>">Personal Details</a></li>
			<li><img src="<?php print getStaticContentRootURL(); ?>/site/images/<?php if (isEqualOpsComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isEqualOpsComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> <a href="<?php print getSiteRootURL() . buildJobApplicationURL('equalOps', $app->id); ?>">Equal Opportunities</a></li>
			<li><img src="<?php print getStaticContentRootURL(); ?>/site/images/<?php if (isEducationComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isEducationComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> <a href="<?php print getSiteRootURL() . buildJobApplicationURL('education', $app->id); ?>">Education</a></li>
			<li><img src="<?php print getStaticContentRootURL(); ?>/site/images/<?php if (isCurrentEmploymentComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isCurrentEmploymentComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> <a href="<?php print getSiteRootURL() . buildJobApplicationURL('employmentCurrent', $app->id); ?>">Employment</a></li>
			<li><img src="<?php print getStaticContentRootURL(); ?>/site/images/<?php if (isExperienceComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isExperienceComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> <a  href="<?php print getSiteRootURL() . buildJobApplicationURL('experience', $app->id); ?>">Supporting Information</a></li>
			<li><img src="<?php print getStaticContentRootURL(); ?>/site/images/<?php if (areReferencesComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (areReferencesComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> <a  href="<?php print getSiteRootURL() . buildJobApplicationURL('references', $app->id); ?>">References</a></li>
		</ol>
		<div class="clear"></div>
	</div>
	<!-- END Step / progress box -->
 	<p class="first">Complete all the steps. As each step is fully completed a green tick will appear by it. When all steps are fully complete you will be able to submit the application.</p>
               
<?php
		if ($displaySubmit) {
?>	
	<!-- On application completion, show this next section -->
	<p class="first">You have now <strong>completed</strong> all sections of the application. Before submiting, we advise you double <strong>check each section</strong> and its details. Once you are happy with your application, click the submit button below.</p>
<?php
		}

		if (!$displaySubmit && !$alreadySubmitted) {
?>	
	<form method="post" enctype="multipart/form-data" action="<?php print getSiteRootURL() . buildJobApplicationURL('instructions', $app->id); ?>" class="basic_form" >
		<p class="center">
			<input class="button" type="submit" name="submit" value="Begin: Step 1" />
		</p>
	</form>
<?php
		}
		if ($displaySubmit) {
?>
	 <!-- On application completion, show this next section -->
	<form method="post" enctype="multipart/form-data" action="<?php print getSiteRootURL() . buildJobApplicationURL('details', $app->id); ?>" class="basic_form">
		<p class="center">
			<input class="button" type="submit" name="submit" value="Submit Your Application" />
		</p>
	</form>
	<!-- end SUBMIT BUTTON --> 
<?php
		}
	}
?>
                
	<p class="note"><?php print encodeHtml(METADATA_GENERIC_NAME); ?> is an equal opportunities employer.</p>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>