<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print encodeHtml($parent->name) . ', '; } ?><?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> blogs" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

		<!-- Categories -->
<?php
	 {
?>

		
<?php
		if (sizeof($categories) > 0) {
			print '<ul class="list icons blogs">';
			foreach ($categories as $subCat) {
?>
				<li><a href="<?php print buildBlogURL($subCat->id); ?>"><?php print encodeHtml($subCat->name); ?></a></li>
<?php
			}
			print '</ul>';
		}
?>


<?php
	}

    if (sizeof($allBlogs) > 0) {
?>

	<h2 class="topTitle">Blogs in <?php print encodeHtml($parent->name); ?></h2>

<?php
		print '<ul class="list icons blogs">';
		if (sizeof($allBlogs) > 0) {
			foreach ($allBlogs as $blog) {
?>
				<li><a href="<?php print buildBlogURL(-1, $blog->id); ?>"><?php print encodeHtml($blog->title); ?></a></li>
<?php
			}
		}
		print '</ul>';
?>

<?php
    }

	if (sizeof($categories) == 0 && sizeof($allBlogs) == 0) {
?>
	<h2>Sorry, this blog is no longer available</h2>

<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>