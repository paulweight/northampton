<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="statistics, page views, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is committed to making our site available as close to 100% as possible" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> statistics" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is committed to making our site available as close to 100% as possible" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->	
	
	<p>	
<?php
	if (($year == date('Y') && str_pad($month, 2, '0', STR_PAD_LEFT) < date('m')) || $year < date('Y')) {
?>
		<a href='http://<?php print DOMAIN . buildStatisticsURL($nextYear, $nextMonth); ?>' class="float_right">Next Month</a>
<?php
	}
	if (($year == $firstYear && str_pad($month, 2, '0', STR_PAD_LEFT) > $firstMonth) || $year > $firstYear) {
?>
		<a href='http://<?php print DOMAIN . buildStatisticsURL($previousYear, $previousMonth); ?>' class="float_left">Previous Month</a> 
<?php
	}
?>
	</p>	
	<div class="clear"></div>
<?php
	if (!empty($lg->dailyReport) && !empty($lg->requestsReport)) {
?>

		<h2>Daily Report</h2>
		<table summary="Daily Report for <?php print encodeHtml($month . " " . $year) ?>">
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
					print formatDateTime(FORMAT_DATE_MEDIUM, mktime(0,0,0,$tDate[1], $tDate[2], $tDate[0]));
?>
				</td>
				<td>
<?php 
					$total += $data['pages'];
					print (int) $data['pages'];
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
			<table summary="Top 10 file requests for <?php print encodeHtml($month . " " . $year) ?>">
			<tbody>
				<tr>
					<th>Filename</th>
					<th>Pages Viewed</th>
				</tr>
<?php
				$request_limit = 10;
				$request_num = 0;
				foreach ($lg->requestsReport->requests as $key => $value) {
?>
				<tr>
					<td>
						<a href="<?php print getSiteRootURL() . encodeHtml($key); ?>">
<?php 
							if(mb_strlen($key) > 50) print encodeHtml(mb_substr($key,0,50)."..."); else print encodeHtml($key);
?>
						</a>
					</td>
					<td><?php print (int) $value['requests']; ?></td>
				</tr>
<?php
				    $request_num++;
					
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

				
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>