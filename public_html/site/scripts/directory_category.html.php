<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($category->title); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="directory,<?php print encodeHtml($category->title) . ',' . encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s directories organised by category" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s directories organised by category" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($useDirectoryCategories && $categoryInfo->id != '-1') {
		if ($categoryInfo->imageFilename != '') {
?>
    <a id="brand" href="<?php print getSiteRootURL() . buildDirectoryCategoryURL($directory->id, (int) $_REQUEST['directoryCategoryID']); ?>">
	    <img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($categoryInfo->imageFilename); ?>" alt="<?php print encodeHtml(getImageProperty($categoryInfo->imageFilename, 'altText')); ?>" />
	</a>
	
<?php
		}
?>
	<div class="byEditor article">
		<?php print $categoryInfo->description; ?>
	</div>
<?php
    }

    if (sizeof($categoryAdverts) > 0) {
?>

<?php
		foreach ($categoryAdverts as $categoryAdvert) {
?>
		<div>
			<a href="<?php print encodeHtml($categoryAdvert->url) ?>">
				<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($categoryAdvert->imageFilename); ?>" alt="<?php print encodeHtml(getImageProperty($categoryAdvert->imageFilename, 'altText')); ?> "  />
			</a>
		</div>
<?php
		}
?>

<?php
    }

    if (sizeof($subCats) > 0) {
?>
    	<div class="cate_info">
    		<h2>Categories in <?php print ($useDirectoryCategories) ? encodeHtml($category->title) : encodeHtml($category->name); ?></h2>

    		<ul class="list">
<?php
            foreach ($subCats as $subCat) {
?>
    			<li<?php print $liStyle; ?>><a href="<?php print buildDirectoryCategoryURL($directory->id, $subCat->id, $categoryInfo->id); ?>"><?php print encodeHtml($subCat->title); ?></a></li>
<?php
            }
?>
    		</ul>
        </div>
<?php
    }

    if (sizeof($records) > 0) {
?>
    	<div class="doc_info">
    		<h2>Records in <?php print ($useDirectoryCategories) ? encodeHtml($category->title) : encodeHtml($category->name); ?></h2>
<?php
        if (sizeof($records) > 0) {
?>
    		<ul class="list">
<?php
            foreach ($records as $record) {
?>
    			<li<?php print $liStyle; ?>><a href="<?php print buildDirectoryRecordURL($record->id, $category->id, $categoryInfo->id); ?>"><?php print encodeHtml($record->title); ?></a></li>
<?php
            }
?>
    		</ul>
<?php
        }
?>
        </div>
<?php
    }
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php");?>
