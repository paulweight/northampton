<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="press releases, news, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />	
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s press releases for <?php print encodeHtml($textualMonth . ' ' . $_GET['year']); ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Press release archive - <?php print encodeHtml($textualMonth . ' ' . $_GET['year']); ?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s press releases for <?php print encodeHtml($textualMonth . ' ' . $_GET['year']); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->



<?php
	if (sizeof($allPressReleases) == 0) {
?>
		<p>There are no press releases in this archive, please see <a href="<?php print getSiteRootURL() . buildPressURL(); ?>">Latest press releases</a>.</p>
<?php
	} 
	else {
?>
	<ul class="archive">
<?php
		foreach ($allPressReleases as $pressRelease) {
?>

	<li>
		<h3><a href="<?php print getSiteRootURL() . buildPressArticleURL($pressRelease->id);?>"><?php print $pressRelease->title;?></a></h3>
<?php 
			if ($pressRelease->imageURL != "") { 
?>
		<a href="<?php print getSiteRootURL() . buildPressArticleURL($pressRelease->id); ?>" >
			<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($pressRelease->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($pressRelease->imageURL, 'altText')); ?> " />
		</a>
<?php 
			}
?>
		<p class="date">Published <?php print formatDateTime(FORMAT_DATE_FULL, $pressRelease->pressDate);?></p>
		<p><?php print encodeHtml($pressRelease->summary); ?></p>
		<div class="clear"></div>
	</li>
	
<?php
		}
?>
	</ul>
	
<?php
	}
	
	if ($showNext || $showPrevious) {
?>

	<p>
<?php
			$previous = $page - 1;
			$next = $page + 1;
			if ($showPrevious) {
				print '<a href="' . getSiteRootURL() . buildNewsArchiveURL($_GET['year'], $_GET['month'], $previous) . '">&laquo; Previous</a>';
			}
			if ($showNext && $showPrevious) {
				print ' | ';
			}
			if ($showNext) {
				print '<a href="' . getSiteRootURL() . buildNewsArchiveURL($_GET['year'], $_GET['month'], $next). '">Next &raquo;</a>';
			}
?>
	</p>
<?php
		}
?>

	
<?php
	if(sizeof($months) > 0) {
		print '<ul>';
		foreach($months as $index => $month) {
?>
			<li><a href="<?php print getSiteRootURL() . buildPressArchiveURL($_GET['year'], $month['month']);?>"><?php print encodeHtml($month['monthname']); ?> <?php print encodeHtml($_GET['year']); ?></a> </li>
<?php
		}
		print '</ul>';
	}
?>

	<h2>Available years</h2>
	<ul class="bottomList">
<?php
	$tmpYear = date('Y');
	foreach (range(date('Y'),$archiveStartYear) as $year) {
		$allPressReleasesForYearLink = getAllPressReleasesForYearAndMonth($year, '00', true, true, 'pressDate', 'DESC', 0, 1);	
		if (count($allPressReleasesForYearLink) > 0) {	
			if ($year != $_GET['year']) {
?>
		<li><a href="<?php print getSiteRootURL() . buildPressArchiveURL($year, 1);?>"><?php print $year;?></a></li>
<?php
			}
			else {
				print '<li>'.$year.'</li>';
			}
			$yearHasBeenOutput = true;
		}
?>
<?php
	}
?>
	</ul>

		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
