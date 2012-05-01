<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="podcast,<?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Podcasts" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Podcasts" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Podcasts" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
<?php
	$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
	$allRootCategories = $lgclList->getTopLevelCategories();
	$rootCategories = filterCategoriesInUse($allRootCategories, MULTIMEDIA_PODCAST_APPLIED_CATEGORIES_TABLE, true);

	foreach ($rootCategories as $rootCat) {
		$subCats = filterCategoriesInUse($lgclList->getChildCategories($rootCat->id), MULTIMEDIA_PODCAST_APPLIED_CATEGORIES_TABLE, true);
?>	
                    
		<div class="cate_info">
			<h2><a href="<?php print getSiteRootURL() . buildMultimediaPodcastsURL($rootCat->id); ?>"><?php print encodeHtml($rootCat->name); ?></a></h2>                        
<?php
		if (sizeof($subCats) > 0) {
			print '<ul class="list icons podcasts">';
			foreach ($subCats as $subCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildMultimediaPodcastsURL($subCat->id); ?>"><?php print encodeHtml($subCat->name); ?></a></li>
<?php
			}
			print '</ul>';
		}
?>
		</div>
<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>