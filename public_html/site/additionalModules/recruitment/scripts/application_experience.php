<?php
    include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("marketing/JaduAdverts.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");
	include_once("recruitment/JaduRecruitmentApplicationExperience.php");
	
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

    		if (isset($_POST['saveProceed']) || isset($_POST['saveExit'])){
    		
    			// get all the new details
    			$exp->applicationID 	 = $_GET['appID'];
				$exp->relevantExperience = $_POST['relevantExperience'];
				$exp->drivingLicense 	 = $_POST['drivingLicense'];
				$exp->licenseClass	 	 = $_POST['licenseClass'];
				$exp->carForWork		 = $_POST['carForWork'];
    		
    			if (getExperience($_GET['appID']) == null) {
    				// see if there's anything missing
    				$missing = getMissingExperienceDetails($exp);
					addExperience($exp);
				}
				else {
					// see if there's anything missing
	    			$missing = getMissingExperienceDetails($exp);
					updateExperience($exp);
				}

    			if (isset($_POST['saveProceed']) && sizeof($missing) < 1) {
	    			header("Location: application_references.php?appID=$appID#anchor");
	    			exit;
	    		}
	    		elseif (isset($_POST['saveExit'])) {
					header("Location: application_details.php?appID=$appID#anchor");
					exit;
	    		}
	    	}
	    	
    		$exp = getExperience($app->id);
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

			<!-- Breadcrumb --><!-- googleoff:all -->
			<div id="bc">
				<span class="bcb">&raquo;</span> 
				<a href="http://<?php print $DOMAIN; ?>/site/">Home</a> | <a href="user_home.php">Your Account</a> | <a href="application_details.php?appID=<?php print $app->id; ?>">Application details</a> | Equal Opportunities
			</div>
			<!-- END Breadcrumb --><!-- googleon:all -->
			
			<h1>Application for employment</h1>
						
						<p class="first"><span class="h">Position:</span> <?php print $job->title;?> <span class="h">Closing Date:</span> <?php print date("l jS F y", $job->closingDate);?></p>
						
						
						<?php 
							include("/home/$USERNAME/public_html/site/includes/application_sections.php"); 
						?>
		
						<!-- Creates the slim central column -->
						<div id="jobs_centre">
		
						<h2><span class="h">Step 6:</span> Information in Support of your Application</h2>
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
						 <form action="application_experience.php?appID=<?php print $_GET['appID'] ?>#anchor" method="post" enctype="x-www-form-encoded" class="jobs_form">
						 
							<!-- Text area one -->
							<div class="space">
								<p class="first"><?php if ($missing['relevantExperience']) { ?><span class="star">! Mandatory: </span><?php } ?>We are looking for evidence of how your experience, skills, knowledge and qualifications will enable you to perform effectively in this post.</p>
		
								<p class="first">A person specification will normally be supplied and you should detail how you meet the requirements set out, giving specific example where possible. You should also state how this post fits in with your longer term career planning.</p>
		
								<div class="jobs_txtarea_wrap">
								<label for="fieldismandatory">This field is mandatory</label>
									<textarea id="fieldismandatory" name="relevantExperience" class="jobs_txtarea" rows="28"><?php print $exp->relevantExperience; ?></textarea>
									<div class="clear"></div>
								</div>
							</div>
							<!-- END Text area one -->
							
							<div class="space"></div>
							<div class="form_line"></div>
							<div class="space"></div>
							
							<!-- Driving -->
							<div class="jobs_subheader">Transportation</div>
		
							<!-- question one -->
							
							<div class="check_column_wrap">
								<p class="first"><?php if ($missing['drivingLicense']) { ?><span class="star">! <?php } ?>Do you have a current driving license?<?php if ($missing['drivingLicense']) { ?></span> <?php } ?> *</p>
								<label for="drivingLicenseYes"><input id="drivingLicenseYes" type="radio" name="drivingLicense" value="yes" <?php if ($exp->drivingLicense == "yes") print "checked=\"checked\"";?> /> Yes</label>
								<label for="drivingLicenseNo"><input id="drivingLicenseNo" type="radio" name="drivingLicense" value="no" <?php if ($exp->drivingLicense == "no") print "checked=\"checked\"";?> /> No</label>
								<div class="clear"></div>
							</div>
							<!-- END question one -->
							
							
							<!-- question two -->
							
							<div class="check_column_wrap">
							<p class="first"><?php if ($missing['licenseClass']) { ?><span class="star">! <?php } ?>If yes, please state the class.<?php if ($missing['licenseClass']) { ?></span> <?php } ?></p>
								<label for="PROV"><input id="PROV" type="radio" name="licenseClass" value="PROV" <?php if ($exp->licenseClass == "PROV") print "checked=\"checked\"";?> /> PROV</label>
									<label for="FULL"><input id="FULL" type="radio" name="licenseClass" value="FULL" <?php if ($exp->licenseClass == "FULL") print "checked=\"checked\"";?> /> FULL</label>
									<label for="HGV"><input id="HGV" type="radio" name="licenseClass" value="HGV" <?php if ($exp->licenseClass == "HGV") print "checked=\"checked\"";?> /> HGV</label>
								<div class="clear"></div>
							</div>
							<!-- END question two -->
							
							<div class="space"></div>
							
							<!-- question three -->
							
							<div class="check_column_wrap">
							<p class="first"><?php if ($missing['carForWork']) { ?><span class="star">! <?php } ?>Do you own a car or other form of motorised transport which you are prepared to use for business if required?<?php if ($missing['carForWork']) { ?></span> <?php } ?> *</p>
								<label for="carForWorkYes"><input id="carForWorkYes" type="radio" name="carForWork" value="yes" <?php if ($exp->carForWork == "yes") print "checked=\"checked\"";?> /> Yes</label>
								<label for="carForWorkNo"><input id="carForWorkNo" type="radio" name="carForWork" value="no" <?php if ($exp->carForWork == "no") print "checked=\"checked\"";?> /> No</label>
								<div class="clear"></div>
							</div>
							<!-- END question three -->
							
							<!-- END Driving -->
					<?php
						if ($app->submitted != 1) {
					?>
							<!-- Proceed button -->
							<div class="center">
									<input type="submit" class="proceed_button" name="saveProceed" value="Save &amp; Proceed" />
								<div class="clear"></div>
							</div>
							<!-- END Proceed button -->
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