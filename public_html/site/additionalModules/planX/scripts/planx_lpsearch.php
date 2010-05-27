<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	
	$breadcrumb = 'planxLpSearch';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Policy Search</title>

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
   						
    <p class="first">You can search <?php print METADATA_GENERIC_COUNCIL_NAME;?>'s Planning Policies using either a Post Code or a Keyword.  You can also browse through the complete documents. </p>
	
	<form class="basic_form" action="http://<?php print $DOMAIN; ?>/site/scripts/planx_lpsearch_results.php" method="get">
		<p>
			<label for="postcode">Post Code</label>
			<input id="postcode" class="field" type="text" name="postcode" />
			<input type="submit" value="Go" name="submitPostcodeSearch" class="button" />
		</p>
	</form>
	
	<form class="basic_form" action="http://<?php print $DOMAIN; ?>/site/scripts/planx_lpsearch_results.php" method="get">
		<p>
			<label for="keyword">Keyword Search</label>
			<input id="keyword" class="field" type="text" name="keywords"  />
			<input type="submit" value="Go" name="submitKeywordSearch" class="button" />
		</p>
	</form>

<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/closing.php"); ?>