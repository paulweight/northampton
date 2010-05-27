<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduPressReleases.php");
	include_once("JaduCategories.php");
	include_once("JaduAppliedCategories.php");
	include_once("websections/JaduFeedManager.php");

	$NUM_OF_PRESS_RELEASE_INDEX_ITEMS = 12;
/*	$topPressReleases = getTopPressReleases(true, true);
	if ($topPressReleases == -1) {
		$topPressReleases = getLastPressReleases(true, true);
	}

	$bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$usedTopLevelCats = createItemIndex(PRESS_RELEASES_CATEGORIES_TABLE, $bespokeCategoryList);
	$PressReleasesWithCats = sortAndFilterCategorisedPressReleases($usedTopLevelCats);*/

	$topPressReleases = getTopPressReleases(true, true);
	if ($topPressReleases == -1) {
		$topPressReleases = getLastPressReleases(true, true);
	}

	$bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$usedTopLevelCats = createItemIndex(PRESS_RELEASES_CATEGORIES_TABLE, $bespokeCategoryList);
	$PressReleasesWithCats = sortAndFilterCategorisedPressReleases($usedTopLevelCats);
	
	$allPressReleases = getAllPressReleasesByDateLimited($NUM_OF_PRESS_RELEASE_INDEX_ITEMS, true, true);
	
	// ensure that item is not the top news.
	$latestPressRelease = array();
	$repeated = false;
	
	foreach($allPressReleases as $pressRelease) {
		if($pressRelease->id == $topPressReleases->id) {
			$repeated = true;
		}
	}

	if($repeated) {
		$allPressReleases = getAllPressReleasesByDateLimited($NUM_OF_PRESS_RELEASE_INDEX_ITEMS + 1, true, true);
		foreach($allPressReleases as $pressRelease) {
			if($pressRelease->id != $topPressReleases->id) {
				$latestPressRelease [] = $pressRelease;
			}
		}
	}
	else {
		$latestPressRelease = $allPressReleases;
	}
		
	$breadcrumb = 'PressReleasesIndex';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Press releases | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<link rel="alternate" type="application/rss+xml" title="RSS" href="http://<?php print $DOMAIN;?>/site/scripts/pressRss.php" />

	<meta name="Keywords" content="press releases, news, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />	
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s latest press releases directory" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> press releases" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s latest press releases directory" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="<?php print $PROTOCOL.$DOMAIN;?>/site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($topPressReleases == -1) {
?>
	<h2>Sorry, there are currently no press releases</h2>
<?php
	} 
	else {
?>
	<div class="lead">
<?php 
	if ($topPressReleases->imageURL != "") { 
?>
		<a href="http://<?php print $DOMAIN;?>/site/scripts/press_article.php?pressReleaseID=<?php print $topPressReleases->id;?>">
			<img src="http://<?php print $DOMAIN;?>/images/<?php print $topPressReleases->imageURL;?>" alt="<?php print getImageProperty($topPressReleases->imageURL, 'altText'); ?> " class="contentimage" />
		</a>
<?php 
	} 
	
	$categoryID = getFirstCategoryIDForItemOfType (PRESS_RELEASES_CATEGORIES_TABLE, $topPressReleases->id, BESPOKE_CATEGORY_LIST_NAME);
	$bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$currentCategory = $bespokeCategoryList->getCategory($categoryID);	
?>
		<h2><a href="http://<?php print $DOMAIN;?>/site/scripts/press_article.php?pressReleaseID=<?php print $topPressReleases->id;?>"><?php print htmlentities($topPressReleases->title);?></a></h2>
		<p class="date">Published <?php print date("jS F Y", $topPressReleases->pressDate);?> in <a href="http://<?php print $DOMAIN;?>/site/scripts/press_category.php?categoryID=<?php print $currentCategory->id;?>"><?php print $currentCategory->name; ?></a></p>
		<p><?php print $topPressReleases->summary;?></p>
	</div>

		<!-- PressReleases by catagory -->	
<?php
		/*$PressReleasesWithCatsKeys = array_keys($PressReleasesWithCats);
		foreach ($PressReleasesWithCatsKeys as $index => $topCat) {
			$cat = $bespokeCategoryList->getCategory($topCat);
			$PressReleases = $PressReleasesWithCats[$topCat][0];
		*/
		foreach ($latestPressRelease as $pressItem) {
			$categoryID = getFirstCategoryIDForItemOfType (PRESS_RELEASES_CATEGORIES_TABLE, $pressItem->id, BESPOKE_CATEGORY_LIST_NAME);
			$bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
			$currentCategory = $bespokeCategoryList->getCategory($categoryID);
?>
	<div class="content_box">
	<?php if ($pressItem->imageURL != "") { ?>
		<a href="http://<?php print $DOMAIN;?>/site/scripts/press_article.php?pressReleasesID=<?php print $pressItem->id;?>">
		<img src="http://<?php print $DOMAIN;?>/images/<?php print $pressItem->imageURL;?>" alt="<?php print getImageProperty($pressItem->imageURL, 'altText'); ?>" />
		</a>
	<?php } ?>
		<h3>
			<a href="http://<?php print $DOMAIN;?>/site/scripts/press_article.php?pressReleasesID=<?php print $pressItem->id;?>"><?php print htmlentities($pressItem->title);?></a>
		</h3>
		<p class="date">Published <?php print date("jS F Y", $pressItem->pressDate);?> in <a href="http://<?php print $DOMAIN;?>/site/scripts/press_category.php?categoryID=<?php print $currentCategory->id;?>"><?php print $currentCategory->name; ?></a></p>
		<p><?php print $pressItem->summary;?></p>
	</div>	
	<?php
		}
	?>

	<p class="rssfeed"><a href="http://<?php print $DOMAIN;?>/site/scripts/pressRss.php" target="_blank">Press release RSS</a></p>
	<p class="first"><a href="http://<?php print $DOMAIN; ?>/site/scripts/press_archive.php">Press release archive</a></p>

<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>