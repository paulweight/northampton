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
	
	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
	    if (Jadu_Service_User::getInstance()->isSessionLoggedIn()){
		    $user = Jadu_Service_User::getInstance()->getSessionUser();
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
    			header("Location: ". buildJobApplicationURL('employmentCurrent', $appID));
    			exit;
	    	}
		
    		elseif (isset($_POST['saveExit'])){
    			header("Location: ". buildJobApplicationURL('details', $appID));
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
    			
    			list ($month_started, $year_started) = mb_split("[-]",$est->dateStarted);
				list ($month_finished, $year_finished) = mb_split("[-]",$est->dateFinished);
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
    
    $breadcrumb = 'application_education';
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
    
    <p class="first">Enter education details in reverse chronological order.  Create a school / establishment and then add your qualifications to it.</p>    
    <p class="first">Educational establishments will be listed in chronological order based on the start date.</p>
    
<?php
	if (sizeof($missing) > 0) {
?>
    <!-- ERROR -->
    <h2 class="warning">Please ensure fields marked with <span class="star">!</span> are entered correctly</h2>
<?php
	}
?>

    <!-- Begin form area -->
     <form action="<?php print getSiteRootURL() . buildJobApplicationURL('education', $_GET['appID']); ?>" method="post" enctype="multipart/form-data" enctype="x-www-form-encoded" class="basic_form">
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
        <p>
        	<label for="establishment" ><?php if ($missing['establishment']) { ?><span class="star">!<?php } ?> School/College/University name<?php if ($missing['establishment']) { ?></span><?php } ?> * </label>
        	<input id="establishment" type="text" name="establishment" class="field" value="<?php print encodeHtml($est->establishment); ?>" />
					<span class="clear"></span>
        </p>
        <!-- END establishment name -->
                
        <!-- location -->
        <p>
        	<label for="location"><?php if ($missing['location']) { ?><span class="star">!<?php } ?> Location<?php if ($missing['location']) { ?></span><?php } ?> * </label>
        	<textarea id="location" name="location" class="jobs_form" rows="2"><?php print encodeHtml($est->location); ?></textarea>
        </p>
                
        <!-- from date -->	
        <p class="date_birth">
            <label><?php if ($missing['dateStarted']) { ?><span class="star">!<?php } ?> Start date <?php if ($missing['dateStarted']) { ?></span><?php } ?> *</label>
            <label for="day" class="multipleinput"><em>mm</em> <input type="text" name="month_started" value="<?php print $month_started;?>" size="2" maxlength="2" class="dob" /></label>
            <label for="year" class="multipleinput"><em>yyyy</em> <input type="text" name="year_started" value="<?php print $year_started;?>" size="4" maxlength="4" class="dob" /></label>
			<span class="clear"></span>
        </p>
        <!-- END from date -->
        
        <!-- end date -->	
        <p class="date_birth">
            <label><?php if ($missing['dateFinished']) { ?><span class="star">!<?php } ?> End date <?php if ($missing['dateFinished']) { ?></span><?php } ?></label> 
            <label for="day" class="multipleinput"><em>mm</em> <input type="text" name="month_finished" value="<?php print $month_finished;?>" size="2" maxlength="2" class="dob" /></label>
            <label for="year" class="multipleinput"><em>yyyy</em> <input type="text" name="year_finished" value="<?php print $year_finished;?>" size="4" maxlength="4" class="dob" /></label>
 			<span class="clear"></span>
 			(If you are still attending, simply leave the end date blank)
       </p>
        
<?php
		if ($_GET['action'] == EDIT_EST && $app->submitted != 1) {
?>
               	<p class="center">
	                <input class="button" type="submit" name="updateEstablishment" value="Update" />
               	</p>
<?php
		}
		elseif($app->submitted != 1) {
?>
               	<p class="center">
               		<input class="button" type="submit" name="addEstablishment" value="Add" />
               	</p>
<?php
		}
	}
	else {
 	   $est = getEducationEstablishment($_GET['estID']);
 	   if ($_GET['action'] == EDIT_QUAL) {
?>
        <input type="hidden" name="qualID" value="<?php print (int) $_GET['qualID']; ?>" />
<?php
 	   }
?>
        <input type="hidden" name="estID" value="<?php print (int) $_GET['estID']; ?>" />

        <h3>Add Qualification for <?php print encodeHtml($est->establishment); ?></h3>
        
        <p>Start with your most recent qualification or highest grade earned.</p>
        
        <!-- Qualification name -->
        <p><label for="qualification"><?php if ($missing['qualification']) { ?><span class="star">!<?php } ?> Qualification<?php if ($missing['qualification']) { ?></span><?php } ?> * </label><input id="qualification" type="text" name="qualification" class="field" value="<?php print encodeHtml($qual->qualification); ?>" /></p>
        <!-- END Qualification name -->
        
        
        <!-- subject -->
        <p><label for="qualification"><?php if ($missing['subject']) { ?><span class="star">!<?php } ?> Subject / Course Title<?php if ($missing['subject']) { ?></span><?php } ?>* </label><input id="qualification" type="text" name="subject" class="field" value="<?php print encodeHtml($qual->subject); ?>" /></p>
        <!-- END subject -->
        
        
        <!-- grade -->
        <p><label for="grade"><?php if ($missing['grade']) { ?><span class="star">!<?php } ?> Grade<?php if ($missing['grade']) { ?></span><?php } ?> * </label><input id="grade" type="text" name="grade" class="field" value="<?php print encodeHtml($qual->grade); ?>" /></p>
        <!-- END grade -->
        

<?php
            if ($_GET['action'] == EDIT_QUAL && $app->submitted != 1) {
?>
            <p class="center">   
                <input class="button" type="submit" name="updateQualification" value="Update" />
            </p>
<?php
            }
            elseif($app->submitted != 1) {
?>
            <p class="center">   
               <input class="button" type="submit" name="addQualification" value="Add" />
            </p>
<?php
            }

	}
?>	
    
        <!-- Entries so far -->
        <h3>Entries so far:</h3>
<?php
	if (sizeof($establishments) > 0) {
?>
        <table>
<?php
	    if ($app->submitted != 1) {
?>
    		<tr>
    			<td colspan="4"><a href="<?php print getSiteRootURL() . buildJobApplicationURL('education', $_GET['appID']); ?>">Add a new establishment</a></td>
    		</tr>
<?php	
		}
		foreach ($establishments as $e) {
			$quals = getQualificationsForEstablishment($e->id);
?>
        	<tr>
        		<td colspan="2" style="width: 50%">
        			<strong><?php print $e->establishment; ?></strong>
        		</td>
        		<td colspan="2">
<?php 
			if ($app->submitted != 1) { 
?> 
					<a href="<?php print getSiteRootURL(). buildJobApplicationURL('education', $_GET['appID'], EDIT_EST, $e->id); ?>">Edit</a> | <a href="http://<?php print getSiteRootURL().buildJobApplicationURL('education', $_GET['appID'], DELETE_EST, $e->id); ?>">Remove</a> | <a href="<?php print getSiteRootURL(). buildJobApplicationURL('education', $_GET['appID'],ADD_QUAL, $e->id); ?>">Add Qualification</a>
<?php 
			} 
?>
				</td>
			</tr>
<?php
			if (sizeof($quals) < 1) {
?>
        	<tr>
        		<td colspan="4">You have yet to add your qualifications to this establishment </td>
        	</tr>
<?php
			}
			else {
				foreach ($quals as $q) {
?>
        	<tr>
        		<td><?php print encodeHtml($q->qualification) ;?></td>
        		<td><?php print encodeHtml($q->subject);?></td>
        		<td><?php print encodeHtml($q->grade); ?></td>
<?php 
					if ($app->submitted != 1) { 
?>			
				<td><a href="<?php print getSiteRootURL() . buildJobApplicationURL('education', $_GET['appID'], EDIT_QUAL, $q->educationID, $q->id); ?>">Edit</a> | <a href="<?php print getSiteRootURL() . buildJobApplicationURL('education', $_GET['appID'], DELETE_QUAL, $q->educationID, $q->id); ?>">Remove</a></td>
<?php 
					} 
?>
			</tr>
<?php
				}
			}
		}
?>
	</table>

<?php
	}
	else {
?>
    <p>You have yet to add any establishments </p>
<?php
	}

	if ($app->submitted != 1) {
?>
        <!-- Proceed button -->
        <p class="center">
            <input class="button" type="submit" name="saveProceed" value="Save &amp; Proceed" />
        </p>
        <!-- END Proceed button -->
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