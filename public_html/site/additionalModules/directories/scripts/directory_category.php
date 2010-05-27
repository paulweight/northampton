<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduImages.php");
	include_once("directoryBuilder/JaduDirectories.php");
	include_once("directoryBuilder/JaduDirectoryEntries.php");
	include_once("directoryBuilder/JaduDirectoryCategoryInformation.php");
	include_once("directoryBuilder/JaduDirectoryCategoryTree.php");
	include_once("../includes/lib.php");

	if (!isset($_REQUEST['directoryCategoryID']) && !isset($_REQUEST['categoryID']) 
		&& !isset($_REQUEST['categoryInfoID'])) {
	    header("Location: http://$DOMAIN/site/index.php");
        exit();
	}

	$records = array();
	$categoryAdverts = array();
	$subCats = array();
	$useDirectoryCategories = true;
	$categoryInfo = new DirectoryCategoryInformation();
	$liStyle = '';

	if (isset($_REQUEST['directoryCategoryID']) && is_numeric($_REQUEST['directoryCategoryID'])) {
	    $category = getDirectoryCategory($_REQUEST['directoryCategoryID']);
		$directory = getDirectory($category->directoryID);
		$categoryAdverts = getDirectoryCategoryAdverts($category->id);

		if (!isset($_REQUEST['categoryInfoID']) || $_REQUEST['categoryInfoID'] == -1) {
			$categoryInfo = getDirectoryCategoryInformationForCategory($category->id);

			if ($categoryInfo->id != '-1' && !empty($categoryInfo->bulletImageFilename)) {
				$liStyle = sprintf(' style="background-image: url(http://%s/images/%s)"', DOMAIN, $categoryInfo->bulletImageFilename);
			}
		}
	}
	elseif (isset($_REQUEST['categoryID']) && is_numeric($_REQUEST['categoryID']) && 
	    $_REQUEST['categoryID'] > 0 && !isset($_REQUEST['directoryCategoryID'])) {
		$useDirectoryCategories = false;
		$categoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$category = $categoryList->getCategory($_REQUEST['categoryID']);
		$directory = getDirectory($_REQUEST['directoryID']);
	}

	if (isset($_REQUEST['categoryInfoID']) && is_numeric($_REQUEST['categoryInfoID'])) {
		$categoryInfo = getDirectoryCategoryInformation($_REQUEST['categoryInfoID']);

		if ($categoryInfo->id != '-1' && !empty($categoryInfo->bulletImageFilename)) {
			$liStyle = sprintf(' style="background-image: url(http://%s/images/%s)"', DOMAIN, $categoryInfo->bulletImageFilename);
		}
	}

	if ($useDirectoryCategories) {
		$records = getAllDirectoryEntries ($directory->id, $live = 1, $category->id);
		$subCats = getDirectoryCategories($category->id, $directory->id);
		$splitSubCats = splitArray($subCats);		
	}
	else {
		$records = getAllDirectoryEntriesInCategory ($directory->id, $category->id, true);
	}

    $splitRecords = splitArray($records);

	$dirTree = array_reverse(getDirectoryCategoryAncestors($category->id));

    $breadcrumb = 'directoryCategory';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - <?php print $category->title; ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body id="zone">
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($useDirectoryCategories && $categoryInfo->id != '-1') {
?>
    <a id="brand" href="http://<?php print $DOMAIN; ?>/site/scripts/directory_category.php?directoryCategoryID=<?php print $_REQUEST['directoryCategoryID']; ?>">
	    <img src="http://<?php print $DOMAIN . '/images/' . $categoryInfo->imageFilename; ?>" alt="<?php print getImageProperty($categoryInfo->imageFilename, 'altText'); ?>" />
	</a>

	<div class="byEditor">
		<?php print $categoryInfo->description; ?>
	</div>
<?php

?>
	<div class="zoneAdverts">

<?php
		foreach ($categoryAdverts as $categoryAdvert) {
?>
		<div>
			<a href="<?php print htmlentities($categoryAdvert->url) ?>">
				<img src="http://<?php print $DOMAIN;?>/images/<?php print $categoryAdvert->imageFilename; ?>" alt="<?php print getImageProperty($categoryAdvert->imageFilename, 'altText'); ?> "  />
			</a>
		</div>
<?php
		}
?>
	</div>

<?php
	}

    if (sizeof($splitSubCats['left']) > 0 || sizeof($splitSubCats['right']) > 0) {
?>
    	<div class="cate_info">
    		<h2>Categories in <?php print ($useDirectoryCategories) ? htmlentities($category->title) : $category->name; ?></h2>
<?php
        if (sizeof($splitSubCats['left']) > 0) {
?>
    		<ul class="info_left list">
<?php
            foreach ($splitSubCats['left'] as $subCat) {
?>
    			<li<?php print $liStyle; ?>><a href="http://<?php print $DOMAIN; ?>/site/scripts/directory_category.php?categoryInfoID=<?php print $categoryInfo->id; ?>&amp;directoryCategoryID=<?php print $subCat->id; ?>"><?php print htmlentities($subCat->title); ?></a></li>
<?php
            }
?>
    		</ul>
<?php
        }

        if (sizeof($splitSubCats['right']) > 0) {
?>
    		<ul class="info_right list">
<?php
            foreach ($splitSubCats['right'] as $subCat) {
?>
    			<li<?php print $liStyle; ?>><a href="http://<?php print $DOMAIN; ?>/site/scripts/directory_category.php?categoryInfoID=<?php print $categoryInfo->id; ?>&amp;directoryCategoryID=<?php print $subCat->id; ?>"><?php print htmlentities($subCat->title); ?></a></li>
<?php
            }
?>
    		</ul>
<?php
        }
?>
			<div class="clear"></div>
        </div>
<?php
    }

    if (sizeof($splitRecords['left']) > 0 || sizeof($splitRecords['right']) > 0) {
?>
    	<div class="doc_info">
    		<h2>Records in <?php print ($useDirectoryCategories) ? htmlentities($category->title) : $category->name; ?></h2>
<?php
        if (sizeof($splitRecords['left']) > 0) {
?>
    		<ul class="info_left list">
<?php
            foreach ($splitRecords['left'] as $record) {
?>
    			<li<?php print $liStyle; ?>><a href="http://<?php print $DOMAIN; ?>/site/scripts/directory_record.php?categoryInfoID=<?php print $categoryInfo->id; ?>&amp;directoryCategoryID=<?php print $category->id; ?>&amp;recordID=<?php print $record->id; ?>"><?php print htmlentities($record->title); ?></a></li>
<?php
            }
?>
    		</ul>
<?php
        }

        if (sizeof($splitRecords['right']) > 0) {
?>
    		<ul class="info_right list">
<?php
            foreach ($splitRecords['right'] as $record) {
?>
    			<li<?php print $liStyle; ?>><a href="http://<?php print $DOMAIN; ?>/site/scripts/directory_record.php?categoryInfoID=<?php print $categoryInfo->id; ?>&amp;directoryCategoryID=<?php print $category->id; ?>&amp;recordID=<?php print $record->id; ?>"><?php print htmlentities($record->title); ?></a></li>
<?php
            }
?>
    		</ul>
<?php
        }
?>
			<div class="clear"></div>
        </div>
<?php
    }
?>

    <!-- The Contact box -->
    <?php include("../includes/contactbox.php"); ?>
    <!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php");?>