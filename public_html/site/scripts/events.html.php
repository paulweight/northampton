<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($currentCategory->name); ?> events - <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print encodeHtml($parent->name) . ', '; } ?><?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s index of events organised within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (sizeof($allEvents > 0)) {
?>
	<h2><?php print encodeHtml($parent->name); ?> related events</h2>
<?php
			print '<ul class="list">';
			foreach ($allEvents as $event) {
?>
		<li><a href="<?php print getSiteRootURL() . buildEventsURL(-1, '', $event->id); ?>"><?php print encodeHtml($event->title); ?></a></li>
<?php
			}
			print '</ul>';
?>

<?php
	}

	if (sizeof($categories > 0)) {
?>
		

	<h2>Categories in <?php print encodeHtml($parent->name); ?></h2>
<?php
			print '<ul class="list">';
			foreach ($categories as $subCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildEventsURL($subCat->id) ?>"><?php print encodeHtml($subCat->name); ?></a></li>
<?php
			}
			print '</ul>';
?>		


<?php
	}
?>

	<p><a href="<?php print getSiteRootURL() . buildCategoryRSSURL("events", $_GET['categoryID']); ?>" target="_blank"><img src="<?php print getStaticContentRootURL(); ?>/site/images/xml.gif" alt=" " /> <?php print encodeHtml(METADATA_GENERIC_NAME . ' ' . $currentCategory->name); ?>  feed</a></p>
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
