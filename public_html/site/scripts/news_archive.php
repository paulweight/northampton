<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduNews.php");
	include_once("JaduAppliedCategories.php");

	$numberToDisplay = 10;

	$page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	
	$offset = ($page * $numberToDisplay) - $numberToDisplay;
		
	$showNext = false;
	$showPrevious = false;

	// takes into account amount of items on news index
	$displayMinusIndex = false;

	//	Setup suitable defaults
	if (!isset($_GET['year'])) {
		$_GET['year'] = date("Y");
		$noYear = true;
	}

	if (!isset($_GET['month']) || $_GET['month'] < 1 || $_GET['month'] > 12) {
		//$displayMinusIndex = true;
		$_GET['month'] = date("m");
		$noMonth = true;
	}

	//	Validate the numerics
	if (!mb_ereg("^[0-9]{1,2}$", $_GET['month']) || !mb_ereg("^[0-9]{4}$", $_GET['year']) || $_GET['year'] > date("Y")) {
		header("Location: http://$DOMAIN" . buildNewsURL());
		exit();
	}

	$monthWithNewsFound = false;
	$firstMonthInYearWithNews = $_GET['month'];

	$months = array();
	for ($i = 1; $i <= 12; $i++) {
		$i = str_pad($i, 2, '0', STR_PAD_LEFT);
		$monthName = strftime('%B', mktime(0, 0, 0, $i, 1, $_GET['year']));
		$allNewsForMonthLink = getAllNewsForYearAndMonth($_GET['year'], $i, true, true, 'newsDate', 'DESC', 0, 1);

		if (sizeof($allNewsForMonthLink) > 0) {
			if ($firstMonthInYearWithNews == 1 && !$monthWithNewsFound) {
				$firstMonthInYearWithNews = $i;
				$monthWithNewsFound = true;
			}
			$months[] = array('month' => $i, 'monthname' => $monthName);
		}
	}

	$_GET['month'] = $firstMonthInYearWithNews;


	// get the news to display
	$allNews = getAllNewsForYearAndMonth($_GET['year'], $_GET['month'], true, true, 'newsDate', 'DESC', $offset, $numberToDisplay);

	// check whether previous and next links should be displayed
	if ($page > 1) {
		$showPrevious = true;
	}
	
	$nextNews = getAllNewsForYearAndMonth($_GET['year'], $_GET['month'], true, true, 'newsDate', 'DESC', $numberToDisplay + $offset, $numberToDisplay);

	if (sizeof($nextNews) > 0) {
		$showNext = true;
	}
	unset($nextNews);
	
	$dateShowing = strftime('%B', mktime(0, 0, 0, $_GET['month'], 1, $_GET['year'])) . ' ' . $_GET['year'];
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'News archive';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildNewsURL() .'" >Latest news</a></li><li><span>News archive</span></li>';
	
	include("news_archive.html.php");
?>