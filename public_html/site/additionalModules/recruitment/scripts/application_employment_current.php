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
	    	    header("Location: ". buildJobApplicationURL('details',$app->id));
	    	    exit;
			}
			
    		if (isset($_POST['saveProceed']) || isset($_POST['saveExit'])){
    		
    			$tmpEmp = getCurrentEmployment($app->id);
    			$previousEmploymentStatus = $tmpEmp->employmentStatus;



    			// get all the new details
    			$emp->applicationID 	 = $_GET['appID'];
				$emp->employer 			 = $_POST['employer'];
				$emp->employerAddress 	 = $_POST['employerAddress'];
				$emp->employerBusiness 	 = $_POST['employerBusiness'];
				$emp->phone 			 = $_POST['phone'];
				$emp->fax 				 = $_POST['fax'];;
				$emp->email 			 = $_POST['email'];
				$emp->jobTitle 			 = $_POST['jobTitle'];
				$emp->startDate 		 = $_POST['start_day'] . "-" . $_POST['start_month'] . "-" . $_POST['start_year'];
				$emp->salary 			 = $_POST['salary'];
				$emp->availableStartDate = $_POST['newStart_day'] . "-" . $_POST['newStart_month'] . "-" . $_POST['newStart_year'];
				$emp->employmentStatus	 = $_POST['employmentStatus'];

    			if (getCurrentEmployment($_GET['appID']) == null) {
    				// see if there's anything missing
    				$missing = getMissingCurrentEmploymentDetails($emp);
					addCurrentEmployment($emp);
				}
				else {
					// see if there's anything missing
	    			$missing = getMissingCurrentEmploymentDetails($emp);
					updateCurrentEmployment($emp);
				}

    			if (isset($_POST['saveProceed']) && sizeof($missing) < 1) {
	    			header("Location: ". buildJobApplicationURL('employmentHistory', $appID));
	    			exit;
	    		}
	    		elseif (isset($_POST['saveExit'])) {
					header("Location: ". buildJobApplicationURL('details', $appID));
					exit;
	    		}
	    	}
	    	$emp = getCurrentEmployment($app->id);

	    	if ((($previousEmploymentStatus == "Unemployed") || $tmpEmp == null) && !isset($_POST['statusChanged'])) {
    			unset($missing);
    		}

    		if ($emp != null && sizeof(getMissingCurrentEmploymentDetails($emp)) < 1 && !isset($_GET['edit']) && !isset($_GET['changeStatus'])) {
    			$viewAppString = "";
    			if (isset($_GET['viewApp'])) {
    				$viewAppString = "&viewApp=true";
    			}
    			header("Location: ". buildJobApplicationURL('employmentHistory', $appID));
    			exit;
    		}

	    	$start = explode("-", $emp->startDate);
	    	$start_day = $start[0];
	    	$start_month = $start[1];
	    	$start_year = $start[2];
	    	
	    	$newStart = explode("-", $emp->availableStartDate);
	    	$newStart_day = $newStart[0];
	    	$newStart_month = $newStart[1];
	    	$newStart_year = $newStart[2];
    	}
	}
	else {
		header ("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}
	
	$breadcrumb = 'application_employment_current';
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
             <form action="<?php print getSiteRootURL() . buildJobApplicationURL('employmentCurrent', $_GET['appID']); ?>" method="post" enctype="multipart/form-data" enctype="x-www-form-encoded" class="basic_form">
<?php
	if (isset($_GET['changeStatus'])) {
?>
            <input type="hidden" name="statusChanged" value="true" />
<?php	
        }
        if (($emp->employmentStatus == "Unemployed" || $emp->employmentStatus == "Employed") && $_GET['changeStatus'] != 'true') {
?>
                <input type="hidden" name="employmentStatus" value="<?php print encodeHtml($emp->employmentStatus); ?>" />
                <p>Current Employment Status: <strong><?php print encodeHtml($emp->employmentStatus); ?></strong> - <a href="<?php print getSiteRootURL(). buildJobApplicationURL('employmentCurrent', $_GET['appID'], 'changeStatus'); ?>" >Change Status</a></p>
<?php
        }
        else {
?>
                <!-- employment status -->
                <p>
                	<label for="employmentStatus">Current Employment Status * </label>
                    <select id="employmentStatus" class="jobs_date" name="employmentStatus" >
                        <option <?php if ($emp->employmentStatus == "Employed") print "selected"; ?> value="Employed">Employed</option>
                        <option <?php if ($emp->employmentStatus == "Unemployed") print "selected"; ?> value="Unemployed">Unemployed</option>
                    </select>
                    <span class="clear"></span>
                </p>
<?php
        }

        if ($emp->employmentStatus == 'Employed' && $_GET['changeStatus'] != 'true') {
?>			
                <h3>Current Employment</h3>
                
                <p><label for="employer"><?php if ($missing['employer']) { ?><span class="star">! <?php } ?>Employer <?php if ($missing['employer']) { ?></span><?php } ?>* </label><input id="employer" type="text" name="employer" class="field" value="<?php print encodeHtml($emp->employer); ?>" /></p>
                <!-- END employer -->
                                
                <!-- location -->
                <p><label for="employerAddress"><?php if ($missing['employerAddress']) { ?><span class="star">! <?php } ?>Address of Employer<?php if ($missing['employerAddress']) { ?></span><?php } ?>* </label><textarea id="employerAddress" name="employerAddress" class="jobs_form" rows="4"><?php print encodeHtml($emp->employerAddress); ?></textarea></p>
                <!-- END location -->
                                
                <!-- from date -->	
                <p class="date_birth">
                    <label><?php if ($missing['startDate']) { ?><span class="star">! <?php } ?>Start date <?php if ($missing['startDate']) { ?></span><?php } ?> * </label>
                    <label for="day" class="multipleinput"><em>day</em> <input id="day" type="text" name="start_day" value="<?php print $start_day;?>" size="2" maxlength="2" class="dob"  /></label>
                    <label for="month" class="multipleinput"><em>month</em> <input id="month" type="text" name="start_month" value="<?php print $start_month;?>" size="2" maxlength="2" class="dob" /></label>
                    <label for="startyear" class="multipleinput"><em>year</em> <input id="startyear" type="text" name="start_year" value="<?php print $start_year;?>" size="4" maxlength="4" class="dob"  /></label>
                	<span class="clear"></span>
                </p>
                <!-- END from date -->

                <!-- salary -->
                <p>
	                <label for="salary"><?php if ($missing['salary']) { ?><span class="star">! <?php } ?>Salary / Grade<?php if ($missing['salary']) { ?></span><?php } ?> * </label> 
	                <input id="salary" type="text" name="salary" class="field" value="<?php print encodeHtml($emp->salary); ?>" />
                </p>
                <!-- END salary -->
                
                <!-- employers business -->
                <p>
	                <label for="employerBusiness"><?php if ($missing['employerBusiness']) { ?><span class="star">! <?php } ?>Employers business<?php if ($missing['employerBusiness']) { ?></span><?php } ?> * </label>
	                <input id="employerBusiness" type="text" name="employerBusiness" class="field" value="<?php print encodeHtml($emp->employerBusiness); ?>" />
                </p>
                <!-- END employers business -->
                          
                <!-- telephone -->
                <p>
 	               <label for="phone"><?php if ($missing['phone']) { ?><span class="star">! <?php } ?>Telephone<?php if ($missing['phone']) { ?></span><?php } ?> * </label>
 	               <input id="phone" type="text" name="phone" class="field" value="<?php print encodeHtml($emp->phone); ?>" />
                </p>
                <!-- END telephone -->
                
                <!-- fax -->
                <p>
  	              	<label for="fax">Fax </label>
    	            <input id="fax" type="text" name="fax" class="field" value="<?php print encodeHtml($emp->fax); ?>" />
                </p>
                <!-- END fax -->
    
                <!-- email -->
                <p>
         	       	<label for="email"><?php if ($missing['email']) { ?><span class="star">! <?php } ?>Email<?php if ($missing['email']) { ?></span><?php } ?> </label>
           	     	<input id="email" type="text" name="email" class="field" value="<?php print encodeHtml($emp->email); ?>" />
                </p>
                <!-- END email -->
    
                <!-- post -->
                <p>
                	<label for="jobTitle"><?php if ($missing['jobTitle']) { ?><span class="star">! <?php } ?>Post held / Type of work done<?php if ($missing['jobTitle']) { ?></span><?php } ?> * </label>
                	<input id="jobTitle" type="text" name="jobTitle" class="field" value="<?php print encodeHtml($emp->jobTitle); ?>" />
                	<span class="clear"></span>
                </p>
    
                <!-- start -->
                <p class="date_birth">
                    <label><?php if ($missing['availableStartDate']) { ?><span class="star">! <?php } ?>When can you start? <?php if ($missing['availableStartDate']) { ?></span><?php } ?> * </label>
                    <label for="newStart_day"><em>day</em> <input id="newStart_day" type="text" name="newStart_day" value="<?php print $newStart_day;?>" size="2" maxlength="2" class="dob" /></label>
                    <label for="newStart_month"><em>month</em> <input id="newStart_month" type="text" name="newStart_month" value="<?php print $newStart_month;?>" size="2" maxlength="2" class="dob" /></label>
                    <label for="year"><em>year</em> <input id="newStart_year" type="text" name="newStart_year" value="<?php print $newStart_year;?>" size="4" maxlength="4" class="dob" /></label>
                	<span class="clear"></span>
                </p>
                <!-- END start -->
    
<?php
        }
        
        if ($app->submitted != 1) {
?>
                <!-- proceed button -->
                <p class="center">
                    <input class="button" type="submit" name="saveProceed" value="Save &amp; Proceed" />
                </p>
                <!-- END proceed button -->
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