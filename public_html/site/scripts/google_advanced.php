<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");  
	include_once("JaduSearch.php");
	
	include_once("egov/JaduEGovCouncillors.php");
	include_once("egov/JaduEGovWards.php");
	include_once("egov/JaduEGovParties.php");

	$allCouncillors = getAllCouncillors(true, true);
	$allWards = getAllWards();
	$allParties = getAllParties();
	
	$breadcrumb = 'googleAdvanced';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Advanced search | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="search, advanced, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Advanced Search facilities" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Advanced Search" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Advanced Search facilities" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
						
<?php
		//	Print none found error message
		if (isset($_GET["noResults"]) && $noResults) {
?>
		<h2>Your search returned <strong>no results</strong>. Try using the Search below to find what you were looking for.</h2>
<?php 
		}
?>
			
		<!-- Keyword Search -->
		<form action="http://<?php print $DOMAIN;?>/site/scripts/google_results.php" method="get" class="basic_form">
				<!-- check boxes -->
				<p id="search_checkbox">
<?php
					foreach ($JADOOGLE_COLLECTIONS as $collection => $friendly) {
?>
					<label for="<?php print $collection; ?>">
						<input type="checkbox" id="<?php print $collection; ?>" name="collections[]" value="<?php print $collection; ?>" /><?php print $friendly; ?>
					</label>
<?php
					}
?>
				</p>

					<p>
						<label for="googleSearchBox1">With all the words: </label>
						<input class="field" type="text" name="q" id="googleSearchBox1" value="" />
					</p>
					<p>
						<label for="googleSearchBox2">With the exact phrase: </label>
						<input class="field" type="text" name="quoteQuery" id="googleSearchBox2" value="" />
					</p>
					<p>
						<label for="googleSearchBox3">With at least one of the words: </label>
						<input class="field" type="text" name="orQuery" id="googleSearchBox3" value="" />
					</p>

					<p>
						<label for="googleSearchBox4">Without the words: </label>
						<input class="field" type="text" name="excludeWords" id="googleSearchBox4" value="" />
					</p>

					<p>
						<label>Return results:</label>

						<select class="select" name="fileFormatInclusion" id="fileFormatInclusion">
							<option value="">Only as</option>
							<option value="-">Excluding</option>
						</select>

						<select class="select" name="fileFormat" id="fileFormat">
							<option value="">any format</option>
							<option value="pdf">Adobe Acrobat (.pdf)</option>
							<option value="doc">Microsoft Word (.doc)</option>
							<option value="xls">Microsoft Excel (.xls)</option>
							<option value="ppt">Microsoft Powerpoint (.ppt)</option>
							<option value="rtf">Rich text format (.rtf)</option>
						</select>
					</p>

					<input name="searchType" value="advanced" type="hidden" />
				<!-- button -->
				<p class="centre">
					<input class="button" type="submit" name="advancedSubmit" value="Search" />
				</p>

				<div class="clear"></div>
		</form>
		<!-- END Keyword Search -->

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ################ MAIN STRUCTURE ############ -->
			
<?php include("../includes/closing.php"); ?>