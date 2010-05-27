<?php
	session_start();
	include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");
	include_once("marketing/JaduUsers.php");
	include_once("hrlive/JaduHRLiveCategories.php");
	include_once("hrlive/JaduHRLiveJobsToCategories.php");
	include_once("hrlive/JaduHRLiveJobCompanies.php");
	include_once("hrlive/JaduHRLiveJobsToRoles.php");
	include_once("hrlive/JaduHRLiveJobs.php");
	include_once("hrlive/JaduHRLiveEmailAlerts.php");
	include_once("hrlive/JaduHRLiveJobTypes.php");
	include_once("hrlive/JaduHRLiveJobRoles.php");
	include_once("marketing/JaduAdverts.php");
	
	if (sizeof(getAllCurrentlyLiveJobs()) < 1 && !isset($_GET['editAlert']) && 
	    !isset($_GET['submit']) && !isset($_GET['stopAlerts'])) {
	   header("Location: recruit_list.php");
	   exit();
	}

	if (isset($_SESSION['userID'])) {
		$user = getUser($_SESSION['userID']);
	}

	if (isset($_GET['submit']) && isset($_SESSION['userID'])) {

		$alert->userID 		= $_SESSION['userID'];
		$alert->query 		= $_GET['jobkeyword'];
		$alert->categoryIDs = serialize($_GET['categoryIDs']);
		$alert->roleIDs 	= serialize($_GET['roleIDs']);
		$alert->typeIDs		= serialize($_GET['type']);
		$alert->posted 		= $_GET['posted'];
		$alert->company		= $_GET['company'];
		$alert->salaries	= serialize($_GET['salary']);

		$exisiting_alert = getEmailAlert($_SESSION['userID']);

		if ($exisiting_alert == null) {
			// add a new alert
			addEmailAlert($alert);
		}
		else {
			// update the existng alert
			$alert->id = $exisiting_alert->id;
			updateEmailAlert($alert);
		}
	}

	if (isset($_GET['stopAlerts'])) {
		$alert = getEmailAlert($_SESSION['userID']);
		deleteEmailAlert($alert->id);
		unset($alert);
	}

	$jobsToCategories = getAllJobToCategories();

	$catsToShow = array();
	$catIDsUsed = array();

	// if we're editing an email alert then show all categories
	//if (isset($_GET['editAlert'])) {
		$cats = getAllBespokeCategories("title");
		foreach ($cats as $cat) {
			if ($cat->level == 2) {
				$catsToShow[] = $cat;
			}
		}
	//}
    //else {
    //   foreach ($jobsToCategories as $jc) {
    //        $cat = getBespokeCategory($jc->categoryID);
    //        if ($cat->parentID == 1 && !in_array($cat->id, $catIDsUsed)) {
    //            $catsToShow[] = $cat;
    //            $catIDsUsed[] = $cat->id;
    //        }
    //    }
    //}

	$jobsToRoles = getAllJobsToRoles();

	$rolesToShow = array();
	$roleIDsUsed = array();

    //if (isset($_GET['editAlert'])) {
        $rolesToShow = getAllJobRoles();
    //}
    //else {
    //   foreach ($jobsToRoles as $jr) {
    //        $role = getJobRole($jr->roleID);
    //        if (!in_array($role->id, $roleIDsUsed)) {
    //            $rolesToShow[] = $role;
    //            $roleIDsUsed[] = $role->id;
    //        }
    //    }
	//}

	$types = getAllJobTypes();

	$companies = getAllJobCompanies();
	
	$breadcrumb = "recruitJobs";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
	<title>Jobs at <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="jobs, recruitment, application, job, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="Jobs currently available at <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.title" lang="en" content="Jobs at <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />
	<meta name="DC.description" lang="en" content="Jobs currently available at <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Job vacancies;Employment, jobs and careers" />
	<meta name="DC.subject" lang="en" content="Job vacancies;Jobs and careers;Recruitment" />

</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (!isset($_GET['editAlert'])) {
		if (isset($_SESSION['userID']) && getEmailAlert($_SESSION['userID']) != null && isset($_GET['submit'])) {
?>

		<p class="first">You are now <strong>signed up</strong> for email alerts.</p>
<?php
	}
?>
		<p class="first">You can use this page to search in a number of ways - by keyword, working pattern, category or a combination of all or some of these criteria.</p>

		<p>You can also <a href="http://<?php print $DOMAIN; ?>/site/scripts/recruit_list.php">browse all jobs by category</a>.</p>

		<form action="http://<?php print $DOMAIN; ?>/site/scripts/recruit_results.php" method="get" class="basic_form">

<?php
		}
		else{
?>

		<!-- EMAIL  ALERTS -->
		<h2>Your Job Alerts</h2>

		<p class="first">You will be alerted when any jobs become available based on your chosen criteria. The email address used for alert is <strong><?php print $user->email; ?></strong>. You can change this on the <a title="change details" href="http://<?php print $DOMAIN; ?>/site/scripts/change_details.php">change details</a> page.</p>

		<p>Choose criteria from the options below and when a job is posted that matches the criteria we will send you an email.</p>

		<form action="http://<?php print $DOMAIN; ?>/site/scripts/recruit_jobs.php" class="basic_form" method="get">

<?php
		}
?>
			
		<fieldset>
			<legend>Start searching for jobs</legend>
			
			<!-- KEYWORD SEARCH -->
			<p>Enter keywords you would expect to appear in the job title or job description.</p>
			
				<p>
					<label for="jobkeyword">Keyword / Ref number: </label> <input id="jobkeyword" class="field" type="text" name="jobkeyword" value="<?php print $_GET['jobkeyword'];?>" />
					<span class="clear"></span>
				</p>
			<!-- CATEGORIES SEARCH -->
				<p>
					<label for="categoryIDs">Categories:</label>
					<select multiple="multiple" size="6" id="categoryIDs" name="categoryIDs[]" class="field">
						<option value="" <?php if (isset($_GET['categoryIDs']) && empty($_GET['categoryIDs'][0])) print 'selected'; ?>>&lt;ALL&gt;</option>
<?php
					foreach ($catsToShow as $cat) {
						$child_ids = array();
						if (!empty($cat->childrenIDs)) {
							$child_ids = explode(",",$cat->childrenIDs);
						}
?>
						<option value="<?php print $cat->id; ?>" <?php if (isset($_GET['categoryIDs']) && in_array($cat->id, $_GET['categoryIDs'])) print 'selected'; ?>><?php print $cat->title; ?></option>
<?php
						foreach ($child_ids as $id) {
							$sub_cat = getBespokeCategory($id);
?>
							<option value="<?php print $id; ?>" <?php if (isset($_GET['categoryIDs']) && in_array($id, $_GET['categoryIDs'])) print 'selected'; ?>>&nbsp;&nbsp; - <?php print $sub_cat->title; ?></option>
<?php
						}
					}
?>
					</select>
				</p>
				<p>
				<!-- ROLES SEARCH -->
					<label for="roleIDs">Roles:</label>
					<select multiple="multiple" size="6" id="roleIDs" name="roleIDs[]" class="jobsmultiple" >
						<option value="" <?php if (isset($_GET['roleIDs']) && empty($_GET['roleIDs'][0])) print 'selected'; ?>>&lt;ALL&gt;</option>
<?php
						foreach ($rolesToShow as $role) {
?>
						<option value="<?php print $role->id; ?>" <?php if (isset($_GET['roleIDs']) && in_array($role->id, $_GET['roleIDs'])) print 'selected'; ?>><?php print $role->title; ?></option>
<?php
						}
?>
					</select>
				</p>
				<p>For multiple selections, hold down the Ctrl key (command key on Mac) and click your selections.</p>
			</fieldset>
			
			<fieldset>
			<!-- WORKING PATTERN -->
			<legend>Working Pattern</legend>
			<p  class="form_text">
<?php
			$count = 0;
			foreach ($types as $type) {
?>
				<label class="regPrefs" for="type_<?php print $count; ?>"><input name="type[]" id="type_<?php print $count++; ?>" value="<?php print $type->id; ?>" type="checkbox" <?php if (isset($_GET['type']) && in_array($type->id, $_GET['type'])) print 'checked'; ?> /><?php print $type->title; ?></label>
<?php
			}
?>
				<span class="clear"></span>
			</p>

			<!-- SALARY SEARCH -->
				<p>
					<label for="salary">Salary Range: </label>
					<select multiple="multiple" size="6" id="salary" name="salary[]">
						<option value="" <?php if (isset($_GET['salary']) && empty($_GET['salary'][0])) print 'selected'; ?>>&lt;ALL&gt;</option>
						<option value="1-10000" <?php if (isset($_GET['salary']) && in_array("0-10000", $_GET['salary'])) print 'selected'; ?>>Under 10,000</option>
						<option value="10001-15000" <?php if (isset($_GET['salary']) && in_array("10001-15000", $_GET['salary'])) print 'selected'; ?>>10,000 - 15,000</option>
						<option value="15001-20000" <?php if (isset($_GET['salary']) && in_array("15001-20000", $_GET['salary'])) print 'selected'; ?>>15,001 - 20,000</option>
						<option value="20001-25000" <?php if (isset($_GET['salary']) && in_array("20001-25000", $_GET['salary'])) print 'selected'; ?>>20,001 - 25,000</option>
						<option value="25001-30000" <?php if (isset($_GET['salary']) && in_array("25001-30000", $_GET['salary'])) print 'selected'; ?>>25,001 - 30,000</option>
						<option value="31000-35000" <?php if (isset($_GET['salary']) && in_array("31000-35000", $_GET['salary'])) print 'selected'; ?>>30,001 - 35,000</option>
						<option value="35000-200000" <?php if (isset($_GET['salary']) && in_array("35000-200000", $_GET['salary'])) print 'selected'; ?>>Over 35,000</option>
					</select>
				</p>
				
				<!-- TIME OF POST -->
				<p>
					<label for="posted">Time posted:</label>
					<select name="posted" id="posted">
						<option value="">Show all jobs</option>
						<option value="1" <?php if ($_GET['posted'] == '1') print 'selected'; ?>>Show all jobs from the last 24 hours</option>
						<option value="7"  <?php if ($_GET['posted'] == '7') print 'selected'; ?>>Show all jobs from the past 7 days</option>
						<option value="14" <?php if ($_GET['posted'] == '14') print 'selected'; ?>>Show all jobs from the past 14 days</option>
					</select>
				</p>
				
				<!-- WHICH COMPANY -->
				<p>
					<label for="company">Company:</label>
					<select name="company" id="company">
						<option value="">Show all jobs</option>
					<?php
						foreach ($companies as $company) {
					?>
						<option value="<?php print $company->id; ?>" <?php if ($_GET['company'] == $company->id) print 'selected'; ?>><?php print $company->title; ?></option>
					<?php
						}
					?>
					</select>
				</p>

			<!-- AND THE BUTTON -->
			<p class="center">
			
			<input type="submit" class="button" value="Search" name="submit" />
			
			</p>
			</fieldset>
		</form>

		
<?php 
	if (isset($_SESSION['userID'])) { 
?>	
			<h3>Job email alerts</h3>
	
<?php
		if (getEmailAlert($_SESSION['userID']) != null) {
			$alert = getEmailAlert($_SESSION['userID']);
?>
			<p><em>You are signed up for email alerts</em>.</p>
			<ul class="list">
			
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/recruit_jobs.php?<?php print $alert->getQueryString(); ?>">Edit email alerts</a></li>
			
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/recruit_jobs.php?stopAlerts=true">Stop email alerts</a></li>
			</ul>
	
<?php 
		}
?>
			
			<p>Receive <strong>email alerts</strong> based upon search queries.  <strong>We mail you</strong> when your job comes online.</p> 
			
			<p>Find out how to <a href="http://<?php print $DOMAIN;?>/site/scripts/recruit_email_alerts.php" >create email alerts</a>.</p>
<?php
	} 
?>


		<p><?php print METADATA_GENERIC_COUNCIL_NAME;?> is an equal opportunities employer.</p>
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>