<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="search, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Search Results Listing" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Search Results" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Search Results Listing" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($numResults == 1) {
?>
	<p>There was <strong>one</strong> result found for <strong><?php print encodeHtml($searchFor); ?></strong>. Those areas are:</p>
<?php
	}
	else {
?>
	<p>There were <strong><?php print encodeHtml($numResults); ?></strong> results found in <strong><?php print encodeHtml($numAreas); ?></strong> area<?php if ($numAreas > 1) print "s";?> for <strong><?php print encodeHtml($searchFor); ?></strong>. Those areas are:</p>
										
<?php
	}
		
	//	Print out the area anchors
	if (sizeof ($allResults) > 1) {
		
		$leftCategories = array();
		$rightCategories = array();		
		foreach (array_keys($allResults) as $index => $foundInResults) {
			$searchCategories[] = $foundInResults;
		}
		
?>
		<ul class="list">
<?php
		if (sizeof($searchCategories) > 0) {
			foreach($searchCategories as $categoryItem) {
				$catItem =	mb_ereg_replace(' ','_',$categoryItem);
?>
				<li><a href="<?php print encodeHtml($_SERVER['REQUEST_URI']); ?>#<?php print encodeHtml($catItem); ?>"><?php print encodeHtml($categoryItem); ?></a></li>
<?php
			}
		}
?>
		</ul>			
<?php
	}
		
		foreach (array_keys($allResults) as $foundInResults) {
			$catItem =	str_replace(' ','_', $foundInResults);
?>

	<h2 id="<?php print encodeHtml($catItem); ?>">Results found in <?php print encodeHtml($foundInResults); ?></h2>
	<ul>

<?php
			foreach ($allResults[$foundInResults] as $object) {
?>	
		<li>
			<h3><?php print $search->formatAndReturnTitleLink($object, $nonFormattedArgs); ?></h3>
			<p><?php print $search->formatAndReturnResult($object, $nonFormattedArgs); ?></p>
			<p class="url"><?php print $search->formatAndReturnRelevanceRating($object); ?></p>
		</li>
<?php
			}
?>
	</ul>
<?php
		}
?>
		 
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
