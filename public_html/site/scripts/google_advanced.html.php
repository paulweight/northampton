<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="search, advanced, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Advanced Search facilities" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Advanced Search" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Advanced Search facilities" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->

<?php include("../includes/opening.php"); ?>
			
<!-- ########################## -->
						
<?php
		//	Print none found error message
		if (isset($_GET["noResults"]) && $noResults) {
?>
	<h2>Your search returned <strong>no results</strong>, try using the Search below to find what you were looking for</h2>
<?php 
		}
?>
			
		<!-- Keyword Search -->
	<form class="basic_form xform" action="<?php print getSiteRootURL() . buildSearchResultsURL('',true) ?>" method="get">
<?php
		if (sizeof($collections) > 0) {
?>
			<!-- check boxes -->
		
<?php
			foreach ($collections as $collection) {
?>
			<p class="search"><label for="<?php print encodeHtml($collection->collectionName); ?>">
				<input type="checkbox" class="checkbox"  id="<?php print encodeHtml($collection->collectionName); ?>" name="sites[]" value="<?php print encodeHtml($collection->collectionName); ?>" /><?php print encodeHtml($collection->friendlyName); ?>
			</label></p>
<?php
			}
?>
		
<?php
		}
?>
		<p>
			<label for="googleSearchBox1">With all the words: </label>
			<input type="text" name="q" id="googleSearchBox1" value="" />
		</p>
		<p>
			<label for="googleSearchBox2">With the exact phrase: </label>
			<input type="text" name="quoteQuery" id="googleSearchBox2" value="" />
		</p>
		<p>
			<label for="googleSearchBox3">With at least one of the words: </label>
			<input type="text" name="orQuery" id="googleSearchBox3" value="" />
		</p>
		<p>
			<label for="googleSearchBox4">Without the words: </label>
			<input type="text" name="excludeWords" id="googleSearchBox4" value="" />
		</p>
		<p>
			<label for="fileFormatInclusion">Return results:</label>
			<select name="fileFormatInclusion" id="fileFormatInclusion">
				<option value="">Only as</option>
				<option value="-">Excluding</option>
			</select>
			<label for="fileFormat">in format:</label>
			<select name="fileFormat" id="fileFormat">
				<option value="">any format</option>
				<option value="pdf">Adobe Acrobat (.pdf)</option>
				<option value="doc">Microsoft Word (.doc)</option>
				<option value="xls">Microsoft Excel (.xls)</option>
				<option value="ppt">Microsoft Powerpoint (.ppt)</option>
				<option value="rtf">Rich text format (.rtf)</option>
			</select>
		</p>
		<div><input name="searchType" value="advanced" type="hidden" /></div>
		<p class="centre">
			<input type="submit" name="advancedSubmit" value="Search" class="genericButton grey" />
		</p>
	</form>
			
<!-- ################ MAIN STRUCTURE ############ -->
			
<?php include("../includes/closing.php"); ?>
