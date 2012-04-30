<?php
    include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");
	include_once("recruitment/JaduRecruitmentApplicationPersonalDetails.php");
	
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
			
    		// get any personal info details if they have already been entered    		
    		$details = getPersonalDetails($app->id);

    		if (isset($_POST['saveProceed']) || isset($_POST['saveExit'])){
    		
    			// get all the new details
				$details->address 		= $_POST['address'];
				$details->applicationID = $_GET['appID'];
				$details->email 		= $_POST['email'];
				$details->address 		= $_POST['address'];
				$details->city 			= $_POST['city'];
				$details->county 		= $_POST['county'];
				$details->forename 		= $_POST['forename'];;
				$details->surname 		= $_POST['surname'];
				$details->salutation 	= $_POST['salutation'];
				$details->homePhone 	= $_POST['homePhone'];
				$details->workPhone 	= $_POST['workPhone'];
				$details->postcode		= $_POST['postcode'];
				$details->dob			= $_POST['dob_day'] . "-" . $_POST['dob_month'] . "-" . $_POST['dob_year'];
    		
    			// see if there's anything missing
    			$missing = getRecruitmentMissingFields($details);
    			
    			if (getPersonalDetails($_GET['appID']) == null) {
					addPersonalDetails($details);
				}
				else {
					updatePersonalDetails($details);
				}
    			
    			if (isset($_POST['saveProceed']) && sizeof($missing) < 1) {
	    			header("Location:" . buildJobApplicationURL('equalOps', $appID));
	    			exit;
	    		}
	    		elseif (isset($_POST['saveExit'])) {
					header("Location:" .buildJobApplicationURL('details', $appID));
					exit;
	    		}
	    	}
	    	
	    	$dob = explode("-", $details->dob);
	    	$dob_day = $dob[0];
	    	$dob_month = $dob[1];
	    	$dob_year = $dob[2];
    	}
	}
	else {
		header ("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}

	$breadcrumb = 'application_personal';
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

	<p class="first">As a check to ensure that the Council's Equal Opportunity Policy is being carried out and therefore to eliminate unfair, or illegal discrimination, all applicants for employment with the council are asked to complete the following sections.  These details will be removed before the selection process begins.</p>
  
<?php
        if (sizeof($missing) > 0) {
?>
            
                <h2 class="warning">Please ensure fields marked with <span class="star">!</span> are entered correctly</h2>
            
<?php
        }
?>
            <!-- END ERROR -->
            
            <!-- Begin form area -->
            <form action="<?php print getSiteRootURL() . buildJobApplicationURL('personal', $_GET['appID']);?>" method="post" enctype="multipart/form-data" enctype="x-www-form-encoded" class="basic_form">
    
                <!-- forename component -->
                <p>
                	<label for="ForeName"><?php if ($missing['forename']) { ?><span class="star">! <?php } ?>Forename<?php if ($missing['forename']) { ?></span><?php } ?> * </label>
                	<input id="ForeName" type="text" name="forename" class="field" value="<?php print encodeHtml($details->forename); ?>" />
                </p>
                <!-- END forename component -->
                                
                <!-- surname component -->
                <p>
                	<label for="Surname"><?php if ($missing['surname']) { ?><span class="star">! <?php } ?>Surname<?php if ($missing['surname']) { ?></span><?php } ?> * </label>
                	<input id="Surname" type="text" name="surname" class="field" value="<?php print encodeHtml($details->surname); ?>" />
                </p>
                <!-- END surname component -->
                                
                <!-- designation component -->	
                <p>
                    <label for="Salutation"><?php if ($missing['salutation']) { ?><span class="star">! <?php } ?>Salutation<?php if ($missing['surname']) { ?></span><?php } ?> * </label>
					<select id="Salutation" name="salutation">
						<option <?php if ($details->salutation == "-1") print "selected"; ?> value="-1">Please Select</option>
						<option <?php if ($details->salutation == "Mr") print "selected"; ?> value="Mr">Mr</option>
						<option <?php if ($details->salutation == "Miss") print "selected"; ?> value="Miss">Miss</option>
						<option <?php if ($details->salutation == "Mrs") print "selected"; ?> value="Mrs">Mrs</option>
						<option <?php if ($details->salutation == "Ms") print "selected"; ?> value="Ms">Ms</option>
						<option <?php if ($details->salutation == "Dr") print "selected"; ?> value="Dr">Dr</option>
						<option <?php if ($details->salutation == "Prof.") print "selected"; ?> value="Prof.">Prof.</option>
					</select>
                </p>
                <!-- END designation component -->	
                                   
                <!-- dob component -->
				<p class="date_birth">
                    <label><?php if ($missing['dob']) { ?><span class="star">! <?php } ?>Date of Birth <?php if ($missing['dob']) { ?></span><?php } ?> * <label>
					<span class="clear"></span>
                    <label for="day">
                    	<em>dd</em> 
						<input id="day" type="text" name="dob_day" value="<?php print $dob_day;?>" size="2" maxlength="2" class="dob" />
					</label>
                    <label for="month">						
                    	<em>mm</em> 
						<input id="month" type="text" name="dob_month" value="<?php print $dob_month;?>" size="2" maxlength="2" class="dob" />
					</label>
                    <label for="year">						
                    	<em>yyyy</em> 
						<input id="year" type="text" name="dob_year" value="<?php print $dob_year;?>" size="4" maxlength="4" class="dob" />
					</label>
					<span class="clear"></span>
                </p>
                <!-- END dob component -->
                               
                <!-- address component -->
                <p>
                	<label for="Address"><?php if ($missing['address']) { ?><span class="star">! <?php } ?>Address<?php if ($missing['address']) { ?></span><?php } ?> * </label>
                	<textarea id="Address" name="address" rows="2"><?php print encodeHtml($details->address); ?></textarea>
                </p>
                <!-- END address component -->
                               
                <!-- post code component -->
                <p>
                	<label for="TownCity"><?php if ($missing['city']) { ?><span class="star">! <?php } ?>Town / City<?php if ($missing['city']) { ?></span><?php } ?> * </label>
                	<input id="TownCity" type="text" name="city" class="field" value="<?php print encodeHtml($details->city); ?>" />
                </p>
                <!-- END post code component -->
                                
                <!-- post code component -->
                <p>
                	<label for="County"><?php if ($missing['county']) { ?><span class="star">! <?php } ?>County<?php if ($missing['county']) { ?></span><?php } ?> * </label>
                	<input id="County" type="text" name="county" class="field" value="<?php print encodeHtml($details->county); ?>" />
                </p>
                <!-- END post code component -->
                                    
                <!-- post code component -->
                <p>
                	<label for="PostCode"><?php if ($missing['postcode']) { ?><span class="star">! <?php } ?>Post code<?php if ($missing['postcode']) { ?></span><?php } ?> * </label>
                	<input id="PostCode" type="text" name="postcode" class="field" value="<?php print encodeHtml($details->postcode); ?>" />
                </p>
                <!-- END post code component -->
                                
                <!-- email component -->
                <p>
                	<label for="EmailAddress"><?php if ($missing['email']) { ?><span class="star">! <?php } ?>Email Address<?php if ($missing['email']) { ?></span><?php } ?> * </label>
                	<input id="EmailAddress" type="text" name="email" class="field" value="<?php print encodeHtml($details->email); ?>" />
                </p>
                <!-- END email component -->
                        
                <!-- home telephone component -->
                <p>
                	<label for="HomeTel"><?php if ($missing['homePhone']) { ?><span class="star">! <?php } ?>Home Telephone<?php if ($missing['homePhone']) { ?></span><?php } ?> * </label>
                	<input id="HomeTel" type="text" name="homePhone" class="field" value="<?php print encodeHtml($details->homePhone); ?>" />
                </p>
                <!-- END home telephone component -->
                    
                
                <!-- work telephone component -->
                <p>
                	<label for="WorkTel" >Work Telephone </label>
                	<input id="WorkTel" type="text" name="workPhone" class="field" value="<?php print encodeHtml($telephone); ?>" />
                </p>
                <!-- END work telephone component -->
                
<?php
        if ($app->submitted != 1) {
?>
            	<!-- Proceed button -->
				<p class="center">
					<input class="button" type="submit" name="saveProceed" value="Save &amp; Proceed" />
				</p>
<?php
        }
?>
                <!-- END Proceed button -->
            
            <!-- save for later -->
            <?php include("../includes/savelater.php"); ?>
            <!-- END save for later -->
            </form>
                
            <p class="note"><?php print encodeHtml(METADATA_GENERIC_NAME); ?> is an equal opportunities employer.</p>

			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>