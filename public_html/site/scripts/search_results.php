<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("JaduSearch.php");
	
	if (isset($_POST['q']) && !isset($_POST['advancedSubmit'])) {
		header('Location: http://'. DOMAIN . buildSearchResultsURL($_POST['q']));
		exit();
	}

	$search = new JaduSearch();
	$allResults = array();

	if (isset($_REQUEST['q']) && $_REQUEST['q'] != '') {
		$allResults = $search->runFullSearch($_REQUEST['q'], array_keys($SEARCHABLE_TABLES), false);
		$searchFor = $_REQUEST['q'];
		$nonFormattedArgs = $_REQUEST['q'];
	}
	else if (isset($_POST['advancedSubmit']) && isset($_POST['areas'])) {
		$searchable = array_keys($SEARCHABLE_TABLES);
		if (sizeof($_POST['areas']) > 0) {
			foreach ($_POST['areas'] as $i => $area) {
				if (!mb_strpos($area, '_') === FALSE) {
					$_POST['areas'][$i] = mb_ereg_replace('_',' ',$area);
				}
			}
			$searchable = getTableSetForFriendlyName($_POST['areas']);
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
		$searchFor = mb_substr($searchFor, 0, -2);
	}

	$numAreas = sizeof($allResults);
	$numResults = 0;
	foreach ($allResults as $result) {
		$numResults += sizeof($result);
	}
	
	if ($numResults == 0) {
		header( 'Location: http://' . $DOMAIN . buildSearchURL(true) );
		exit;
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Search results';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildSearchURL() .'">Advanced search</a></li><li><span>Search results</span></li>';
	
	include("search_results.html.php");
?>