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

	$cache = new Cache('LookingGlass_' . (int) $year . '_' . (int) $month, 'website_statistics_detail');
	if ($cache->isEmpty()) {
		$lg = new JaduLookingGlass();
		$startDate = $year . str_pad($month, 2, '0', STR_PAD_LEFT) . '01';
		$endDate = date('Ymd', mktime(0, 0, 0, $endMonth, $day, $year));
		$lg->getStatsForRange($startDate, $endDate, array('dailyReport' => 1, 'requestsReport' => 1));
		
		$cache->setData($lg);
	}
	else {
		$lg = $cache->data;
	}

	$breadcrumb ='webStatDetails';
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
	
	<p class="first">	
<?php
	if ($nextMonth <= date('m')) {
?>
		<a href='http://<?php print $DOMAIN;?>/site/scripts/website_statistics_detail.php<?php print "?month=".$nextMonth."&year=".$nextYear; ?>'>Next Month</a>
<?php
	}
	if ($previousYear . str_pad($previousMonth, 2, '0', STR_PAD_LEFT) >= $firstYear . $firstMonth) {
?>
		<a href='http://<?php print $DOMAIN;?>/site/scripts/website_statistics_detail.php<?php print "?month=".$previousMonth."&year=".$previousYear; ?>'>Previous Month</a> 
<?php
	}
?>
	</p>	
<?php
	if (!empty($lg->dailyReport) && !empty($lg->requestsReport)) {
?>

		<h2>Daily Report</h2>
		<table summary="Daily Report for <?php print($month . " " . $year) ?>">					
		<tbody>
			<tr>
				<th>Day</th>
				<th>Pages Viewed</th>
			</tr>
<?php
		$total = 0;
		foreach ($lg->dailyReport->days as $day => $data) {
?>
			<tr>
				<td>
<?php
					$tDate = explode("-", $day);
					print date("j M Y", mktime(0,0,0,$tDate[1], $tDate[2], $tDate[0]));
?>
				</td>
				<td>
<?php 
					$total += $data['requests'];
					print $data['requests'];
?>
				</td>
			</tr>
<?php
		}
?>
			<tr>
				<td><strong>Total</strong></td>
				<td><strong><?php print $total;?></strong></td>
			</tr>
		</tbody>
		</table>

			<h2>Top 10 pages</h2>
			<table summary="Top 10 file requests for <?php print($month . " " . $year) ?>">					
			<tbody>
				<tr>
					<th>Filename</th>
					<th>Pages Viewed</th>
				</tr>
<?php
				$request_limit = 10;
				$request_num = 0;
				foreach ($lg->requestsReport->requests as $key => $value) {
					if (strpos($key, ".php") > 0 && strpos($key, "MicrositesSOAP") === false) {
?>
				<tr>
					<td>
						<a href="http://<?php print $DOMAIN . $key; ?>">
<?php 
							if(strlen($key) > 50) print(substr($key,0,50)."..."); else print $key;
?>
						</a>
					</td>
					<td><?php print($value['requests']); ?></td>
				</tr>
<?php
						$request_num++;
					}
					if ($request_num >= $request_limit) {
						break;
					}
				}
?>
			</tbody>
			</table>

<?php
			}
			else {
?>
			<h2>Sorry, there are no statistics available for your chosen month.</h2>
<?php
		}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->


				
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>