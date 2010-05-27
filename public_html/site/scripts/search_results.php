<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("JaduSearch.php");

	$search = new JaduSearch();
	$allResults = array();

	if (isset($_REQUEST['searchQuery'])) {
		$allResults = $search->runFullSearch($_REQUEST['searchQuery'], array_keys($SEARCHABLE_TABLES), false);
		$searchFor = $_REQUEST['searchQuery'];
		$nonFormattedArgs = $_REQUEST['searchQuery'];
	}
	else if (isset($_POST['advancedSubmit'])) {

		$searchable = array_keys($SEARCHABLE_TABLES);
		if (sizeof($areas) > 0) {
			foreach ($areas as $i => $area) {
				if (!strpos($area, '_') === FALSE) {
					$areas[$i] = ereg_replace('_',' ',$area);
				}
			}
			$searchable = getTableSetForFriendlyName($areas);
		}

		$nonFormattedArgs = array("all"=>$_POST['all'], "without"=>$_POST['without'], "any"=>$_POST['any'], "phrase"=>$_POST['phrase']);
		$allResults = $search->runFullSearch("", $searchable, true, $nonFormattedArgs);

		$searchFor = "";
		if ($_POST['all'] != "") {
			$searchFor .= "all the words: " . $_POST['all'] . ", ";
		}
		if ($_POST['any'] != "") {
			$searchFor .= "any of the words: " . $_POST['any'] . ", ";
		}
		if ($_POST['without'] != "") {
			$searchFor .= "without any of the words: " . $_POST['without'] . ", ";
		}
		if ($_POST['phrase'] != "") {
			$searchFor .= "with the exact phrase: \"" . $_POST['phrase'] . "\", ";
		}
		$searchFor = substr($searchFor, 0, -2);
	}

	$numAreas = sizeof($allResults);
	$numResults = 0;
	foreach ($allResults as $result) {
		$numResults += sizeof($result);
	}
	
	if ($numResults == 0) {
		header("Location: http://".$DOMAIN."/site/scripts/search_index.php?noResults=true");
		exit;
	}

	$breadcrumb = 'jaduSearchResults';	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Search results for <?php print $searchFor;?> | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="search, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Search Results Listing" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Search Results" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Search Results Listing" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($numResults == 1) {
?>
		<p class="first">There was <strong>one</strong> result found for <strong><?php print $searchFor;?></strong>. Those areas are:</p>
<?php
	}
	else {
?>
		<p class="first">There were <strong><?php print $numResults;?></strong> results found in <strong><?php print $numAreas;?></strong> area<?php if ($numAreas > 1) print "s";?> for <strong><?php print $searchFor;?></strong>. Those areas are:</p>
										
<?php
	}
		
	//	Print out the area anchors
	if (sizeof ($allResults) > 1) {
		
		$leftCategories = array();
		$rightCategories = array();
		foreach (array_keys($allResults) as $index => $foundInResults) {
			if ($index % 2 == 0) {
				$leftCategories[] = $foundInResults;
			}
			else {
				$rightCategories[] = $foundInResults;
			}
	}
		
?>
		<div class="cate_info">
<?php
		if (sizeof($leftCategories) > 0) {
?>
			<ul class="info_left">
<?php 
			foreach($leftCategories as $categoryItem) {
				$catItem =	ereg_replace(' ','_',$categoryItem);
?>
				<li><a href="http://<?php print DOMAIN; ?>/site/scripts/search_results.php?<?php print $_SERVER['QUERY_STRING'].'#'.$catItem;?>"><?php print $categoryItem;?></a></li>
<?php
			}
?>
			</ul>
<?php
		}

		if (sizeof($rightCategories) > 0) {
?>
			<ul class="info_right">
<?php 
			foreach($rightCategories as $categoryItem) {
				$catItem =	str_replace(' ','_', $categoryItem);
		
?>
				<li><a href="http://<?php print DOMAIN; ?>/site/scripts/search_results.php?<?php print $_SERVER['QUERY_STRING'].'#'.$catItem;?>"><?php print $categoryItem;?></a></li>
<?php
			}
?>
			</ul>
<?php
		}
?>
			<div class="clear"></div>
		</div>
			
<?php
	}
		
		foreach (array_keys($allResults) as $foundInResults) {
			$catItem =	str_replace(' ','_', $foundInResults);
?>

			<h2 id="<?php print $catItem;?>"><span>Results found in <?php print $foundInResults;?></span></h2>
			<p class="small"><a rel="nofollow" href="http://<?php print $DOMAIN; ?>/site/scripts/search_results.php#mast">Top of the page</a></p>		
<?php
			foreach ($allResults[$foundInResults] as $object) {
?>	
			<div class="search_result">
				<h3><?php print $search->formatAndReturnTitleLink ($object, $nonFormattedArgs);?></h3>
				<p><?php print $search->formatAndReturnResult ($object, $nonFormattedArgs);?></p>
				<p class="url"><?php print $search->formatAndReturnRelevanceRating ($object);?></p>
			</div>
<?php
			}
		}
?>
		 
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>