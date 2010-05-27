<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	$breadcrumb = 'planxSearch';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Planning Quick Search</title>

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
				
	<p class="first">You can search Planning Applications submitted to <?php print METADATA_GENERIC_COUNCIL_NAME;?> using the quick search below.</p>
	<p class="first">Need more options? Try the <a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_advsearch.php">Advanced Search</a></p>
				
	<form action="http://<?php print $DOMAIN; ?>/site/scripts/planx_details.php" method="post" class="basic_form">
		<p>
			<label for="appNumber">Application N&ordm;: </label> 
			<input id="appNumber" class="field" type="text" name="appNumber" value="" />
			<input type="submit" value="Go" name="submit" class="button" />
		</p>		
	</form>
				
	<form action="http://<?php print $DOMAIN; ?>/site/scripts/planx_results.php" method="get" class="basic_form">
		<p>
			<label for="locSearch">Location Search: </label>
			<input id="locSearch" class="field" type="text" name="location" />
			<input type="submit" value="Go" name="locationSearch" class="button" />
		</p>
		<p>
			<label for="appName">Applicant Name: </label>						
			<input id="appName" class="field" type="text" name="applicant" />
			<input type="submit" value="Go" name="applicantSearch" class="button" />
		</p>
	</form>
				
	<h2>Weekly List of Received Application</h2>
	<p>View our online <a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_weekly_list.php">weekly list</a> to find out what applications have been submitted this week to <?php print METADATA_GENERIC_COUNCIL_NAME;?>.</p>	
	
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/closing.php"); ?>