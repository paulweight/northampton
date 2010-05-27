<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("websections/JaduPressReleases.php");
	include_once("JaduAppliedCategories.php");
	include_once("websections/JaduFeedManager.php");
	
	$skipFirst = false;
	
	$lgcl = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$usedTopLevelCats = createItemIndex(PRESS_RELEASES_CATEGORIES_TABLE, $lgcl);
	$PressReleasesWithCats = sortAndFilterCategorisedPressReleases ($usedTopLevelCats);
	
	//	This little lot checks whether the topPressReleases for the category should be the 
	//	full press release top press release, or just the first for the category as a whole.
	$topPressReleases = getTopPressReleases(true, true);
	if ($topPressReleases == -1) {
		$topPressReleases = getLastPressReleases(true, true);
	}
	
	if (isset($_GET['categoryID']) && sizeof($PressReleasesWithCats[$_GET['categoryID']]) > 0) {
		$categoryViewing = $lgcl->getCategory($_GET['categoryID']);
		
		if ($cat->id != $_GET['categoryID']) {
			$topPressReleases = $PressReleasesWithCats[$_GET['categoryID']][0];
			$skipFirst = true;
		}
	}
	else {
		header("Location: http://$DOMAIN/site/scripts/press_index.php");
		exit();
	}

	$breadcrumb = 'PressReleasesCats';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print "$categoryViewing->name "; ?> press releases | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<link rel="alternate" type="application/rss+xml" title="RSS" href="http://<?php print $DOMAIN;?>/site/scripts/pressRss.php" />

	<meta name="Keywords" content="press releases, news, <?php print $categoryViewing->name; ?>, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />	
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s latest press releases regarding <?php print $categoryViewing->name; ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Latest <?php print "$categoryViewing->name "; ?> press releases" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s latest press releases regarding <?php print $categoryViewing->name; ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
		if ($topPressReleases == -1) {
?>

		<h2>Sorry, there is currently no press releases</h2>

<?php
		} 
		else {
?>
		<!-- Top story -->
		<div class="lead">
			<h2>
				<a href="http://<?php print $DOMAIN;?>/site/scripts/press_article.php?pressReleaseID=<?php print $topPressReleases->id;?>" ><?php print $topPressReleases->title;?></a>
			</h2>
<?php 
			if ($topPressReleases->imageURL != "") {
?>
				<a href="http://<?php print $DOMAIN;?>/site/scripts/press_article.php?pressReleaseID=<?php print $topPressReleases->id;?>" 
					<img src="http://<?php print $DOMAIN;?>/images/<?php print $topPressReleases->imageURL;?>" alt="<?php print getImageProperty($topPressReleases->imageURL, 'altText'); ?> " class="contentimage" />
				</a>
<?php 
			}
?>
				<p class="date">Published <?php print date("jS F Y", $topPressReleases->pressDate);?></p>
				<p><?php print $topPressReleases->summary;?></p>
			</div>
			<!-- END top story -->
	

<?php	
		foreach ($PressReleasesWithCats[$categoryID] as $index => $PressReleases) {
			if ($skipFirst && $index == 0) {
			}
			else {
?>

			<div class="content_box">
				<h3>
					<a href="http://<?php print $DOMAIN;?>/site/scripts/press_article.php?pressReleaseID=<?php print $PressReleases->id;?>" ><?php print $PressReleases->title;?></a>
				</h3>
<?php 
				if ($PressReleases->imageURL != "") { 
?>
					<a href="http://<?php print $DOMAIN;?>/site/scripts/press_article.php?pressReleaseID=<?php print $PressReleases->id;?>" >
						<img src="http://<?php print $DOMAIN;?>/images/<?php print $PressReleases->imageURL;?>" alt="<?php print getImageProperty($PressReleases->imageURL, 'altText'); ?> " class="contentimage" />
					</a>
<?php 
				}
?>
				<p class="date">Published <?php print date("jS F Y", $PressReleases->pressDate);?></p>
				<p><?php print $PressReleases->summary;?></p>
				<div class="clear"></div>
			</div>	
<?php
			}
		}
?>

	<p class="rssfeed"><a href="http://<?php print $DOMAIN;?>/site/scripts/pressRss.php" target="_blank">Press release RSS</a></p>
	<p class="first"><a href="http://<?php print $DOMAIN; ?>/site/scripts/press_archive.php">Press release archive</a></p>
<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>