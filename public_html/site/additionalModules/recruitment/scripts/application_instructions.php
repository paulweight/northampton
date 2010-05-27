<?php
    include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");
	include_once("recruitment/JaduRecruitmentApplicationPersonalDetails.php");
	include_once("recruitment/JaduRecruitmentApplicationReferences.php");
	include_once("recruitment/JaduRecruitmentApplications.php");

	if (!isset($_SESSION['userID'])) {
	    header ("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}
	
	if (isset($_SESSION['userID'])){
	    $user = getUser($userID);
    }
	
	$app = getApplication($_GET['appID']);
	$job = getRecruitmentJob($app->jobID);
   	$cat = getRecruitmentCategory($job->categoryID);
	
	if ($user->id != $app->userID) {
    	header("Location: recruit_details.php?id=$job->id");
    	exit;
    }
    	
    setInstructionsViewed($app->id);
    
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
                <a href="http://<?php print $DOMAIN; ?>/site/">Home</a> | <a href="user_home.php">Your Account</a> | <a href="application_details.php?appID=<?php print $app->id; ?>">Application details</a> | Application Notes
            </div>
            <!-- END Breadcrumb --><!-- googleon:all -->
    
            <h1>Application for employment</h1>
            
            <p class="first"><span class="b">Position:</span> <?php print $job->title;?> <span class="b">Closing Date:</span> <?php print date("l jS F y", $job->closingDate);?></p>
                
            <!-- Step / progress box -->
            <?php 
                include("/home/$USERNAME/public_html/site/includes/application_sections.php"); 
            ?>
            <!-- END Step / progress box -->
                
            <h2><span class="h">Step 1:</span> Application Notes</h2>
            
            <p class="first">Please <span class="b">read all the notes</span> before completing the application form.</p>
            
            <p>Solemus haereticis compendii gratia de posteritate praescribere. In quantum enim ueritatis regula prior, quae etiam haereses futuras renuntiauit, in tantum posteriores quaeque doctrinae haereses praeiudicabuntur, quia sunt quae futurae ueritatis antiquiore regula praenuntiabantur.</p>
            
            <p>Solemus haereticis compendii gratia de posteritate praescribere. In quantum enim ueritatis regula prior, quae etiam haereses futuras renuntiauit, in tantum posteriores quaeque doctrinae haereses praeiudicabuntur, quia sunt quae futurae ueritatis antiquiore regula praenuntiabantur.</p>
            
            <p>Solemus haereticis compendii gratia de posteritate praescribere. In quantum enim ueritatis regula prior, quae etiam haereses futuras renuntiauit, in tantum posteriores quaeque doctrinae haereses praeiudicabuntur, quia sunt quae futurae ueritatis antiquiore regula praenuntiabantur.</p>
            
            <p>Solemus haereticis compendii gratia de posteritate praescribere. In quantum enim ueritatis regula prior, quae etiam haereses futuras renuntiauit, in tantum posteriores quaeque doctrinae haereses praeiudicabuntur, quia sunt quae futurae ueritatis antiquiore regula praenuntiabantur.</p>
            
            <p>Solemus haereticis compendii gratia de posteritate praescribere. In quantum enim ueritatis regula prior, quae etiam haereses futuras renuntiauit, in tantum posteriores quaeque doctrinae haereses praeiudicabuntur, quia sunt quae futurae ueritatis antiquiore regula praenuntiabantur.</p>
            
            <p>Solemus haereticis compendii gratia de posteritate praescribere. In quantum enim ueritatis regula prior, quae etiam haereses futuras renuntiauit, in tantum posteriores quaeque doctrinae haereses praeiudicabuntur, quia sunt quae futurae ueritatis antiquiore regula praenuntiabantur.</p>
            <!-- Proceed button -->
    <?php
        if ($app->submitted != 1) {
    ?>
            <div class="center">
                <form method="POST" ACTION="application_personal.php?appID=<?php print $app->id; ?>#anchor">
                    <input class="proceed_button" type="submit" name="submit" value="Proceed to Step 2" />
                </form>
            </div>
    <?php
        }
    ?>
            <!-- END Proceed button -->
                
            <p class="jobs_first"><?php print METADATA_GENERIC_COUNCIL_NAME;?> is an equal opportunities employer.</p>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>