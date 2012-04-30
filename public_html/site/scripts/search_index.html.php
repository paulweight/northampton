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
		if (isset($_GET["noResults"]) && $_GET["noResults"]) {
?>
	<h2 class="warning">Your search returned <strong>no results</strong>. Try using the Search below to find what you were looking for.</h2>
<?php 
		}
?>
			
	<!-- Keyword Search -->
	<form action="<?php print getSiteRootURL() . buildNonReadableSearchResultsURL('',true) ?>" method="post" enctype="multipart/form-data">
		<ol>
			<li>
				<ul>
<?php
			$seen = array();
			foreach (array_keys($SEARCHABLE_TABLES) as $table) {
				$friendly = $SEARCHABLE_TABLES[$table]['FRIENDLY_NAME'];
				if (!in_array($friendly, $seen)) {
					$seen[] = $friendly;
?>
					<li><label for="<?php print encodeHtml(str_replace(' ','_',$friendly)); ?>"><input type="checkbox" id="<?php print encodeHtml(str_replace(' ','_',$friendly)); ?>" name="areas[]" class="areas_checkboxes" value="<?php print encodeHtml($friendly); ?>" /><?php print encodeHtml($friendly); ?></label></li>
<?php
				}
			}
?>
				</ul>
			</li>
			<li>
				<label for="all">With all the words</label>
				<input id="all" type="text" name="all" />
			</li>
			<li>
				<label for="without">Without the words</label>
				<input id="without" type="text" name="without" />
			</li>
			<li>
				<label for="any">With any of these words</label>
				<input id="any" type="text" name="any" />
			</li>
			<li>
				<label for="phrase">With the exact phrase</label>
				<input id="phrase" type="text" name="phrase"  />
			</li>
			<li>
				<input type="submit" value="Search" name="advancedSubmit" />
			</li>
		</ol>
	</form>
	<!-- END Keyword Search -->
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>