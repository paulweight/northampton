<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="documents, consultations, policies, information, plans, performance, objectives, facts and figures, strategy, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>s index of available documents and pages organised by category" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> online information" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>s index of available documents and pages organised by category" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<p>Browse the listed information and services below.</p>

<?php
	foreach ($rootCategories as $rootCat) {
		$relCats = filterCategoriesInUse($lgclList->getChildCategories($rootCat->id), DOCUMENTS_APPLIED_CATEGORIES_TABLE, true);
		$splitArray = splitArray($relCats);
?>		

	<h3><a href="<?php print getSiteRootURL() . buildDocumentsCategoryURL($rootCat->id); ?>"><?php print encodeHtml($rootCat->name); ?></a></h3>
	
<?php
		if (sizeof($relCats) > 0) {
			print '<ul class="list icons documents">';
			foreach ($relCats as $cat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildDocumentsCategoryURL($cat->id); ?>"><?php print encodeHtml($cat->name); ?></a></li>
<?php
			}
			print '</ul><div class="clear"></div>';
		}
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>