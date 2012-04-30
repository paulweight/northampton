<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="directory,information,<?php foreach ($dirTree as $parent) { print encodeHtml($parent->name) . ', '; } ?><?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> directories" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> directories" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> directories" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
    if (sizeof($allDirectories) > 0) {
?>

	<h2>Directories in <?php print encodeHtml($parent->name); ?></h2>

<?php
	if (sizeof($allDirectories) > 0) {
		print '<ul class="list">';
			foreach ($allDirectories as $directory) {					
?>
				<li><a href="<?php print buildDirectoriesURL(-1, $directory->id); ?>"><?php print encodeHtml($directory->name); ?></a></li>
<?php
			}
		print '</ul>';
	}
?>


<?php
    }
    
	if (sizeof($categories) > 0) {
?>
		<div class="cate_info">
			<h2>
<?php
            if (isset($_GET['categoryID'])) {
                print 'Categories in ' . encodeHtml($parent->name);
            }
            else {
                print "Directories";
            }
?>
			</h2>
<?php
		if (sizeof($categories) > 0) {
			print '<ul class="list">';
			foreach ($categories as $subCat) {
?>
				<li><a href="<?php print buildDirectoriesURL($subCat->id); ?>"><?php print encodeHtml($subCat->name); ?></a></li>
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