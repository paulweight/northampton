<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="events, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s events organised by category" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> events" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s index of events organised by category" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
		foreach ($rootCategories as $rootCat) {
			$relCats = filterCategoriesInUse($lgclList->getChildCategories($rootCat->id), EVENTS_APPLIED_CATEGORIES_TABLE, true);
?>

	<h2><a href="<?php print getSiteRootURL() . buildEventsURL($rootCat->id); ?>"><?php print encodeHtml($rootCat->name); ?></a></h2>
<?php
			if (sizeof($relCats) > 0) {
				print '<ul class="list">';
				foreach ($relCats as $cat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildEventsURL($cat->id); ?>"><?php print encodeHtml($cat->name); ?></a></li>
<?php
				}
				print '</ul>';
			}
		}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>