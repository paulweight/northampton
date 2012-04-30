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
				
		<h2>Statistics Overview</h2>
		<p>Reliable performance is important in order to encourage and maintain the usage of quality web-based services. Our aim is to have the <?php print encodeHtml(METADATA_GENERIC_NAME); ?> website available for use at all times throughout the year.</p>
		
		<p>This is not always possible due to essential maintenance, but we are committed to making our site available as close to 100% as possible.</p>

		<table summary="Website availability and performance statistics for <?php print $year; ?>.">				
			<tbody>
				<tr>
					<th scope="col">Month</th>
					<th scope="col">Pages Viewed</th>
					<th scope="col">Unique Visitors</th>
				</tr>
				<?php
					foreach($stats as $timestamp => $data) {
				?>
				<tr>
					<td><a href="<?php print getSiteRootURL() . buildStatisticsURL( date("Y", $timestamp), date("m", $timestamp)); ?>"><?php print date("F", $timestamp); ?></a> <?php if (date("m", $timestamp) == date('m') && $year == date('Y')) print '(so far)'; ?></td>
					<td><?php print (int) $data['pages']; ?></td>
					<td><?php print (int) $data['visitors']; ?></td>
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
		
		<div class="contactbox">
			<h3>Other available years</h3>
			<ul class="list">
<?php
			while ($firstYear <= date('Y')) {
?>
				<li><?php if ($firstYear == $year) { ?><strong>You are here:</strong> <?php } ?><a href="<?php print getSiteRootURL() . buildStatisticsURL($firstYear); ?>"><?php print (int) $firstYear++; ?></a> </li>
<?php 
			}
?>
			</ul>
		</div>
<?php
	}
?>				

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>