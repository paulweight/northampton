<?php
    include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");
	include_once("recruitment/JaduRecruitmentApplicationEducation.php");
	include_once("recruitment/JaduRecruitmentApplicationQualifications.php");

	define("DELETE_EST", 1);
	define("DELETE_QUAL", 2);
	define("EDIT_QUAL", 3);
	define("ADD_QUAL", 4);
	define("EDIT_EST", 5);

	function addToEstablishments()
	{
		$est = new ApplicationEducationEstablishment();
		$est->applicationID = $_GET['appID'];
		$est->establishment = $_POST['establishment'];
		$est->location = $_POST['location'];
		$est->dateStarted = $_POST['month_started'] . "-" . $_POST['year_started'];
		$est->dateFinished = $_POST['month_finished'] . "-" . $_POST['year_finished'];

		$est->id = addEducationEstablishment($est);
		
		$missing = getMissingEducationDetails($est);
		
		if (sizeof($missing) > 0) {
			$_GET['action'] = EDIT_EST;
			$_GET['estID'] = $est->id;
		}
		else {
			$_GET['estID'] = $est->id;
			$_GET['action'] = ADD_QUAL;
		}
		
		return $missing;
	}
	
	function updateEst()
	{
		$est = new ApplicationEducationEstablishment();
		$est->applicationID = $_GET['appID'];
		$est->id = $_POST['estID'];
		$est->establishment = $_POST['establishment'];
		$est->location = $_POST['location'];
		$est->dateStarted = $_POST['month_started'] . "-" . $_POST['year_started'];
		$est->dateFinished = $_POST['month_finished'] . "-" . $_POST['year_finished'];

		updateEducationEstablishment($est);

		$missing = getMissingEducationDetails($est);
		
		if (sizeof($missing) > 0) {
			$_GET['action'] = EDIT_EST;
			$_GET['estID'] = $est->id;
		}
		else {
			$_GET['estID'] = $est->id;
			$_GET['action'] = ADD_QUAL;
		}

		return $missing;
	}

	function addToQualifications()
	{
		$qual = new ApplicationQualification();
		$qual->applicationID = $_GET['appID'];
		$qual->educationID = $_POST['estID'];
		$qual->qualification = $_POST['qualification'];
		$qual->subject = $_POST['subject'];
		$qual->grade = $_POST['grade'];
		
		$qual->id = addQualification($qual);
		
		$missing = getMissingQualificationDetails($qual);
		
		if (sizeof($missing) > 0) {
			$_GET['action'] = EDIT_QUAL;
			$_GET['qualID'] = $qual->id;
			$_GET['estID'] = $_POST['estID'];
		}
		else {
			$_GET['estID'] = $_POST['estID'];
			$_GET['action'] = ADD_QUAL;
		}
		
		return $missing;
	}
	
	function updateQual()
	{
		$qual = new ApplicationQualification();
		$qual->applicationID = $_GET['appID'];
		$qual->id = $_POST['qualID'];
		$qual->qualification = $_POST['qualification'];
		$qual->subject = $_POST['subject'];
		$qual->grade = $_POST['grade'];
		
		updateQualification($qual);
		
		$missing = getMissingQualificationDetails($qual);
		
		if (sizeof($missing) > 0) {
			$_GET['action'] = EDIT_QUAL;
			$_GET['qualID'] = $qual->id;
			$_GET['estID'] = $_POST['estID'];
		}
		else {
			$_GET['estID'] = $_POST['estID'];
			$_GET['action'] = ADD_QUAL;
		}
		
		return $missing;
	}
	
	function removeEst()
	{
		deleteEstablishment($_GET['estID']);
		deleteQualificationsForEstablishment($_GET['estID']);
	}
	
	function removeQual()
	{	
		deleteQualification($_GET['qualID']);
	}
	
	if (isset($_SESSION['userID'])) {
	    if (isset($userID)){
		    $user = getUser($userID);
	}
	
	if (isset($user) && isset($_GET['appID'])){
	
	    	$appID = $_GET['appID'];
	    	
		    // get the application
	   		$app = getApplication($appID);
	   		
	   		if ($user->id != $app->userID) {
    		    header("Location: recruit_details.php?id=$job->id");
    		}
    		
    		$job = getRecruitmentJob($app->jobID);
		
	    	$cat = getRecruitmentCategory($job->categoryID);
		
	    	//if ($app->submitted == 1 && (!isset($_GET['viewApp']))) {
	    	//    header("Location: application_details.php?appID=$app->id");


	    	//   exit;
	    	//}
		
    		if (isset($_POST['saveProceed'])){
    			header("Location: application_employment_current.php?appID=$appID#anchor");
    			exit;
	    	}
		
    		elseif (isset($_POST['saveExit'])){
    			header("Location: application_details.php?appID=$appID");
    			exit;
	    	}

    		elseif (isset($_POST['addEstablishment'])){
    			$missing = addToEstablishments();
	    	}
		
    		elseif (isset($_POST['addQualification'])){
    			$missing = addToQualifications();
	    	}
		
    		elseif (isset($_POST['updateEstablishment'])){
    			$missing = updateEst();
	    	}
		
    		elseif (isset($_POST['updateQualification'])){
    			$missing = updateQual();
	    	}
		
    		elseif ($_GET['action'] == DELETE_EST){
    			removeEst();
	    	}
		
    		elseif ($_GET['action'] == DELETE_QUAL){
    			removeQual();
	    	}
	    	
	    	if ($_GET['action'] == EDIT_EST){
    			$est = getEducationEstablishment($_GET['estID']);
    			
    			list ($month_started, $year_started) = split("[-]",$est->dateStarted);
				list ($month_finished, $year_finished) = split("[-]",$est->dateFinished);
	    	}
	    	elseif ($_GET['action'] == EDIT_QUAL){
    			$qual = getQualification($_GET['qualID']);
	    	}
	    	
	    	$establishments = getEducationEstablishments($_GET['appID']);
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
                <a href="http://<?php print $DOMAIN; ?>/site/">Home</a> | <a href="user_home.php">Your Account</a> | <a href="application_details.php?appID=<?php print $app->id; ?>">Application details</a> | Education
            </div>
            <!-- END Breadcrumb --><!-- googleon:all -->
    
            <h1>Application for employment</h1>
            
    <p class="first"><span class="h">Position:</span> <?php print $job->title;?> <span class="h">Closing Date:</span> <?php print date("l jS F y", $job->closingDate);?></p>
    
    
    <!-- Step / progress box -->
    <?php 
        include("/home/$USERNAME/public_html/site/includes/application_sections.php"); 
    ?>
    <!-- END Step / progress box -->
    
    <p class="first">Enter education details in reverse chronological order.  Create a school / establishment and then add your qualifications to it.</p>

    <p class="first">Fields marked * are mandatory.

    <!-- Creates the slim central column -->
    <div id="jobs_centre">

    <h2><span class="h">Step 4:</span> Education</h2>
    
    <p class="first">Educational establishments will be listed in chronological order based on the start date.</p>
    
<?php
if (sizeof($missing) > 0) {
?>
    <!-- ERROR -->
    <div class="joberror">
        <p>Please ensure fields marked with <span class="star">!</span> are entered correctly</p>
    </div>
    <!-- END ERROR -->
<?php
}
?>

    <!-- Begin form area -->
     <form action="application_education.php?appID=<?php print $_GET['appID']; ?>#anchor" method="post" enctype="x-www-form-encoded" class="jobs_form">
<?php
if (!isset($_GET['action']) || $_GET['action'] == EDIT_EST || $_GET['action'] == DELETE_EST) {
    if ($_GET['action'] == EDIT_EST) {
?>
        <input type="hidden" name="estID" value="<?php print $_GET['estID']; ?>" />
<?php
    }
?>
    
    <!-- Add Establishment -->

        <!-- establishment name -->
        <div><label for="establishment" ><?php if ($missing['establishment']) { ?><span class="star">!<?php } ?> School/College/University name<?php if ($missing['establishment']) { ?></span><?php } ?> * </label><input id="establishment" type="text" name="establishment" class="jobs_form" value="<?php print $est->establishment;?>" /></div>
        <!-- END establishment name -->
        
        <div class="form_line"></div>
        
        <!-- location -->
        <div><label for="location"><?php if ($missing['location']) { ?><span class="star">!<?php } ?> Location<?php if ($missing['location']) { ?></span><?php } ?> * </label><textarea id="location" name="location" class="jobs_form" rows="2"><?php print $est->location;?></textarea></div>
        <!-- END location -->
        
        <div class="form_line"></div>
        
        <!-- from date -->	
        <div>
            <div class="multipleinput_label"><?php if ($missing['dateStarted']) { ?><span class="star">!<?php } ?> Start date <?php if ($missing['dateStarted']) { ?></span><?php } ?> * </div>
            <label for="day" class="multipleinput">mm <input type="text" name="month_started" value="<?php print $month_started;?>" size="2" maxlength="2" class="jobs_date" /></label>
            <label for="year" class="multipleinput">yyyy <input type="text" name="year_started" value="<?php print $year_started;?>" size="4" maxlength="4" class="jobs_date" /></label>
        <div class="clear"></div>
        </div>
        <!-- END from date -->
        
        <!-- end date -->	
        <div>
            <div class="multipleinput_label"><?php if ($missing['dateFinished']) { ?><span class="star">!<?php } ?> End date <?php if ($missing['dateFinished']) { ?></span><?php } ?> </div>
            <label for="day" class="multipleinput">mm <input type="text" name="month_finished" value="<?php print $month_finished;?>" size="2" maxlength="2" class="jobs_date" /></label>
            <label for="year" class="multipleinput">yyyy <input type="text" name="year_finished" value="<?php print $year_finished;?>" size="4" maxlength="4" class="jobs_date" /></label>
            <div class="clear"></div>
        </div>
        <p class="s">(If you are still attending, simply leave the end date blank)</p>
        <!-- END end date -->
        
        <div class="form_line"></div>

        <!-- Add button -->
        <div class="right">
        <?php
            if ($_GET['action'] == EDIT_EST && $app->submitted != 1) {
        ?>
                <input class="proceed_button" type="submit" name="updateEstablishment" value="Update" />
        <?php
            }
            elseif($app->submitted != 1) {
        ?>
                <input class="proceed_button" type="submit" name="addEstablishment" value="Add" />
        <?php

            }
        ?>
            <div class="form_line"></div>
        </div>
        <!-- END Add button -->
    <!-- END of Add Establishment -->
<?php
}
else {
    $est = getEducationEstablishment($_GET['estID']);
    if ($_GET['action'] == EDIT_QUAL) {
?>
        <input type="hidden" name="qualID" value="<?php print $_GET['qualID']; ?>" />
<?php
    }
?>
        <input type="hidden" name="estID" value="<?php print $_GET['estID']; ?>" />
        
        <div class="space"></div>

    <!-- Add Qualification -->
        <div class="jobs_subheader">Add Qualification for <?php print $est->establishment; ?></div>
        
        <p>Start with your most recent qualification or highest grade earned.</p>
        
        <!-- Qualification name -->
        <div><label for="qualification"><?php if ($missing['qualification']) { ?><span class="star">!<?php } ?> Qualification<?php if ($missing['qualification']) { ?></span><?php } ?> * </label><input id="qualification" type="text" name="qualification" class="jobs_form" value="<?php print $qual->qualification; ?>" /></div>
        <!-- END Qualification name -->
        
        <div class="form_line"></div>
        
        <!-- subject -->
        <div><label for="qualification"><?php if ($missing['subject']) { ?><span class="star">!<?php } ?> Subject / Course Title<?php if ($missing['subject']) { ?></span><?php } ?>* </label><input id="qualification" type="text" name="subject" class="jobs_form" value="<?php print $qual->subject; ?>" /></div>
        <!-- END subject -->
        
        <div class="form_line"></div>
        
        <!-- grade -->
        <div><label for="grade"><?php if ($missing['grade']) { ?><span class="star">!<?php } ?> Grade<?php if ($missing['grade']) { ?></span><?php } ?> * </label><input id="grade" type="text" name="grade" class="jobs_form" value="<?php print $qual->grade; ?>" /></div>
        <!-- END grade -->
        
        <div class="form_line"></div>

        <!-- Add button -->
        <div class="right">
        <?php
            if ($_GET['action'] == EDIT_QUAL && $app->submitted != 1) {
        ?>
                <input class="proceed_button" type="submit" name="updateQualification" value="Update" />
        <?php
            }
            elseif($app->submitted != 1) {
        ?>
                <input class="proceed_button" type="submit" name="addQualification" value="Add" />
        <?php
            }
        ?>
            <div class="form_line"></div>
        </div>
        <!-- END Add button -->
    <!-- END of Add Qualification -->
<?php
}
?>	
        <div class="space"></div>
    
        <!-- Entries so far -->
        <div class="jobs_heading"><span class="b">Entries so far:</span></div>
<?php
if (sizeof($establishments) > 0) {
    if ($app->submitted != 1) {
?>
    <p><a href="application_education.php?appID=<?php print $_GET['appID']; ?>">Add a new establishment</a></p>
<?php	
    }
    foreach ($establishments as $e) {
        $quals = getQualificationsForEstablishment($e->id);
?>
        <p><span class="jobs_subheader"><?php print $e->establishment; ?></span><?php if ($app->submitted != 1) { ?> - <span class="small"><a href="application_education.php?appID=<?php print $_GET['appID'] ?>&action=<?php print EDIT_EST . "&estID=$e->id"; ?>#anchor">Edit</a> | <a href="application_education.php?appID=<?php print $_GET['appID'] ?>&action=<?php print DELETE_EST . "&estID=$e->id"; ?>#anchor">Remove</a> | <a href="application_education.php?appID=<?php print $_GET['appID'] ?>&action=<?php print ADD_QUAL . "&estID=$e->id"; ?>#anchor">Add Qualification</a></span><?php } ?>
<?php
    if (sizeof($quals) < 1) {
?>
        <p class="b">You have yet to add your qualifications to this establishment </p>
<?php
    }
    else {
        $i = 1;
        foreach ($quals as $q) {
?>
        <p class="slim"><span class="b"><?php print $i++; ?>.</span> <?php print "$q->qualification, $q->subject, $q->grade"; ?><?php if ($app->submitted != 1) { ?> - <span class="small"><a href="application_education.php?appID=<?php print $_GET['appID'] ?>&action=<?php print EDIT_QUAL . "&qualID=$q->id&estID=$q->educationID"; ?>#anchor">Edit</a> | <a href="application_education.php?appID=<?php print $_GET['appID'] ?>&action=<?php print DELETE_QUAL . "&qualID=$q->id&estID=$q->educationID"; ?>#anchor">Remove</a> </span><?php } ?></p>
<?php
        }
    }
?>
        <div class="form_line"></div>
        <!-- END Entries so far -->
    

        <div class="space"></div>

<?php
    }
}
else {
?>
    <p class="b">You have yet to add any establishments </p>
<?php
}

if ($app->submitted != 1) {
?>
        <!-- Proceed button -->
        <div class="center">
            <input class="proceed_button" type="submit" name="saveProceed" value="Save &amp; Proceed" />
        </div>
        <!-- END Proceed button -->
    <!-- ENDS the slim central column -->
    </div>
<?php
}
?>
    
    <!-- save for later -->
    <?php include("../includes/savelater.php"); ?>
    <!-- END save for later -->
    </form>
        
    <p class="jobs_first"><?php print METADATA_GENERIC_COUNCIL_NAME;?> is an equal opportunities employer.</p>

			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>