<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("egov/JaduCL.php");
	include_once("directoryBuilder/JaduDirectories.php");
	include_once("../includes/lib.php");

	$dirTree = array();

	if (isset($_GET['categoryID'])) {

		$allDirectories = getAllDirectories($adminID = -1, $live = 1, $_GET['categoryID']);
		$splitDirectories = splitArray($allDirectories);

		$bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$allCategories = $bespokeCategoryList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, DIRECTORY_APPLIED_CATEGORIES_TABLE, true);

		$currentCategory = $bespokeCategoryList->getCategory($_GET['categoryID']);
		$dirTree = $bespokeCategoryList->getFullPath($_GET['categoryID']);
		$leftCategoryID = $_GET['categoryID'];
	}
	else {
	    $bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
    	$allRootCategories = $bespokeCategoryList->getTopLevelCategories();
    	$categories = filterCategoriesInUse($allRootCategories, DIRECTORY_APPLIED_CATEGORIES_TABLE, true);
	}

	$splitCategories = splitArray($categories);

	$breadcrumb = 'directoriesCat';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Directories</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print $parent->name . ', '; } ?><?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> directories" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

		<!-- Categories -->
<?php
	if (sizeof($splitCategories['left']) > 0 || sizeof ($splitCategories['right']) > 0) {
?>
		<div class="cate_info">
			<h2>
<?php
            if (isset($_GET['categoryID'])) {
                print "Categories in $parent->name";
            }
            else {
                print "Directories";
            }
?>
			</h2>
<?php
		if (sizeof($splitCategories['left']) > 0) {
			print '<ul class="info_left list">';
			foreach ($splitCategories['left'] as $subCat) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/directories_index.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a></li>
<?php
			}
			print '</ul>';
		}

		if (sizeof($splitCategories['right']) > 0) {
			print '<ul class="info_right list">';
			foreach ($splitCategories['right'] as $subCat) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/directories_index.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a></li>
<?php
			}
			print '</ul>';
		}
?>
			<div class="clear"></div>
		</div>
<?php
	}

    if (sizeof($splitDirectories['left']) > 0 || sizeof($splitDirectories['right']) > 0) {
?>
	<div class="doc_info">
		<h2>Directories in <?php print $parent->name; ?></h2>

<?php
	if (sizeof($splitDirectories['left']) > 0) {
		print '<ul class="info_left list">';
		if (sizeof($splitDirectories['left']) > 0) {
			foreach ($splitDirectories['left'] as $directory) {					
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/directory_home.php?categoryID=<?php print $_GET['categoryID']; ?>&amp;directoryID=<?php print $directory->id; ?>"><?php print $directory->name; ?></a></li>
<?php
			}
		}
		print '</ul>';
	}
	
	if (sizeof($splitDirectories['right']) > 0) {
		print '<ul class="info_right list">';
		if (sizeof($splitDirectories['right']) > 0) {
			foreach ($splitDirectories['right'] as $directory) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/directory_home.php?categoryID=<?php print $_GET['categoryID']; ?>&amp;directoryID=<?php print $directory->id; ?>"><?php print $directory->name; ?></a></li>
<?php
			}
		}
		print '</ul>';
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
<?php include("../includes/closing.php"); ?>