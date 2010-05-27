<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduNews.php");
	include_once("JaduAppliedCategories.php");
	include_once("websections/JaduFeedManager.php");

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
	if (!ereg("^[0-9]{2}$", $_GET['month']) || !ereg("^[0-9]{4}$", $_GET['year']) || $_GET['year'] > date("Y")) {
		header("Location: http://$DOMAIN/site/scripts/news_index.php");
		exit();
	}

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
	
	$months = array();
	for ($i = 1; $i <= 12; $i++) {
		$i = str_pad($i, 2, '0', STR_PAD_LEFT);
		$monthName = date('F', mktime(0, 0, 0, $i, 1, $_GET['year']));
		$allNewsForMonthLink = getAllNewsForYearAndMonth($_GET['year'], $i, true, true, 'newsDate', 'DESC', 0, 1);

		if (sizeof($allNewsForMonthLink) > 0 && $i != $_GET['month']) {
			$months[] = array(month => $i, monthname => $monthName);
		}
	}
	
	$dateShowing = date('F', mktime(0, 0, 0, $_GET['month'], 1, $_GET['year'])) . ' ' . $_GET['year'];
	
	$breadcrumb = 'newsArchive';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php if (sizeof($allNews) > 0) { print ' '. $dateShowing; } ?> | News archive | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="news, <?php print $dateShowing; ?>, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />	
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s archived news from <?php print $dateShowing; ?>" />
	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - News archive - <?php print $dateShowing;?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s archived news from <?php print $dateShowing; ?>" />
	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<h2>News archive for <?php print $monthName = date('F', mktime(0, 0, 0, $_GET['month'], 1, $_GET['year']));?> <?php print $_GET['year'];?></h2>
<?php
	if(sizeof($months) > 0) {
		print '<p class="first">';
		foreach($months as $month) {
?>
	<a href="http://<?php print $DOMAIN;?>/site/scripts/news_archive.php?year=<?php print $_GET['year'];?>&amp;month=<?php print $month[month];?>"><?php print $month[monthname]; ?> <?php print $_GET['year'];?></a> |
<?php
		}
		print '</p>';
	}

	$tmpYear = date('Y');
	$allNewsForYearLink = getAllNewsForYearAndMonth($tmpYear, '00', true, true, 'newsDate', 'DESC', 0, 1);

	if (!empty($allNewsForYearLink)) {
?>
		
	<form method="get" action="http://<?php print $DOMAIN;?>/site/scripts/news_archive.php" class="basic_form newsForm">
		<input type="hidden" value="01" name="month" />

		<p>
			<label for="year" >Available Years:
				<select id="year" name="year" class="select">
	<?php
			while (sizeof($allNewsForYearLink) > 0) {
				if ($tmpYear != $_GET['year']) {
	?>
				<option value="<?php print $tmpYear;?>"><?php print $tmpYear;?></option>
	<?php
				}
				else {
	?>
				<option value="<?php print $tmpYear;?>" selected="selected"><?php print $tmpYear;?></option>
	<?php
				}
	
				$tmpYear--;
				$allNewsForYearLink = getAllNewsForYearAndMonth($tmpYear, '00', true, true, 'newsDate', 'DESC', 0, 1);
			}
	?>
				
				</select>
				<input type="submit" class="button" value="Go" />
			</label>
		</p>
	</form>
		
		<!-- News list -->
<?php
	}

	if (sizeof($allNews) == 0) {
?>
	<p class="first">There is no news in this month, please select another month or see <a href="http://<?php print $DOMAIN;?>/site/scripts/news_index.php">Latest news</a></p>
<?php
	} 
	else {
		foreach ($allNews as $news) {
?>
	<div class="content_box">
<?php 
	if ($news->imageURL != "") { 
?>
		<a href="http://<?php print $DOMAIN; ?>/site/scripts/news_article.php?newsID=<?php print $news->id; ?>">
			<img src="http://<?php print $DOMAIN;?>/images/<?php print $news->imageURL;?>" alt="<?php print getImageProperty($news->imageURL, 'altText'); ?> " />
		</a>
<?php 
	}
?>
		<h3>
			<a href="http://<?php print $DOMAIN;?>/site/scripts/news_article.php?newsID=<?php print $news->id; ?>"><?php print $news->title;?></a>
		</h3>
		<p class="date">Published <?php print date("jS F Y", $news->newsDate);?> in <a href="http://<?php print $DOMAIN; ?>/site/scripts/news_category.php?categoryID=<?php print $currentCategory->id;?>"><?php print $currentCategory->name ?> news</a></p>
		<p><?php print $news->summary;?></p>
	</div>	
<?php
		}
	}
	
	if ($showNext || $showPrevious) {
?>

	<p class="first">
<?php
			$previous = $page - 1;
			$next = $page + 1;
			if ($showPrevious) {
				print '<a href="http://' . $DOMAIN . '/site/scripts/news_archive.php?page=' . $previous . '&amp;month=' . $_GET['month'] . '&amp;year=' . $_GET['year'] . '">&laquo; Previous</a>';
			}
			if ($showNext && $showPrevious) {
				print ' | ';
			}
			if ($showNext) {
				print '<a href="http://' . $DOMAIN . '/site/scripts/news_archive.php?page=' . $next . '&amp;month=' . $_GET['month'] . '&amp;year=' . $_GET['year'] . '">Next &raquo;</a>';
			}
?>
	</p>
<?php
		}
?>


	<p class="rssfeed"><a href="http://<?php print $DOMAIN;?>/site/scripts/rss.php" target="_blank">News RSS</a></p>
			
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
