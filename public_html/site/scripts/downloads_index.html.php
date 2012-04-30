<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="downloads, download, documents, pdf, word, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Files and documents available for download" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Document and File Downloads" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Files and documents available for download" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<p>The following documents are available for download. Documents in PDF format can be read using <a href="http://www.adobe.com/products/acrobat/readstep2.html">Adobe Reader</a>.</p>
	<p>MS Word and Powerpoint documents can be read by using their respective applications or any alternatives.</p>                                                  

	<!-- The ten most used downloads listed here. -->
	<h2>Popular downloads</h2>
	<ul>
<?php 
		foreach($topDownloads as $item) {
			if ($item->url == "") {
				 $extension = $item->getFilenameExtension();
			}
			else {
				 $extension = $item->getURLExtension();
			}
?>
			<li><a href="<?php print getSiteRootURL() . buildDownloadsURL(-1, $item->id); ?>"><?php print encodeHtml($item->title);?></a> <img src="<?php print getStaticContentRootURL() . $item->getFileIcon(); ?>" alt="<?php print encodeHtml($extension); ?>" /> <span>(<?php print $extension;?>)</span></li>
<?php
		}
?>
	</ul>


<?php
	//	must do here to ensure not using left nav version.
	
	$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
	$allRootCategories = $lgclList->getTopLevelCategories();
	$rootCategories = filterCategoriesInUse($allRootCategories, DOWNLOADS_APPLIED_CATEGORIES_TABLE, true);

	foreach ($rootCategories as $rootCat) {
		$subCats = filterCategoriesInUse($lgclList->getChildCategories($rootCat->id), DOWNLOADS_APPLIED_CATEGORIES_TABLE, true);
		$splitArray = splitArray($subCats);
?>	
                    
		<div class="cate_info">
			<h3><a href="<?php print getSiteRootURL() . buildDownloadsURL($rootCat->id); ?>"><?php print encodeHtml($rootCat->name); ?></a></h3>                        
<?php
		if (sizeof($subCats) > 0) {
			print '<ul class="list">';
			foreach ($subCats as $subCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildDownloadsURL($subCat->id); ?>"><?php print encodeHtml($subCat->name); ?></a></li>
<?php
			}
			print '</ul>';
		}
?>
		</div>
<?php
	}

	if (sizeof($topDownloads) > 0) {
?>
        

<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>