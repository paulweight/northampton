<?php
	include('../../404.php');
	exit;

	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once('JaduConstants.php');
	include_once('lookingGlass/JaduLookingGlass.php');
	include_once('JaduCache.php');
	
	list($firstDay, $firstMonth, $firstYear) = split('[-/]', GO_LIVE_DATE);

	if (isset($_GET['year']) && is_numeric($_GET['year'])) {
		$year = $_GET['year'];
	}
	else {
		$year = date('Y');
	}

	$stats = array();
    
	$lg = new LookingGlass();

	$startMonth = 1;
	$endMonth = date('m');

	if ($year == $firstYear) {
		$startMonth = $firstMonth;
	}

	if ($year < date("Y")) {
		$endMonth = 12;
	}
	
	if (date("d") == 1 && date("Y") == $year) {
		$endMonth = date("m", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));
	}

	for ($i = $startMonth; $i <= $endMonth; $i++) {

		if (date('d') == 1 && (str_pad($i, 2, '0', STR_PAD_LEFT) == date('m'))) {
			$startDate = date("Ymd", mktime(0, 0, 0, $i - 1, 1, $year));
		}
		else {
			($i == $firstMonth && $year == $firstYear)? $d = $firstDay :$d = 1;
			$startDate = date("Ymd", mktime(0, 0, 0, $i, $d, $year));
		}

		if (str_pad($i, 2, '0', STR_PAD_LEFT) == date('m') && $year == date('Y')) {
			$day = date('d') - 1;
			$month = $i;
		}
		else {
			$day = 1 - 1;
			$month = $i + 1;
		}

		$endDate = date("Ymd", mktime(0, 0, 0, $month, $day, $year));
		
		$lg->getStatsForRange($startDate, $endDate, array('generalReport' => 1));

		$stats[mktime(0, 0, 0, $i, 1, $year)] = array(
			'pages' => $lg->generalReport->successfulRequestsForPages, 
			'visitors' => $lg->generalReport->distinctHostsServed
		);
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Website statistics '. $year;
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li class="bc_end"><span>Website statistics : '.encodeHtml($year) .'</span></li>';
	
	include("website_statistics.html.php");
?>