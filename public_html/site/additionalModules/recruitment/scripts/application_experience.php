<?php
    include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("marketing/JaduAdverts.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");
	include_once("recruitment/JaduRecruitmentApplicationExperience.php");
	
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
	    	    header("Location: ". buildJobApplicationURL('details', $app->id));
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
	    			header("Location: ". buildJobApplicationURL('references', $appID));
	    			exit;
	    		}
	    		elseif (isset($_POST['saveExit'])) {
					header("Location: " . buildJobApplicationURL('details', $appID));
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

	$breadcrumb = 'application_experience';
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
	<h2 class="warning">Please ensure fields marked with ! are entered correctly</h2>
<?php
	}
?>

	 <form action="<?php print getSiteRootURL() . buildJobApplicationURL('experience', $_GET['appID']); ?>" method="post" enctype="multipart/form-data" enctype="x-www-form-encoded" class="basic_form">

		<p class="first"><?php if ($missing['relevantExperience']) { ?><span class="star">! Mandatory: </span><?php } ?>We are looking for evidence of how your experience, skills, knowledge and qualifications will enable you to perform effectively in this post.</p>

		<p class="first">A person specification will normally be supplied and you should detail how you meet the requirements set out, giving specific example where possible. You should also state how this post fits in with your longer term career planning.</p>

		<p>
			<label for="fieldismandatory">This field is mandatory</label>
			<textarea id="fieldismandatory" name="relevantExperience" class="jobs_txtarea" rows="28"><?php print encodeHtml($exp->relevantExperience); ?></textarea>
		</p>
		
		<h3>Transportation</h3>

		<p>
			<label><?php if ($missing['drivingLicense']) { ?><span class="star">! <?php } ?>Do you have a current driving license?<?php if ($missing['drivingLicense']) { ?></span> <?php } ?> *</label>
			<label for="drivingLicenseYes"><input id="drivingLicenseYes" type="radio" name="drivingLicense" value="yes" <?php if ($exp->drivingLicense == "yes") print "checked=\"checked\"";?> /> Yes</label>
			<label for="drivingLicenseNo"><input id="drivingLicenseNo" type="radio" name="drivingLicense" value="no" <?php if ($exp->drivingLicense == "no") print "checked=\"checked\"";?> /> No</label>
			<span class="clear"></span>
		</p>			

		<p>
			<label><?php if ($missing['licenseClass']) { ?><span class="star">! <?php } ?>If yes, please state the class.<?php if ($missing['licenseClass']) { ?></span> <?php } ?></label>
			<label for="PROV"><input id="PROV" type="radio" name="licenseClass" value="PROV" <?php if ($exp->licenseClass == "PROV") print "checked=\"checked\"";?> /> PROV</label>
			<label for="FULL"><input id="FULL" type="radio" name="licenseClass" value="FULL" <?php if ($exp->licenseClass == "FULL") print "checked=\"checked\"";?> /> FULL</label>
			<label for="HGV"><input id="HGV" type="radio" name="licenseClass" value="HGV" <?php if ($exp->licenseClass == "HGV") print "checked=\"checked\"";?> /> HGV</label>
			<span class="clear"></span>
		</p>
		
		<p>
			<label><?php if ($missing['carForWork']) { ?><span class="star">! <?php } ?>Do you own a car or other form of motorised transport which you are prepared to use for business if required?<?php if ($missing['carForWork']) { ?></span> <?php } ?> *</label>
			<label for="carForWorkYes"><input id="carForWorkYes" type="radio" name="carForWork" value="yes" <?php if ($exp->carForWork == "yes") print "checked=\"checked\"";?> /> Yes</label>
			<label for="carForWorkNo"><input id="carForWorkNo" type="radio" name="carForWork" value="no" <?php if ($exp->carForWork == "no") print "checked=\"checked\"";?> /> No</label>
			<span class="clear"></span>
		</p>
		
<?php
	if ($app->submitted != 1) {
?>
		<p class="center">
			<input type="submit" class="button" name="saveProceed" value="Save &amp; Proceed" />
		</p>
<?php
	}
?>
		<?php include("../includes/savelater.php"); ?>
	</form>
		
	<p class="notes"><?php print encodeHtml(METADATA_GENERIC_NAME); ?> is an equal opportunities employer.</p>

			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>