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

	if (!Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
	    header ("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}
	
	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()){
	    $user = Jadu_Service_User::getInstance()->getSessionUser();
    }
	
	$app = getApplication($_GET['appID']);
	$job = getRecruitmentJob($app->jobID);
   	$cat = getRecruitmentCategory($job->categoryID);
	
	if ($user->id != $app->userID) {
    	header("Location: recruit_details.php?id=$job->id");
    	exit;
    }
    	
    setInstructionsViewed($app->id);
    
    $breadcrumb ='application_instructions';
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

	<p class="first">Please <strong>read all the notes</strong> before completing the application form.</p>
	
	<h3>Application Forms</h3>
	
	<p>Shortlisting decisions will be made using the application form and this is your only opportunity to tell us about you.  Therefore, it is essential that you fully complete the whole of the application form and tell us exactly how you feel you match our specified requirements in the job we are trying to fill.</p>

	<p>A CV is not acceptable either in full or part. However if you wish to return your application form in another format such as braille or on tape this will be welcomed.</p>

	<h3>General points to be aware of and remember</h3>

	<p>You should use the job profile provided when completing Step 6, "Information in Support of your Application".  The specification has been designed to show what the requirements for this position are.  It is, therefore, important that you provide supporting evidence to show that you can achieve these requirements.</p>

	<p>For example, if the post requires supervisory experience, it will not be enough to say that you have supervised staff without giving information as to what was involved, and your specific responsibilities such as monitoring work performance, training and development etc.</p>

	<p>When providing evidence or examples please remember that unpaid work or studies and work at home can be just as relevant to paid employment.</p>
	
	<p>If you would like any help filling in the form contact the Personnel Unit on 01200 414559.</p>
	
	<h3>Selection Process</h3>
	
	<p>If you are selected for interview you will be notified within three weeks of the closing date.  You may be required to carry out a Personal Profile Analysis prior to the interview.  This is a short exercise that should take no longer than ten minutes to complete.  You may also be asked to carry out other job-related exercises.  You will be informed of this in the interview letter.</p>

	<p>The interview panel will normally consist of two or three people, one of whom will be the line manager responsible for this position.  Interviews will normally take around 45 minutes.</p>

	<p>In most cases the outcome of the interviews will be decided on the same day that the interviews took place.  The successful candidate will be informed by telephone and the unsuccessful candidates will be informed in writing (unless told differently at interview).</p>

	<p>A contract of employment will be issued as soon as possible and the employment will be confirmed once the signed copy of the contract has been received along with the necessary medical clearance and satisfactory references.</p>

	<h3>Using this online form</h3>
	
	<p>There are seven steps to the form, all of which must be fully completed before you are able to submit it.  All fields or text areas that are marked with an asterisk(*) are mandatory and therefore must be completed.  Fields or text areas that do not have an asterisk, may be left or not filled in.</p>

	<p>You can navigate from STEP to STEP at any time in the process using the above links.  Once a STEP has been completed in full, a green tick will appear next to it.  This only indicates that all the required fields or text areas have been completed and does not indicate that the information is correct or checked for spelling.  If you are using the text only version of this site then the green graphical ticks will not be used.</p>

	<p>You must always save a page with the "Save &amp; Proceed" button before using the above STEP links.  On part-filling a section and using these links without saving will result in the loss of that information.</p>

	<p>You can always "Save for Later".  If for example, you need to check a referees details you can use the save for later button, which appears at the bottom of each page of each STEP.  You can then resume the application by signing in to this site and visiting the "Your Account" page.  The application will be saved there ready for completion or updating.</p>

	
	<p>Never leave the computer you are using for an application unattended and always save your progress before leaving your computer.  Not doing so may result in loss of information should your browser crash or time out.</p>

	<p><strong>Always double check your form for errors before submitting.</strong></p>

            <!-- Proceed button -->
    <?php
        if ($app->submitted != 1) {
    ?>
                <form method="post" enctype="multipart/form-data" class="basic_form" action="<?php print getSiteRootURL() .  buildJobApplicationURL('personal', $app->id) ; ?>">
					<p class="center">
                  		<input class="button" type="submit" name="submit" value="Proceed to Step 2" />
                  	</p>
                </form>
    <?php
        }
    ?>
            <!-- END Proceed button -->
                
            <p class="note"><?php print encodeHtml(METADATA_GENERIC_NAME); ?> is an equal opportunities employer.</p>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>