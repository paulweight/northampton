<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="news, <?php print $dateShowing; ?>, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />	
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s archived news from <?php print $dateShowing; ?>" />
	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - News archive - <?php print $dateShowing;?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>'s archived news from <?php print $dateShowing; ?>" />
	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<h2>News published <?php print $dateShowing;?></h2>

<?php
	if (sizeof($allNews) == 0) {
?>

	<p>There is no news for this month, please select another month or see the <a href="<?php print getSiteRootURL() . buildNewsURL();?>">latest news</a>.</p>
	
<?php
	} 
	else {
?>
	
	<ul>	
<?php
		foreach ($allNews as $news) {
?>

	<li>
		<h3><a href="<?php print getSiteRootURL() . buildNewsArticleURL($news->id);?>"><?php print encodeHtml($news->title); ?></a></h3>
				
<?php 
			if ($news->imageURL != "") { 
?>
	<a href="<?php print getSiteRootURL() . buildNewsArticleURL($news->id);?>" ><img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($news->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($news->imageURL, 'altText')); ?>" /></a>
<?php 
			}
?>
		<p>Published <?php print formatDateTime(FORMAT_DATE_LONG, $news->newsDate);?></p>
		<p><?php print encodeHtml($news->summary); ?></p>
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
				print '<a href="' . getSiteRootURL() . buildNewsArchiveURL($_GET['year'], $_GET['month'], $previous) . '">Previous page</a>';
			}
			if ($showNext && $showPrevious) {
				print ' | ';
			}
			if ($showNext) {
				print '<a href="' . getSiteRootURL() . buildNewsArchiveURL($_GET['year'], $_GET['month'], $next) . '">Next page</a>';
			}
?>
	</p>
<?php
		}
?>

	
<?php
	if(sizeof($months) > 0) {
?>
	<h2>Available Months for <?php print encodeHtml($_GET['year']); ?></h2>
	<ul>
<?php
		foreach($months as $key => $month) {
			if ($month['month'] == $_GET['month']) {
				print '<li>' . encodeHtml($month['monthname']).' '.encodeHtml($_GET['year']) . '</li>';
			}
			else {
?>
			<li><a href="<?php print getSiteRootURL() . buildNewsArchiveURL($_GET['year'], $month['month']);?>"><?php print encodeHtml($month['monthname']); ?> <?php print encodeHtml($_GET['year']);?></a></li>
<?php
			}
			if ($key + 1 < sizeof($months)) {
?>

<?php
			}
		}
		print '</ul>';
	}

	$firstNews = getFirstNews(true, true);
	$lastNews = getLastNews(true, true);
	
	$firstYear = 1970;
	if ($firstNews != null) {
		$firstYear = date('Y', $firstNews->newsDate);
	}
	
	$lastYear = date('Y');
	if ($lastNews != null) {
		$lastYear = date('Y', $lastNews->newsDate);
	}
	
	$yearHasBeenOutput = false;
?>
		
	<h2>Available years</h2>
	<ul>
<?php
	for ($j = $lastYear; $j >= $firstYear; $j--) {
		if (sizeof(getAllNewsForYearAndMonth($j, 0, true, true, 'newsDate', 'DESC', -1, $numberToDisplay)) > 0) {
			if ($yearHasBeenOutput) {
			}
			if ($j != $_GET['year']) {
?>
		<li><a href="<?php print getSiteRootURL() . buildNewsArchiveURL($j, 1);?>"><?php print $j;?></a></li>
<?php
			}
			else {
				print '<li>'.$j.'</li>';
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
