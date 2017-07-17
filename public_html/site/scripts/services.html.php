<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($currentCategory->name); ?> services | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print encodeHtml($parent->name) . ', '; } ?><?php foreach ($dirTree as $parent) { print encodeHtml($parent->name) . ', '; } ?><?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s Services availiable within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s Services availiable within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s Services availiable within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (sizeof($allRelatatedServices) > 0) {
?>
	<h2>Available services</h2>
	<ul class="list icons services">
<?php
		foreach ($allRelatatedServices as $service) {
?>
		<li><a href="<?php print getSiteRootURL() . buildAZServiceURL($service->id); ?>"><?php print encodeHtml($service->title); ?></a></li>
<?php
		}
?>
	</ul>
<?php
	}
	if (sizeof($categories) > 0) {
?>

	<h2><?php print encodeHtml($parent->name); ?> categories</h2>
	<ul class="list icons services">
<?php
			foreach ($categories as $subCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildAZServicesCategoryURL($subCat->id); ?>"><?php print encodeHtml($subCat->name); ?></a> </li>
<?php
			}
			print '</ul>';
?>
			
<?php
	}

?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>