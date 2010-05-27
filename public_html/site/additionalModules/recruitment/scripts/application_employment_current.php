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
	    			header("Location: application_employment_history.php?appID=$appID#anchor");
	    			exit;
	    		}
	    		elseif (isset($_POST['saveExit'])) {
					header("Location: application_details.php?appID=$appID#anchor");
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
    			header("Location: application_employment_history.php?appID=$appID$viewAppString");
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
                <a href="http://<?php print $DOMAIN; ?>/site/" >Home</a> | <a href="user_home.php">Your Account</a> | <a href="application_details.php?appID=<?php print $app->id; ?>">Application details</a> | Employment
            </div>
            <!-- END Breadcrumb --><!-- googleon:all -->
    
            <h1>Application for employment</h1>
            
            <p class="first"><span class="b">Position:</span> <?php print $job->title;?> <span class="b">Closing Date:</span> <?php print date("l jS F y", $job->closingDate);?></p>
            
            
            <!-- Step / progress box -->
            <?php include_once("../includes/application_sections.php"); ?>
            <!-- END Step / progress box -->
    
            <p class="first">Fields marked * are manditory.
    
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
             <form action="application_employment_current.php?appID=<?php print $_GET['appID']; ?>#anchor" method="post" enctype="x-www-form-encoded" class="jobs_form">
    <?php
        if (isset($_GET['changeStatus'])) {
    ?>
            <input type="hidden" name="statusChanged" value="true" />
    <?php	
        }
        if (($emp->employmentStatus == "Unemployed" || $emp->employmentStatus == "Employed") && $_GET['changeStatus'] != 'true') {
    ?>
                <input type="hidden" name="employmentStatus" value="<?php print $emp->employmentStatus; ?>" />
                <div class="jobs_centre">
                <p class="first">Current Employment Status: <span class="b"><?php print $emp->employmentStatus; ?></span> - <a href="application_employment_current.php?appID=<?php print $_GET['appID']; ?>&changeStatus=true#anchor"class="small"> Change Status</a></p>
                </div>
    <?php
        }
        else {
    ?>
                <!-- employment status -->
                <div><label for="employmentStatus">Current Employment Status * </label>
                    <select id="employmentStatus" class="jobs_date" name="employmentStatus" >
                        <option <?php if ($emp->employmentStatus == "Employed") print "selected"; ?> value="Employed">Employed</option>
                        <option <?php if ($emp->employmentStatus == "Unemployed") print "selected"; ?> value="Unemployed">Unemployed</option>
                    </select>
                </div>
    <?php
        }
    ?>
                <div class="form_line"></div>
    
                <!-- END employment status -->
    <?php
        if ($emp->employmentStatus == 'Employed' && $_GET['changeStatus'] != 'true') {
    ?>			
                <!-- Current Employment -->
                <div class="jobs_subheader">Current Employment</div>
                
                <div><label for="employer"><?php if ($missing['employer']) { ?><span class="star">! <?php } ?>Employer <?php if ($missing['employer']) { ?></span><?php } ?>* </label><input id="employer" type="text" name="employer" class="jobs_form" value="<?php print $emp->employer;?>" /></div>
                <!-- END employer -->
                
                <div class="form_line"></div>
                
                <!-- location -->
                <div><label for="employerAddress"><?php if ($missing['employerAddress']) { ?><span class="star">! <?php } ?>Address of Employer<?php if ($missing['employerAddress']) { ?></span><?php } ?>* </label><textarea id="employerAddress" name="employerAddress" class="jobs_form" rows="4"><?php print $emp->employerAddress;?></textarea></div>
                <!-- END location -->
                
                <div class="form_line"></div>
                
                <!-- employer -->
                
                <!-- from date -->	
                <div>
                    <div class="multipleinput_label"><?php if ($missing['startDate']) { ?><span class="star">! <?php } ?>Start date <?php if ($missing['startDate']) { ?></span><?php } ?> * </div>
                    <label for="day" class="multipleinput">&nbsp;day <input id="day" type="text" name="start_day" value="<?php print $start_day;?>" size="2" maxlength="2" class="jobs_date"  /></label>
                    <label for="month" class="multipleinput">month <input id="month" type="text" name="start_month" value="<?php print $start_month;?>" size="2" maxlength="2" class="jobs_date" /></label>
                    <label for="startyear" class="multipleinput">year <input id="startyear" type="text" name="start_year" value="<?php print $start_year;?>" size="4" maxlength="4" class="jobs_date"  /></label>
                </div>
                <!-- END from date -->
                
                <div class="form_line"></div>
                
                <!-- salary -->
                <div><label for="salary"><?php if ($missing['salary']) { ?><span class="star">! <?php } ?>Salary / Grade<?php if ($missing['salary']) { ?></span><?php } ?> * </label><input id="salary" type="text" name="salary" class="jobs_form" value="<?php print $emp->salary;?>" /></div>
                <!-- END salary -->
                
                <div class="form_line"></div>
                
                <!-- employers business -->
                <div><label for="employerBusiness"><?php if ($missing['employerBusiness']) { ?><span class="star">! <?php } ?>Employers business<?php if ($missing['employerBusiness']) { ?></span><?php } ?> * </label><input id="employerBusiness" type="text" name="employerBusiness" class="jobs_form" value="<?php print $emp->employerBusiness;?>" /></div>
                <!-- END employers business -->
                
                <div class="form_line"></div>
                
    
                <!-- telephone -->
                <div><label for="phone"><?php if ($missing['phone']) { ?><span class="star">! <?php } ?>Telephone<?php if ($missing['phone']) { ?></span><?php } ?> * </label><input id="phone" type="text" name="phone" class="jobs_form" value="<?php print $emp->phone;?>" /></div>
                <!-- END telephone -->
                
                <div class="form_line"></div>
                
                <!-- fax -->
                <div><label for="fax">Fax </label><input id="fax" type="text" name="fax" class="jobs_form" value="<?php print $emp->fax;?>" /></div>
                <!-- END fax -->
    
                <div class="form_line"></div>
    
                <!-- email -->
                <div><label for="email"><?php if ($missing['email']) { ?><span class="star">! <?php } ?>Email<?php if ($missing['email']) { ?></span><?php } ?> </label><input id="email" type="text" name="email" class="jobs_form" value="<?php print $emp->email;?>" /></div>
                <!-- END email -->
    
                <div class="form_line"></div>
    
                <!-- post -->
                <div><label for="jobTitle"><?php if ($missing['jobTitle']) { ?><span class="star">! <?php } ?>Post held / Type of work done<?php if ($missing['jobTitle']) { ?></span><?php } ?> * </label><input id="jobTitle" type="text" name="jobTitle" class="jobs_form" value="<?php print $emp->jobTitle;?>" /></div>
                <!-- END post -->
    
                <div class="form_line"></div>
                <!-- start -->
                <div>
                    <div class="multipleinput_label"><?php if ($missing['availableStartDate']) { ?><span class="star">! <?php } ?>When can you start? <?php if ($missing['availableStartDate']) { ?></span><?php } ?> * </div>
                    <label for="newStart_day" class="multipleinput">&nbsp;day <input id="newStart_day" type="text" name="newStart_day" value="<?php print $newStart_day;?>" size="2" maxlength="2" class="jobs_date" /></label>
                    <label for="newStart_month" class="multipleinput">month <input id="newStart_month" type="text" name="newStart_month" value="<?php print $newStart_month;?>" size="2" maxlength="2" class="jobs_date" /></label>
                    <label for="year" class="multipleinput">year <input id="newStart_year" type="text" name="newStart_year" value="<?php print $newStart_year;?>" size="4" maxlength="4" class="jobs_date" /></label>
                </div>
                <!-- END start -->
    
                <div class="form_line"></div>
    <?php
        }
        
        if ($app->submitted != 1) {
    ?>
                <!-- proceed button -->
                <div class="center">
                    <input class="proceed_button" type="submit" name="saveProceed" value="Save &amp; Proceed" />
                </div>
                <!-- END proceed button -->
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