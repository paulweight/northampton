<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");  
	
	include_once("JaduSearch.php");
	
	$breadcrumb = 'jaduSearch';
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
		<h2 class="warning">Your search returned <strong>no results</strong>. Try using the Search below to find what you were looking for.</h2>
<?php 
		}
?>
			
		<!-- Keyword Search -->
		<form action="http://<?php print $DOMAIN;?>/site/scripts/search_results.php" method="post" class="basic_form">
				<p id="search_checkbox">
<?php
				$seen = array();
				foreach (array_keys($SEARCHABLE_TABLES) as $table) {
					$friendly = $SEARCHABLE_TABLES[$table]['FRIENDLY_NAME'];
					if (!in_array($friendly, $seen)) {
						$seen[] = $friendly;
?>
					<label for="<?php print ereg_replace(' ','_',$friendly);?>"><input type="checkbox" id="<?php print ereg_replace(' ','_',$friendly);?>" name="areas[]" value="<?php print $friendly;?>" /><?php print $friendly;?></label>
<?php
					}
				}
?>
					<span class="clear"></span>		
				</p>

				<!-- all the keywords -->
				<p>
					<label for="all">With all the words</label>
					<input id="all" type="text" name="all" class="field" />
					<span class="clear"></span>
				</p>
		
				<!-- without the words -->
				<p>
					<label for="without">Without the words</label>
					<input id="without" type="text" name="without" class="field" />
					<span class="clear"></span>
				</p>
		
				<!-- at least one word -->
				<p>
					<label for="any">With any of these words</label>
					<input id="any" type="text" name="any" class="field" />
					<span class="clear"></span>
				</p>
				
				<!-- exact phrase -->
				<p>
					<label for="phrase">With the exact phrase</label>
					<input id="phrase" type="text" name="phrase" class="field" />
					<span class="clear"></span>
				</p>
	
				<!-- button -->
				<p class="centre">
					<input type="submit" value="Search" name="advancedSubmit" class="button" />
				</p>
		</form>
		<!-- END Keyword Search -->
	
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>