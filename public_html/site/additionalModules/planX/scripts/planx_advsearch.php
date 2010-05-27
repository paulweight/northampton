<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	include_once("planXLive/JaduPlanXDecisionTypes.php");
	
	$decisionTypes = getAllDecisionTypes();
	
	$breadcrumb = 'planxAdvSearch';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Planning Advanced Search</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of documents and pages organised within the following categories, Environment, Planning" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> online information | Environment | Planning" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of documents and pages organised within the following categories, Environment, Planning" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

						
<?php
	if (isset($_GET['noResults'])) {
?>
	<h2 class="warning">No results were found for your search</h2>
<?php
	}
?>
						
	<p class="first">You can use any or all of the fields below to search planning applications submitted to <?php print METADATA_GENERIC_COUNCIL_NAME;?>.</p>
						
	<form action="http://<?php print $DOMAIN; ?>/site/scripts/planx_results.php" method="get" class="basic_form">
		<p>
			<label for="locSearch">Location Search: </label>
			<input id="locSearch" class="field" type="text" name="location" value="<?php print $_GET['location']; ?>" />
		</p>
		<p>
			<label for="appName">Applicant Name: </label>
			<input id="appName" class="field" type="text" name="applicant" value="<?php print $_GET['applicant']; ?>"  />
		</p>
		<p>
			<label for="devDescrip">Development Description: </label>
			<input id="devDescrip" class="field" type="text" name="developmentDescription" value="<?php print $_GET['developmentDescription']; ?>" />
		</p>
		<p>Search for a certain decision type in a given year.</p>
		<p>
			<label for="decision">Decision:</label>
			<select id="decision" name="decisionType">
				<option value=""  selected="selected">Decision Type</option>
<?php
				foreach ($decisionTypes as $type) {
?>
				<option value="<?php print $type->reference; ?>" <?php if ($_GET['decisionType'] == $type->reference) print 'selected="selected"'; ?>><?php print $type->value; ?></option>
<?php
				}
?>
			</select>
		</p>
		<p>
			<label for="year">Year:</label>
			<select id="year" name="decisionDate" >
				<option value="" selected="selected">Year</option>
<?php
				for ($i = 1989; $i <= date('Y'); $i++) {
?>
				<option value="<?php print $i; ?>" <?php if ($_GET['decisionDate'] == $i) print 'selected="selected"'; ?>><?php print $i; ?></option>
<?php
				}
?>
			</select>
		</p>

		<p>Or search for a decision between two dates.</p>

		<p>
			<label for="fromDay">Decision Date Between:</label>
			<select id="fromDay" name="fromDay" class="dob">
				<option value="" selected="selected">Day</option>
<?php
				for ($i = 1; $i <= 31; $i++) {
?>
				<option value="<?php print $i; ?>" <?php if ($_GET['fromDay'] == $i) print 'selected="selected"'; ?>><?php print $i; ?></option>
<?php
				}
?>
			</select>
			
			<select name="fromMonth" class="dob">
				<option value="" selected="selected">Month</option>
<?php
				for ($i = 1; $i <= 12; $i++) {
?>
				<option value="<?php print $i; ?>" <?php if ($_GET['fromMonth'] == $i) print 'selected="selected"'; ?>><?php print date("M", mktime(0,0,0,$i,0,0)); ?></option>
<?php
				}
?>
			</select> 
			
			<select name="fromYear" class="dob">
				<option value="" selected="selected">Year</option>
<?php
				for ($i = 1989; $i <= date('Y'); $i++) {
?>
				<option value="<?php print $i; ?>" <?php if ($_GET['fromYear'] == $i) print 'selected="selected"'; ?>><?php print $i; ?></option>
<?php
				}
?>
			</select>
		</p>
		<p>
			<label>and: </label>
			<select name="toDay" class="dob">
				<option value=""  selected="selected">Day</option>
<?php
			for ($i = 1; $i <= 31; $i++) {
?>
				<option value="<?php print $i; ?>" <?php if ($_GET['toDay'] == $i) print 'selected="selected"'; ?>><?php print $i; ?></option>
<?php
			}
?>
			</select>
			<select class="dob" name="toMonth">
				<option value=""  selected="selected">Month</option>
<?php
				for ($i = 1; $i <= 12; $i++) {
?>
				<option value="<?php print $i; ?>" <?php if ($_GET['toMonth'] == $i) print 'selected="selected"'; ?>><?php print date("M", mktime(0,0,0,$i,0,0)); ?></option>
<?php
				}
?>
			</select>
			<select class="dob" name="toYear">
				<option value=""  selected="selected">Year</option>
<?php		
				for ($i = 1989; $i <= date('Y'); $i++) {
?>
				<option value="<?php print $i; ?>" <?php if ($_GET['toYear'] == $i) print 'selected="selected"'; ?>><?php print $i; ?></option>
<?php
				}
?>
			</select>
		</p>
		<p class="center">
			<input type="submit" value="Search" name="advancedSearch"  class="button" />
		</p>
	</form>

	<h2>Weekly List of Received Application</h2>
	<p>View our online <a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_weekly_list.php">weekly list</a> to find out what applications have been submitted this week to <?php print METADATA_GENERIC_COUNCIL_NAME;?>.</p>	

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>