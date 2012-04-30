<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("lookingGlass/JaduLookingGlass.php");
	
	if (!is_numeric($_GET['month']) || !is_numeric($_GET['year'])) {
		header("Location: website_statistics.php");
		exit;
	}

	$month = $_GET['month'];
	$year = $_GET['year'];
	
	list($firstDay, $firstMonth, $firstYear) = split('[-/]',GO_LIVE_DATE);

	if ($month == 12) {
		$nextMonth = 1;
		$nextYear = $year + 1;

		$previousMonth = $month - 1;
		$previousYear = $year;
	}
	elseif($month == 1) {
		$nextMonth = $month + 1;
		$nextYear = $year;

		$previousMonth = 12;
		$previousYear = $year - 1;
	}
	else {
		$nextMonth = $month + 1;
		$nextYear = $year;

		$previousMonth = $month - 1;
		$previousYear = $year;
	}

	if ($month == date('m')) {
		$endMonth = date('m');
		$day = date('d') - 1;
	}
	else {
		$day = 1 - 1;
		$endMonth = $month + 1;
	}
	
	if ($year > date('Y') || ($year == date('Y') && str_pad($month, 2, '0', STR_PAD_LEFT) > date('m'))) {
		header('HTTP/1.0 404 Not Found');
		$lg = null;
	}
	else {
		$cache = new Cache('LookingGlass_' . (int) $year . '_' . (int) $month, 'website_statistics_detail');
		if ($cache->isEmpty()) {
			$lg = new LookingGlass();
			$startDay = ($year == $firstYear && $month == $firstMonth)? str_pad($firstDay, 2, '0', STR_PAD_LEFT) : '01';
			$startDate = $year . str_pad($month, 2, '0', STR_PAD_LEFT) . $startDay;
			$endDate = date('Ymd', mktime(0, 0, 0, $endMonth, $day, $year));
			$lg->getStatsForRange($startDate, $endDate, array('dailyReport' => 1, 'requestsReport' => 1));
			
			$cache->setData($lg);
		}
		else {
			$lg = $cache->data;
		}
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Website statistics '. sprintf("%s %s", strftime('%B', mktime(0, 0, 0, $month, 1, $year)), $year);
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Website statistics '. sprintf("%s %s", strftime('%B', mktime(0, 0, 0, $month, 1, $year)), $year) .'</span></li>';
	
	include("website_statistics_detail.html.php");
?>