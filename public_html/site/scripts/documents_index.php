<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");

	include("../includes/lib.php");
	
	$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$allRootCategories = $lgclList->getTopLevelCategories();
	$rootCategories = filterCategoriesInUse($allRootCategories, DOCUMENTS_APPLIED_CATEGORIES_TABLE, true);

	$breadcrumb = 'documentsIndex';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Council information | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="documents, consultations, policies, information, plans, performance, objectives, facts and figures, strategy, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of available documents and pages organised by category" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> online information" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of available documents and pages organised by category" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

		<p class="first">Browse the listed information and services below.</p>

<?php
		foreach ($rootCategories as $rootCat) {
			$relCats = filterCategoriesInUse($lgclList->getChildCategories($rootCat->id), DOCUMENTS_APPLIED_CATEGORIES_TABLE, true);
			$splitArray = splitArray($relCats);
?>		
		<div class="cate_info">
			<h2><a href="http://<?php print $DOMAIN; ?>/site/scripts/documents.php?categoryID=<?php print $rootCat->id;?>"><?php print $rootCat->name; ?></a></h2>
<?php
				if (sizeof($splitArray['left']) > 0) {
					print '<ul class="info_left">';
					foreach ($splitArray['left'] as $cat) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/documents.php?categoryID=<?php print $cat->id;?>"><?php print $cat->name; ?></a></li>
<?php
					}
					print '</ul>';
				}

				if (sizeof($splitArray['right']) > 0) {
					print '<ul class="info_right">';
					foreach ($splitArray['right'] as $cat) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/documents.php?categoryID=<?php print $cat->id;?>"><?php print $cat->name; ?></a></li>
<?php
					}
					print '</ul>';
				}
?>
			<br class="clear" />
		</div>
<?php
		}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>