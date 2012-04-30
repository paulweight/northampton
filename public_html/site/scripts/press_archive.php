<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduPressReleases.php");
	include_once("JaduAppliedCategories.php");

	$archiveStartYear = 2000;
	$numberToDisplay = 10;
	$page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	$offset = ($page * $numberToDisplay) - $numberToDisplay;
	$showNext = false;
	$showPrevious = false;

	// takes into account amount of items on press index
	$displayMinusIndex = false;

	//	Setup suitable defaults
	if (!isset($_GET['year'])) {
		$_GET['year'] = date("Y");
		$noYear = true;
	}

	if (!isset($_GET['month']) || $_GET['month'] < 0 || $_GET['month'] > 12) {
		//$displayMinusIndex = true;
		$_GET['month'] = date("m");
		$noMonth = true;
	}

	//	Validate the numerics
	if (!mb_ereg("^[0-9]{2}$", $_GET['month']) || !mb_ereg("^[0-9]{4}$", $_GET['year']) || $_GET['year'] > date("Y")) {
		header("Location: http://" . $DOMAIN . buildPressURL());
		exit();
	}

	// get the press releases to display
	$allPressReleases = getAllPressReleasesForYearAndMonth($_GET['year'], $_GET['month'], true, true, 'pressDate', 'DESC', $offset, $numberToDisplay);
	$year = (int)$_GET['year'];
	// if there is no press releases for the month then get press releases for the previous month
	// and repeat until press releases is found
	while (sizeof($allPressReleases) == 0) {
		$_GET['month']--;
		if ($_GET['month'] == 0) {
			$_GET['month'] = 12;
			$year = $year-1;
		}	
			
		if ($year < $archiveStartYear) {
			break;
		}
		
		$allPressReleases = getAllPressReleasesForYearAndMonth($year, $_GET['month'], true, true, 'pressDate', 'DESC', $offset, $numberToDisplay);
	}
	
	$textualMonth = strftime('%B', mktime(0, 0, 0, $_GET['month'], 1, $_GET['year']));

	// check whether previous and next links should be displayed
	if ($page > 1) {
		$showPrevious = true;
	}

	$nextPressReleases = getAllPressReleasesForYearAndMonth($_GET['year'], $_GET['month'], true, true, 'pressDate', 'DESC', $numberToDisplay + $offset, $numberToDisplay);

	if (sizeof($nextPressReleases) > 0) {
		$showNext = true;
	}

	unset($nextPressReleases);
	
	$lgcl = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);

	$months = array();
	for ($i = 1; $i <= 12; $i++) {
		$i = str_pad($i, 2, '0', STR_PAD_LEFT);
		$monthName = strftime('%B', mktime(0, 0, 0, $i, 1, $_GET['year']));
		$allPressReleasesForMonthLink = getAllPressReleasesForYearAndMonth($_GET['year'], $i, true, false, 'pressDate', 'DESC', 0, 1);

		if (sizeof($allPressReleasesForMonthLink) > 0 && $i != $_GET['month']) {
			$months[] = array('month' => $i, 'monthname' => $monthName);
		}
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = encodeHtml($_GET['year']) . '  press releases';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildPressURL() . '" >Press releases</a></li><li><span>Press release archive</span></li>';
	
	include("press_archive.html.php");
?>