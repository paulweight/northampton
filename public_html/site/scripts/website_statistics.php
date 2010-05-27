<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once('JaduConstants.php');
	include_once('lookingGlass/JaduLookingGlass.php');
	include_once('JaduCache.php');
	
	$PAGES = 0;
	$VISITORS = 1;

	$firstYear = '2009';
	$firstMonth = '04';
	$firstDay = '01';

	if (isset($_GET['year']) && is_numeric($_GET['year'])) {
		$year = $_GET['year'];
	}
	else {
		$year = date('Y');
	}

	$stats = array();

	$lg = new JaduLookingGlass();

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
			$startDate = date("Ymd", mktime(0, 0, 0, $i, 1, $year));
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

		$stats[mktime(0, 0, 0, $i, 1, $year)] = array($lg->generalReport->successfulRequests + $lg->generalReport->failedRequests + $lg->generalReport->redirectedRequests, 
													  $lg->generalReport->distinctHostsServed);
	}
	
	$breadcrumb = 'webStat';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Statistics | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="statistics, page views, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to making our site available as close to 100% as possible" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> statistics" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to making our site available as close to 100% as possible" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
				
	<h2>Statistics overview</h2>
	
	<p class="first">Reliable performance is important in order to encourage and maintain the usage of quality web-based services. Our aim is to have the <?php print METADATA_GENERIC_COUNCIL_NAME; ?> website available for use at all times throughout the year.</p>
		
	<p>This is not always possible due to essential maintenance, but we are committed to making our site available as close to 100% as possible.</p>

	<table summary="Website availability and performance statistics for <?php print $year; ?>.">				
		<tbody>
			<tr>
				<th scope="col">Month</th>
				<th scope="col">Pages Requested</th>
				<th scope="col">Unique Visitors</th>
			</tr>
			<?php
				foreach($stats as $timestamp => $data) {
			?>
			<tr>
				<td><a href="http://<?php print $DOMAIN; ?>/site/scripts/website_statistics_detail.php?month=<?php print date("m", $timestamp); ?>&amp;year=<?php print date("Y", $timestamp); ?>"><?php print date("F", $timestamp); ?></a> <?php if (date("m", $timestamp) == date('m')) print '(so far)'; ?></td>
				<td><?php print $data[$PAGES]; ?></td>
				<td><?php print $data[$VISITORS]; ?></td>
			</tr>
			<?php
				}
			?>
		</tbody>
	</table>

	<p><strong>Visitors:</strong> This figure represents the number of unique visitors during the relevant period. This means that any visitor who has already been to the site during this period is not counted again.</p>

	<p><strong>Pages Requested:</strong> This figure represents the total number of pages requested from the web site. If a visitor visits a page more than once during the same session or in the relevant period, it is re-counted.</p>

<?php
	if ($firstYear != date('Y')) {
?>
		

	<h3>Other available years</h3>
	<ul class="pagenav">
<?php
			while ($firstYear <= date('Y')) {
?>
		<li><?php if($firstYear == $year) { ?><strong>You are here</strong> <?php } ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/website_statistics.php?year=<?php print $firstYear; ?>"><?php print $firstYear; ?></a> 
		</li>
<?php
				$firstYear++;
			}
?>
	</ul>

<?php
	}
?>		
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>