<?php
    include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");
	include_once("recruitment/JaduRecruitmentApplicationEqualOpportunities.php");
	include_once("marketing/JaduAdverts.php");
	
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
    		$details = getEqualOps($app->id);

    		if (isset($_POST['saveProceed']) || isset($_POST['saveExit'])){
    		
    			// get all the new details
				$details->address 				= $_POST['address'];
				$details->applicationID 		= $_GET['appID'];
				$details->sicknessRecord 		= $_POST['sicknessRecord'];
				$details->relatedToCouncil 		= $_POST['relatedToCouncil'];
				$details->relatedToEmployee 	= $_POST['relatedToEmployee'];;
				$details->unavailableDates 		= $_POST['unavailableDates'];
				$details->ethnicOrigin 			= $_POST['ethnicOrigin'];
				$details->disabilityYesNo 		= $_POST['disabilityYesNo'];
				$details->disabilityRelevance 	= $_POST['disabilityRelevance'];
				$details->provisionOfAids		= $_POST['provisionOfAids'];
				$details->interviewArrangements	= $_POST['interviewArrangements'];
    		
    			if (getEqualOps($_GET['appID']) == null) {
					addEqualOps($details);
				}
				else {
					updateEqualOps($details);
				}
				
				// see if there's anything missing
    			$missing = getMissingEqualOpsDetails($app->id);
    			
    			if (isset($_POST['saveProceed']) && sizeof($missing) < 1) {
	    			header("Location: application_education.php?appID=$appID");
	    			exit;
	    		}
	    		elseif (isset($_POST['saveExit'])) {
					header("Location: application_details.php?appID=$appID");
					exit;
	    		}
	    	}
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
                <a href="http://<?php print $DOMAIN; ?>/site/">Home</a> | <a href="user_home.php">Your Account</a> | <a href="application_details.php?appID=<?php print $app->id; ?>">Application details</a> | Equal Opportunities
            </div>
            <!-- END Breadcrumb --><!-- googleon:all -->
    
            <h1>Application for employment</h1>
            
            <p class="first"><span class="b">Position:</span> <?php print $job->title;?> <span class="b">Closing Date:</span> <?php print date("l jS F y", $job->closingDate);?></p>
            
            <!-- Step / progress box -->
            <?php 
                include("/home/$USERNAME/public_html/site/includes/application_sections.php"); 
            ?>
            <!-- END Step / progress box -->
            
            <p>As a check to ensure that the Council's Equal Opportunity Policy is being carried out and therefore to eliminate unfair, or illegal discrimination, all applicants for employment with the council are asked to complete the following sections.  These details will be removed before the selection process begins.</p>
    
            <p>Fields marked * are mandatory.
    
            <!-- Creates the slim central column -->
            <div id="jobs_centre">
    
            <h2><span class="h">Step 3:</span> Equal Opportunities</h2>
    <?php
        if (sizeof($missing) > 0) {
    ?>	
            <!-- ERROR -->
            <div class="joberror">
                <h2>Please ensure fields marked with <span class="star">!</span> are entered correctly</h2>
            </div>
            <!-- END ERROR -->
    <?php
        }
    ?>
            
            <!-- Begin form area -->
             <form action="application_equal_ops.php?appID=<?php print $_GET['appID'] ?>#anchor" method="post" enctype="x-www-form-encoded" class="jobs_form">
             
                <!-- Text area one -->
                <div class="space">
                    <p><?php if ($missing['sicknessRecord']) { ?><span class="star">! Mandatory: </span><?php } ?>All appointments are subject to satisfactory medical examination. Please indicate below as brief outline of your sickness record over the last two years (i.e. days off and reason). Include any medical condition requiring regular treatment. *</p>
    
                    <div class="jobs_txtarea_wrap">
                        <label for="sicknessRecord">Sickness Record</label>
                        <textarea id="sicknessRecord" name="sicknessRecord" class="jobs_txtarea" rows="4"><?php print $details->sicknessRecord; ?></textarea>
                        <div class="clear"></div>
                    </div>
                </div>
                <!-- END Text area one -->
                
                <div class="space"></div>
                
                <!-- Text area two -->
                <div class="space">
                <p class="jobs_subheader">Disclosure of Relationships</p>
                    <p><span class="b">1. Members or Senior Officers</span> - To the best of your knowledge, are you related to any elected Member (Councillor) or senior officer of the Council? If so, please give details below. Failure to disclose this information may result in disqualification or subsequent dismissal.</p>
    
                    <div class="jobs_txtarea_wrap">
                    <label for="DiscloseRelationships">Disclose Relationships</label>
                        <textarea id="DiscloseRelationships" name="relatedToCouncil" class="jobs_txtarea" rows="4"><?php print $details->relatedToCouncil; ?></textarea>
                        <div class="clear"></div>
                    </div>
                    <p class="slim">Canvassing by candidates, or anyone on his behalf, of any member of the Council, a Comittee or Sub Comittee in any way, shall disqualify the candidate.</p>
                </div>
                <!-- END Text area two -->
                
                <!-- Text area three -->
                <div class="space">
                    <p><span class="b">2. Employment of Relatives</span> - To the best of your knowledge, are you related to any employee in a supervisory capacity? If so, please give details below. Failure to do so may result in disqualification.</p>
    
                    <div class="jobs_txtarea_wrap">
                    <label for="RelatedRelatives">Related Relatives</label>
                        <textarea id="RelatedRelatives" name="relatedToEmployee" class="jobs_txtarea" rows="4"><?php print $details->relatedToEmployee; ?></textarea><div class="clear"></div>
                    </div>
                </div>
                <!-- END Text area three -->
                
                <div class="space"></div>
                
                <!-- Text area four -->
                <div class="space">
                    <p><span class="b">3. Additional Information</span> - Please give details of any date when you will not be available for interview.</p>
    
                    <div class="jobs_txtarea_wrap">
                    <label for="AdditionalInformation">Additional Information</label>
                        <textarea id="AdditionalInformation" name="unavailableDates" class="jobs_txtarea" rows="2"><?php print $details->unavailableDates; ?></textarea><div class="clear"></div>
                    </div>
                </div>
                <!-- END Text area four -->
                
                <div class="space"></div>
                
                <!-- ethnic origin -->
                <p class="jobs_subheader">Ethnic Origin</p>
                <p>Please indicate your ethnic origin by ticking the appropriate check box.</p>
                
                <!-- asian -->
                <div class="check_column_wrap">
                    <p class="slim"><span class="b">Asian</span></p>
                    <label for="AsianBritish"><input id="AsianBritish" type="radio" name="ethnicOrigin" value="Asian - British" <?php if ($details->ethnicOrigin == "Asian - British") print "checked=\"checked\"";?> /> Asian - British</label>
                    <label for="Bangladeshi"><input id="Bangladeshi" type="radio" name="ethnicOrigin" value="Bangladeshi" <?php if ($details->ethnicOrigin == "Bangladeshi") print "checked=\"checked\"";?> /> Bangladeshi</label>
                    <label for="Chinese"><input id="Chinese" type="radio" name="ethnicOrigin" value="Chinese" <?php if ($details->ethnicOrigin == "Chinese") print "checked=\"checked\"";?> /> Chinese</label>
                    <label for="Indian"><input id="Indian" type="radio" name="ethnicOrigin" value="Indian" <?php if ($details->ethnicOrigin == "Indian") print "checked=\"checked\"";?> /> Indian</label>
                    <label for="Pakistani"><input id="Pakistani" type="radio" name="ethnicOrigin" value="Pakistani" <?php if ($details->ethnicOrigin == "Pakistani") print "checked=\"checked\"";?> /> Pakistani</label>
                    <label for="AsianOther"><input id="AsianOther" type="radio" name="ethnicOrigin" value="Asian - Other" <?php if ($details->ethnicOrigin == "Asian - Other") print "checked=\"checked\"";?> /> Asian - Other</label><div class="clear"></div>
                </div>
                <!-- END asian -->
                
                
                <!-- white -->
                <div class="check_column_wrap">
                    <p class="slim"><span class="b">White</span></p>
                    <label for="WhiteBritish"><input id="WhiteBritish" type="radio" name="ethnicOrigin" value="White - British" <?php if ($details->ethnicOrigin == "White - British") print "checked=\"checked\"";?> /> White - British</label>
                    <label for="WhiteOtherEuropean"><input id="WhiteOtherEuropean" type="radio" name="ethnicOrigin" value="White - Other European" <?php if ($details->ethnicOrigin == "White - Other European") print "checked=\"checked\"";?> /> White - Other European</label>
                    <label for="WhiteOther"><input id="WhiteOther" type="radio" name="ethnicOrigin" value="White - Other" <?php if ($details->ethnicOrigin == "White - Other") print "checked=\"checked\"";?> /> White - Other</label>
                    <label for="WhiteIrish"><input id="WhiteIrish" type="radio" name="ethnicOrigin" value="White - Irish" <?php if ($details->ethnicOrigin == "White - Irish") print "checked=\"checked\"";?> /> White - Irish</label><div class="clear"></div>
                </div>
                <!-- END white -->
                
                
                <!-- black -->
                <div class="check_column_wrap">
                    <p class="slim"><span class="b">Black</span></p>
                    <label for="BlackBritish"><input id="BlackBritish" type="radio" name="ethnicOrigin" value="Black - British" <?php if ($details->ethnicOrigin == "Black - British") print "checked=\"checked\"";?> /> Black - British</label>
                    <label for="BlackAfrican"><input id="BlackAfrican" type="radio" name="ethnicOrigin" value="Black - African" <?php if ($details->ethnicOrigin == "Black - African") print "checked=\"checked\"";?> /> Black - African</label>
                    <label for="BlackCaribbean"><input id="BlackCaribbean" type="radio" name="ethnicOrigin" value="Black - Caribbean" <?php if ($details->ethnicOrigin == "Black - Caribbean") print "checked=\"checked\"";?> /> Black - Caribbean</label>
                    <label for="BlackOther"><input id="BlackOther" type="radio" name="ethnicOrigin" value="Black - Other" <?php if ($details->ethnicOrigin == "Black - Other") print "checked=\"checked\"";?> /> Black - Other</label><div class="clear"></div>
                </div>
                <!-- END black -->
    
    
                <!-- others -->
                <div class="check_column_wrap">
                    <p class="slim"><span class="b">Others</span></p>
                    <label for="Anyothergroup"><input id="Anyothergroup" type="radio" name="ethnicOrigin" value="Any other ethnic group" <?php if ($details->ethnicOrigin == "Any other ethnic group") print "checked=\"checked\"";?> /> Any other ethnic group</label><div class="clear"></div>
                </div>
                <!-- END others -->
    
                
                <!-- mixed -->
                <div class="check_column_wrap">
                    <p class="slim"><span class="b">Mixed</span></p>
                    <label for="WhiteBlackCaribbean"><input id="WhiteBlackCaribbean" type="radio" name="ethnicOrigin" value="White - Black Caribbean" <?php if ($details->ethnicOrigin == "White - Black Caribbean") print "checked=\"checked\"";?> /> White - Black Caribbean</label>
                    <label for="WhiteBlackAfrican"><input id="WhiteBlackAfrican" type="radio" name="ethnicOrigin" value="White - Black African" <?php if ($details->ethnicOrigin == "White - Black African") print "checked=\"checked\"";?> /> White - Black African</label>
                    <label for=""><input id="" type="radio" name="ethnicOrigin" value="White - Asian" <?php if ($details->ethnicOrigin == "White - Asian") print "checked=\"checked\"";?> /> White - Asian</label>
                    <label for="Othermixed"><input id="Othermixed" type="radio" name="ethnicOrigin" value="Other mixed background" <?php if ($details->ethnicOrigin == "Other mixed background") print "checked=\"checked\"";?> /> Other mixed background</label><div class="clear"></div>
                </div>
                <!-- END mixed -->
                
    
                <!-- do not wish -->
                <div class="check_column_wrap">
                    <label for="donotwish"><input id="donotwish" type="radio" name="ethnicOrigin" value="I do not wish to supply this information" <?php if ($details->ethnicOrigin == "I do not wish to supply this information") print "checked=\"checked\"";?> /> Please check here if you do not wish to supply this information</label><div class="space"></div>
                </div>
                <!-- END do not wish -->
                <!-- END ethnic origin -->
                
                
                <!-- disability one -->
                <div class="space">
                    <p class="jobs_subheader">Disability</p>
                    <p>We will interview all disabled candidates who are able to demonstrate that they meet the minimum selection criteria on the personal specification.</p>
                    <p>The following questions are asked in order that the information on reasonable adjustments that may be necessary for interview or employment purposes can be considered to ensure that applicants with a disability are not disadvantaged.</p>
    
                    <div class="check_column_wrap">
                        <p><span class="b">1.</span> Do you consider yourself to have a disability?</p>
                        <label><input type="radio" name="disabilityYesNo" value="yes" <?php if ($details->disabilityYesNo == "yes") print "checked=\"checked\"";?> /> Yes</label>
                        <label><input type="radio" name="disabilityYesNo" value="no" <?php if ($details->disabilityYesNo == "no") print "checked=\"checked\"";?> /> No</label>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                    <p class="slim">If you have answered 'Yes' to this question, please give details below and answer the questions 2-4 which follow.</p>
                </div>
                <!-- END disability one -->
                
                <div class="form_line"></div>
                
                <!-- disability two -->
                <div class="space">
                
                    <p><span class="b">2.</span><?php if ($missing['disabilityRelevance']) { ?><span class="star">! Mandatory: </span><?php } ?> Is there anything about your disability which is relevant to your application?  If so, please explain:</p>
        
                    <div class="jobs_txtarea_wrap">
                    <label for="Aboutyourdisability">About your disability</label>
                        <textarea id="Aboutyourdisability" name="disabilityRelevance" class="jobs_txtarea" rows="2"><?php print $details->disabilityRelevance; ?></textarea><div class="clear"></div>
                    </div>
                </div>
                <!-- END disability two -->
                    
                <div class="form_line"></div>
                    
                <!-- disability three -->	
                <div class="space">
                    <p><span class="b">3.</span><?php if ($missing['provisionOfAids']) { ?><span class="star">! Mandatory: </span><?php } ?> Would the provision of any aids or modifications assist you in carrying out the dutied of this post?  If so, please give details:</p>
                    <div class="jobs_txtarea_wrap">
                    <label for="Aidsormodifications">Aids or modifications</label>
                        <textarea id="Aidsormodifications" name="provisionOfAids" class="jobs_txtarea" rows="2"><?php print $details->provisionOfAids; ?></textarea><div class="clear"></div>
                    </div>
                </div>
                <!-- END disability three -->
                    
                <div class="form_line"></div>
                
                <!-- disability four -->	
                <div class="space">
                    <p><span class="b">4.</span><?php if ($missing['interviewArrangements']) { ?><span class="star">! Mandatory: </span><?php } ?> Is there anything we need to know about your disability in order to offer you a fair selection interview? For example, do you need a signer or require an accessible interview room?</p>
                    <div class="jobs_txtarea_wrap">
                    <label for="Fairselection">Fair selection</label>
                        <textarea id="Fairselection" name="interviewArrangements" class="jobs_txtarea" rows="2"><?php print $details->interviewArrangements; ?></textarea><div class="clear"></div>
                    </div>
                </div>
                <!-- END disability four -->
                            
                <div class="clear"></div>
                <div class="form_line"></div>
                <div class="clear"></div>		
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