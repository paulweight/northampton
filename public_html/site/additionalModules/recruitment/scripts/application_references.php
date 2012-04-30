<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("recruitment/JaduRecruitmentJobs.php");
	include_once("recruitment/JaduRecruitmentApplications.php");
	include_once("recruitment/JaduRecruitmentApplicationReferences.php");
	include_once("marketing/JaduAdverts.php");
	
	define("REFERENCE_MIN", 2);
	define("REFERENCE_MAX", 4);
	
   	if (Jadu_Service_User::getInstance()->isSessionLoggedIn()){
   		$user = Jadu_Service_User::getInstance()->getSessionUser();
   	}
	
    if (isset($user) && isset($_GET['appID'])){
		$appID = intval($_GET['appID']);

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
			header('Location: '.buildJobApplicationURL("details", $app->id));
			exit;
		}

		if (isset($_GET['refNumber']) && $_GET['refNumber'] < REFERENCE_MAX) {
			$refToEdit = intval($_GET['refNumber']);
		}
		else {
			$refToEdit = 0;
		}

		if (isset($_GET['action']) && $_GET['action'] == 'remove') {
			deleteReferenceForApp($appID, $refToEdit);

			header('Location: '.buildJobApplicationURL("references", $appID));
			exit;
		}
			
		if (isset($_POST['saveProceed']) || isset($_POST['saveExit']) || isset($_POST['update']) || isset($_POST['save'])) {	
			$proceed = false;

			// get all the new details
			$ref->address 			= $_POST['address'];
			$ref->applicationID 		= $_GET['appID'];
			$ref->refNumber 		= $_POST['refNumber'];
			$ref->name 				= $_POST['name'];
			$ref->position 			= $_POST['position'];;
			$ref->relationship 		= $_POST['relationship'];
			$ref->telephone 			= $_POST['telephone'];
			$ref->contactPermission	= $_POST['contactPermission'];

			// see if there's anything missing
			$missing = getMissingReferenceDetails($ref);

			if (sizeof($missing) > 0) {
				$refToEdit = intval($_GET['refNumber']);
				$references[$refToEdit] = $ref;
			}
			else {
				// add or update the reference
				if (isset($_POST['add']) && sizeof($missing) < 1) {
					addReference($ref);

					if (!isset($_POST['saveProceed'])) {
						header('Location: '.buildJobApplicationURL("references", $appID));
						exit;
					}
				}
				elseif (isset($_POST['update']) && sizeof($missing) < 1) {
					updateReference($ref);
				}
				
				if ($ref->refNumber < REFERENCE_MAX - 1) {
					$refToEdit = $ref->refNumber + 1;
				}
				else {
					$refToEdit = REFERENCE_MAX - 1;
				}
			}
		}

		if (isset($_POST['saveProceed']) && sizeof($missing) < 1) {
			header('Location: '. buildJobApplicationURL("details", $appID));
			exit;
    		}
		else if (isset($_POST['saveExit'])) {
			header('Location: '.buildJobApplicationURL("details", $appID));
			exit;
		}

		$references = getReferencesForApp($app->id);
		
		if ($references[$refToEdit]->id != -1 && !empty($references[$refToEdit]->id)) {
			$action = "update";
		}
		else {
			$action = "add";
		}
	}
	else {
		header ("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}
	
	$breadcrumb = 'application_references';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html<?php if (TEXT_DIRECTION == 'rtl') print ' dir="rtl"'; ?> xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
	<title>Jobs at <?php print METADATA_GENERIC_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	<link rel="stylesheet" type="text/css" href="<?php print getStaticContentRootURL(); ?>/site/styles/generic/jobApps.css" media="screen" />
	
	<meta name="Keywords" content="jobs, recruitment, application, job, <?php print METADATA_GENERIC_KEYWORDS;?>" />
	<meta name="Description" content="Jobs currently available at <?php print METADATA_GENERIC_NAME;?>" />

	<meta name="DC.title" lang="en" content="Jobs at <?php print METADATA_GENERIC_NAME;?>" />
	<meta name="DC.description" lang="en" content="Jobs currently available at <?php print METADATA_GENERIC_NAME;?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->				

	<?php include('../includes/application_sections.php'); ?>
						
	<p>Please give the names and address of two referees.  One of these must be a direct line manager/supervisor from your present employment, or, if you are unemployed or school/university leaver, a past employer or teacher will suffice.</p>
	<p>References are taken up for shortlisted candidates only.  You may select any that you do not want taken up prior to interview below by checking the relevant box.</p>
		
<?php
        if (sizeof($missing) > 0) {
?>
            
	<h2 class="warning">Please ensure fields marked with <span class="star">!</span> are entered correctly</h2>
            
<?php
        }
?>
		<form action="<?php print getSiteRootURL() .buildJobApplicationURL('references', intval($_GET['appID'])); ?>" method="post" enctype="multipart/form-data" enctype="x-www-form-encoded" class="basic_form">
<?php
	if (sizeof($references) > 0) {
?>
		<table>
			<tr>
				<th>Name</th>
				<th>Job title</th>
				<th>Contact details</th>
				<th>Actions</th>
			</tr>
<?php
		foreach ($references as $refe) {
?>
			<tr>
<?php
			$refTitle = ($refe->refNumber + 1);
			if (substr($refe->refNumber + 1, -1) == '1' && $refe->refNumber + 1 != '11') {
				$refTitle .= 'st';
			}
			else if (substr($refe->refNumber + 1, -1) == '2' && $refe->refNumber + 1 != '12') {
				$refTitle .= 'nd';
			}
			else if (substr($refe->refNumber + 1, -1) == '3' && $refe->refNumber + 1 != '13') {
				$refTitle .= 'rd';
			}
			else {
				$refTitle .= 'th';
			}
?>
			<td><?php print $refe->name; ?></td>
			<td><?php print $refe->position; ?></td>
			<td><?php print nl2br($refe->address); ?><br />
				Tel:<?php print $refe->telephone; ?></td>
			<td>
<?php
			if ($app->submitted != 1) {
?>	
			<a href="<?php print getSiteRootURL() . buildJobApplicationURL('references', $_GET['appID'], $refe->refNumber);?>">Edit <?php print $refTitle; ?> referee</a> | <a href="<?php print getSiteRootURL() . buildJobApplicationURL('references', $_GET['appID'], $refe->refNumber, 'remove');?>">Delete</a>
<?php
			}
?>
				</td>
			</tr>
<?php
		}
?>
	</table>
<?php
	}		
?>					
			<input type="hidden" name="refNumber" value="<?php print $refToEdit; ?>" />
			<input type="hidden" name="<?php print $action; ?>" value="true" />

		<h3><?php
			print ($refToEdit + 1);

			if (substr($refToEdit + 1, -1) == '1' && $refToEdit + 1 != '11') {
				print 'st';
			}
			else if (substr($refToEdit + 1, -1) == '2' && $refToEdit + 1 != '12') {
				print 'nd';
			}
			else if (substr($refToEdit + 1, -1) == '3' && $refToEdit + 1 != '13') {
				print 'rd';
			}
			else {
				print 'th';
			}
		?> Referee</h3>
		
		<p>
			<label for="name"><?php if ($missing['name']) { ?><span class="star">! <?php } ?>Name<?php if ($missing['name']) { ?></span><?php } ?> * </label>
			<input id="name" type="text" name="name" class="field" value="<?php print $references[$refToEdit]->name;?>" />
		</p>
				
		<p>
			<label for="position"><?php if ($missing['position']) { ?><span class="star">! <?php } ?>Position<?php if ($missing['position']) { ?></span><?php } ?> * </label>
			<input id="position" type="text" name="position" class="field" value="<?php print $references[$refToEdit]->position;?>" />
		</p>
				
		<p>
			<label for="address"><?php if ($missing['address']) { ?><span class="star">! <?php } ?>Address<?php if ($missing['address']) { ?></span><?php } ?> * </label>
			<textarea name="address" id="address" class="field" rows="3"><?php print $references[$refToEdit]->address;?></textarea>
		</p>
		
		<p>
			<label for="telephone"><?php if ($missing['telephone']) { ?><span class="star">! <?php } ?>Telephone<?php if ($missing['telephone']) { ?></span><?php } ?> * </label>
			<input type="text" id="telephone" name="telephone" class="field" value="<?php print $references[$refToEdit]->telephone;?>" />
		</p>
				
		<p>
			<label for="relationship"><?php if ($missing['relationship']) { ?><span class="star">! <?php } ?>Capacity in which known to you<?php if ($missing['relationship']) { ?></span><?php } ?> * </label>
			<input id="relationship" type="text" name="relationship"  class="field" value="<?php print $references[$refToEdit]->relationship;?>" />
			<span class="clear"></span>
		</p>
						
		<p class="date_birth">
			<label><?php if ($missing['contactPermission']) { ?><span class="star">! <?php } ?>May we contact this referee prior to interview?<?php if ($missing['contactPermission']) { ?></span><?php } ?> *</label>
			<label for="contactPermissionYes"><input id="contactPermissionYes" type="radio" class="dob" name="contactPermission" value="yes" <?php if ($references[$refToEdit]->contactPermission == "yes") print "checked=\"checked\"";?> /> Yes</label>
			<label for="contactPermissionNo"><input id="contactPermissionNo" type="radio" class="dob" name="contactPermission" value="no" <?php if ($references[$refToEdit]->contactPermission == "no") print "checked=\"checked\"";?> /> No</label>
			<span class="clear"></span>
		</p>

<?php	
	if ($app->submitted != 1) {
?>
	<!-- Proceed button -->
	<p class="center">
		<input class="button" type="submit" name="save" value="Save" />
<?php
		if(sizeof($references) >= REFERENCE_MIN) {
?>
		<input class="button" type="submit" name="saveProceed" value="Save &amp; Proceed" />
<?php
		}
?>
	</p>
<?php
		if (sizeof($references) < REFERENCE_MAX) {
?>
		<p class="center"><a href="<?php print getSiteRootURL() . buildJobApplicationURL('references', $_GET['appID'], sizeof($references)); ?>">Add a Referee.</a></p>
<?php
		}
	}
?>
		<!-- save for later -->
		<?php include("../includes/savelater.php"); ?>
		<!-- END save for later -->
	</form>
					
	<p class="note"><?php print METADATA_GENERIC_NAME;?> is an equal opportunities employer.</p>
				
<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>
