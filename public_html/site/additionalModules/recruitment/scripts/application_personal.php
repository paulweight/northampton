<?php
    include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");
	include_once("recruitment/JaduRecruitmentApplicationPersonalDetails.php");
	
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
    			$missing = getMissingFields($details);
    			
    			if (getPersonalDetails($_GET['appID']) == null) {
					addPersonalDetails($details);
				}
				else {
					updatePersonalDetails($details);
				}
    			
    			if (isset($_POST['saveProceed']) && sizeof($missing) < 1) {
	    			header("Location: application_equal_ops.php?appID=$appID");
	    			exit;
	    		}
	    		elseif (isset($_POST['saveExit'])) {
					header("Location: application_details.php?appID=$appID");
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
                <a href="http://<?php print $DOMAIN; ?>/site/">Home</a> | <a href="user_home.php">Your Account</a> | <a href="application_details.php?appID=<?php print $app->id; ?>">Application details</a> | Personal Details
            </div>
            <!-- END Breadcrumb --><!-- googleon:all -->
    
            <h1>Application for employment</h1>
            
            <p class="first"><span class="b">Position:</span> <?php print $job->title;?> <span class="b">Closing Date:</span> <?php print date("l jS F y", $job->closingDate);?></p>
                
            <!-- Step / progress box -->
            <?php 
                include("/home/$USERNAME/public_html/site/includes/application_sections.php"); 
            ?>
            <!-- END Step / progress box -->
            
            <p class="first">As a check to ensure that the Council's Equal Opportunity Policy is being carried out and therefore to eliminate unfair, or illegal discrimination, all applicants for employment with the council are asked to complete the following sections.  These details will be removed before the selection process begins.</p>
    
            <p class="first">Fields marked * are mandatory.
    
            <!-- Creates the slim central column -->
            <div id="jobs_centre">
    
            <h2><span class="h">Step 2:</span> Personal details</h2>
    
            <!-- ERROR -->
    <?php
        if (sizeof($missing) > 0) {
    ?>
            
            <div class="joberror">
                <h2>Please ensure fields marked with <span class="star">!</span> are entered correctly</h2>
            </div>
            
    <?php
        }
    ?>
            <!-- END ERROR -->
            
            <!-- Begin form area -->
            <form action="application_personal.php?appID=<?php print $_GET['appID']; ?>#anchor" method="post" enctype="x-www-form-encoded" class="jobs_form">
    
                <!-- forename component -->
                <div><label for="ForeName"><?php if ($missing['forename']) { ?><span class="star">! <?php } ?>Forename<?php if ($missing['forename']) { ?></span><?php } ?> * </label><input id="ForeName" type="text" name="forename" class="jobs_form" value="<?php print $details->forename;?>" /></div>
                <!-- END forename component -->
                
                <div class="form_line"></div>
                
                <!-- surname component -->
                <div><label for="Surname"><?php if ($missing['surname']) { ?><span class="star">! <?php } ?>Surname<?php if ($missing['surname']) { ?></span><?php } ?> * </label><input id="Surname" type="text" name="surname" class="jobs_form" value="<?php print $details->surname;?>" /></div>
                <!-- END surname component -->
                
                <div class="form_line"></div>
                
                <!-- designation component -->	
                <div>
                    <label for="Salutation"><?php if ($missing['salutation']) { ?><span class="star">! <?php } ?>Salutation<?php if ($missing['surname']) { ?></span><?php } ?> * </label>
                        <select id="Salutation" class="jobs_form" name="salutation">
                            <option <?php if ($details->salutation == "-1") print "selected"; ?> value="-1">Please Select</option>
                            <option <?php if ($details->salutation == "Mr") print "selected"; ?> value="Mr">Mr</option>
                            <option <?php if ($details->salutation == "Miss") print "selected"; ?> value="Miss">Miss</option>
                            <option <?php if ($details->salutation == "Mrs") print "selected"; ?> value="Mrs">Mrs</option>
                            <option <?php if ($details->salutation == "Ms") print "selected"; ?> value="Ms">Ms</option>
                            <option <?php if ($details->salutation == "Dr") print "selected"; ?> value="Dr">Dr</option>
                            <option <?php if ($details->salutation == "Prof.") print "selected"; ?> value="Prof.">Prof.</option>
                        </select>
                    </div>
                <!-- END designation component -->	
                
                    <div class="form_line"></div>
                    
                <!-- dob component -->
                <div>
                    <div class="multipleinput_label"><?php if ($missing['dob']) { ?><span class="star">! <?php } ?>Date of Birth <?php if ($missing['dob']) { ?></span><?php } ?> * </div> 
                    <label for="day" class="multipleinput">&nbsp;dd <input id="day" type="text" name="dob_day" value="<?php print $dob_day;?>" size="2" maxlength="2" class="jobs_date" /></label>
                    <label for="month" class="multipleinput">mm <input id="month" type="text" name="dob_month" value="<?php print $dob_month;?>" size="2" maxlength="2" class="jobs_date" /></label>
                    <label for="year" class="multipleinput">yyyy <input id="year" type="text" name="dob_year" value="<?php print $dob_year;?>" size="4" maxlength="4" class="jobs_date" /></label>
                </div>
                <!-- END dob component -->
                
                <div class="form_line"></div>
                
                <!-- address component -->
                <div><label for="Address"><?php if ($missing['address']) { ?><span class="star">! <?php } ?>Address<?php if ($missing['address']) { ?></span><?php } ?> * </label><textarea id="Address" name="address" class="jobs_form" rows="2"><?php print $details->address;?></textarea></div>
                <!-- END address component -->
                
                <div class="form_line"></div>
                
                <!-- post code component -->
                <div><label for="TownCity"><?php if ($missing['city']) { ?><span class="star">! <?php } ?>Town / City<?php if ($missing['city']) { ?></span><?php } ?> * </label><input id="TownCity" type="text" name="city" class="jobs_form" value="<?php print $details->city;?>" /></div>
                <!-- END post code component -->
                
                <div class="form_line"></div>
                
                <!-- post code component -->
                <div><label for="County"><?php if ($missing['county']) { ?><span class="star">! <?php } ?>County<?php if ($missing['county']) { ?></span><?php } ?> * </label><input id="County" type="text" name="county" class="jobs_form" value="<?php print $details->county;?>" /></div>
                <!-- END post code component -->
                
                <div class="form_line"></div>
                    
                <!-- post code component -->
                <div><label for="PostCode"><?php if ($missing['postcode']) { ?><span class="star">! <?php } ?>Post code<?php if ($missing['postcode']) { ?></span><?php } ?> * </label><input id="PostCode" type="text" name="postcode" class="jobs_form" value="<?php print $details->postcode;?>" /></div>
                <!-- END post code component -->
                
                <div class="form_line"></div>
                
                <!-- email component -->
                <div><label for="EmailAddress"><?php if ($missing['email']) { ?><span class="star">! <?php } ?>Email Address<?php if ($missing['email']) { ?></span><?php } ?> * </label><input id="EmailAddress" type="text" name="email" class="jobs_form" value="<?php print $details->email;?>" /></div>
                <!-- END email component -->
                
                <div class="form_line"></div>
        
                <!-- home telephone component -->
                <div><label for HomeTel><?php if ($missing['homePhone']) { ?><span class="star">! <?php } ?>Home Telephone<?php if ($missing['homePhone']) { ?></span><?php } ?> * </label><input id="HomeTel" type="text" name="homePhone" class="jobs_form" value="<?php print $details->homePhone;?>" /></div>
                <!-- END home telephone component -->
                
                <div class="form_line"></div>
    
                
                <!-- work telephone component -->
                <div><label for="WorkTel" >Work Telephone </label><input id="WorkTel" type="text" name="workPhone" class="jobs_form" value="<?php print $telephone;?>" /></div>
                <!-- END work telephone component -->
                
                <div class="form_line"></div>
                <div class="clear"></div>
    <?php
        if ($app->submitted != 1) {
    ?>
            <!-- Proceed button -->
            <div class="center">
                <input class="proceed_button" type="submit" name="saveProceed" value="Save &amp; Proceed" />
                <div class="clear"></div>
            </div>
    <?php
        }
    ?>
                <!-- END Proceed button -->
            
            <!-- ENDS the slim central column -->
            </div>
            <!-- save for later -->
            <?php include("../includes/savelater.php"); ?>
            <!-- END save for later -->
            </form>
                
            <p class="jobs_first"><?php print METADATA_GENERIC_COUNCIL_NAME;?> is an equal opportunities employer.</p>

			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>