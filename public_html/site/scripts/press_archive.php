<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduPressReleases.php");
	include_once("JaduAppliedCategories.php");
	include_once("websections/JaduFeedManager.php");


	$numberToDisplay = 10;
	$page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
	$offset = ($page * $numberToDisplay) - $numberToDisplay;
	$showNext = false;
	$showPrevious = false;

	// takes into account amount of items on press index
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
		header("Location: http://$DOMAIN/site/scripts/press_index.php");
		exit();
	}

	// get the press releases to display
	$allPressReleases = getAllPressReleasesForYearAndMonth($_GET['year'], $_GET['month'], true, true, 'pressDate', 'DESC', $offset, $numberToDisplay);

	// if there is no press releases for the month then get press releases for the previous month
	// and repeat until press releases is found
	while (sizeof($allPressReleases) == 0) {
		
		$_GET['month']--;
		if ($_GET['month'] == 0) {
			$_GET['month'] = 12;
			$_GET['year']--;
		}
		
		if ($_GET['year'] == date('Y') - 2) {
			break;
		}
		
		$allPressReleases = getAllPressReleasesForYearAndMonth($_GET['year'], $_GET['month'], true, true, 'pressDate', 'DESC', $offset, $numberToDisplay);
	}
	
	$textualMonth = date('F', mktime(0, 0, 0, $_GET['month'], 1, $_GET['year']));

	// check whether previous and next links should be displayed
	if ($page > 1) {
		$showPrevious = true;
	}

	$nextPressReleases = getAllPressReleasesForYearAndMonth($_GET['year'], $_GET['month'], true, true, 'pressDate', 'DESC', $numberToDisplay + $offset, $numberToDisplay);

	if (sizeof($nextPressReleases) > 0) {
		$showNext = true;
	}

	unset($nextPressReleases);
	
	$lgcl = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);

	$months = array();
	for ($i = 1; $i <= 12; $i++) {
		$i = str_pad($i, 2, '0', STR_PAD_LEFT);
		$monthName = date('F', mktime(0, 0, 0, $i, 1, $_GET['year']));
		$allPressReleasesForMonthLink = getAllPressReleasesForYearAndMonth($_GET['year'], $i, true, false, 'pressDate', 'DESC', 0, 1);

		if (sizeof($allPressReleasesForMonthLink) > 0 && $i != $_GET['month']) {
			$months[] = array(month => $i, monthname => $monthName);
		}
	}
	
	$breadcrumb = 'PressReleasesArchive';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Press release archive <?php if (sizeof($allPressReleases) > 0) { print ' | '. $textualMonth . ' ' . $_GET['year']; } ?> | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="press releases, news, <?php print $categoryViewing->name; ?>, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />	
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s latest press releases regarding <?php print $categoryViewing->name; ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Press release archive - <?php print $textualMonth . ' ' . $_GET['year'];?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s latest press release regarding <?php print $categoryViewing->name; ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="<?php print $PROTOCOL.$DOMAIN;?>/site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<h2>Press release archive for <?php print $_GET['year'];?></h2>
	
<?php
	if(sizeof($months) > 0) {
		print '<p class="first">';
		foreach($months as $month) {
?>
			<a href="http://<?php print $DOMAIN;?>/site/scripts/press_archive.php?year=<?php print $_GET['year'];?>&amp;month=<?php print $month[month];?>"><?php print $month[monthname]; ?> <?php print $_GET['year'];?></a> |
<?php
		}
		print '</p>';
	}
?>
		

	<form method="get" action="http://<?php print $DOMAIN;?>/site/scripts/press_archive.php" class="basic_form newsForm">
		<input type="hidden" value="01" name="month" />

		<p>
			<label for="year" >Available Years:
				<select id="year" name="year" class="select">
		<?php
		$tmpYear = date('Y');
		$allPressReleasesForYearLink = getAllPressReleasesForYearAndMonth($tmpYear, '00', true, true, 'pressDate', 'DESC', 0, 1);
		while (sizeof($allPressReleasesForYearLink) > 0) {
			
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
			$allPressReleasesForYearLink = getAllPressReleasesForYearAndMonth($tmpYear, '00', true, true, 'pressDate', 'DESC', 0, 1);
		}
		?>
				</select>
				<input type="submit" class="button" value="Go" />
			</label>
		</p>
	</form>

		
		<!-- PressReleases list -->
<?php
	if (sizeof($allPressReleases) == 0) {
?>
		<p class="first">There is no press releases in this archive, please see <a href="http://<?php print $DOMAIN;?>/site/scripts/press_index.php">Latest Press releases</a></p>
<?php
	} 
	else {
		foreach ($allPressReleases as $PressReleases) {
?>
		<div class="content_box">
<?php 
		if ($PressReleases->imageURL != "") { 
?>
			<a href="http://<?php print $DOMAIN;?>/site/scripts/press_article.php?pressReleaseID=<?php print $PressReleases->id;?>" >
				<img src="http://<?php print $DOMAIN;?>/images/<?php print $PressReleases->imageURL;?>" alt="<?php print getImageProperty($PressReleases->imageURL, 'altText'); ?> " />
			</a>
<?php 
		}
?>
			<h3>
				<a href="http://<?php print $DOMAIN;?>/site/scripts/press_article.php?pressReleaseID=<?php print $PressReleases->id;?>"><?php print $PressReleases->title;?></a>
			</h3>
			<p class="date">Published <?php print date("jS F Y", $PressReleases->pressDate);?> in <a href="http://<?php print $DOMAIN;?>/site/scripts/press_category.php?categoryID=<?php print $currentCategory->id;?>"><?php print $currentCategory->name; ?></a></p>
			<p><?php print $PressReleases->summary;?></p>
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
				print '<a href="http://'.$DOMAIN.'/site/scripts/press_archive.php?page='. $previous .'&month=' . $month .'&year=' . $year . '">&laquo; Previous</a>';
			}
			if ($showNext && $showPrevious) {
				print ' | ';
			}
			if ($showNext) {
				print '<a href="http://'.$DOMAIN.'/site/scripts/press_archive.php?page='. $next .'&month=' . $month .'&year=' . $year . '">Next &raquo;</a>';
			}
?>
	</p>
<?php
		}
?>

	<p class="rssfeed"><a href="http://<?php print $DOMAIN;?>/site/scripts/pressRss.php" target="_blank">Press release RSS</a></p>
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>