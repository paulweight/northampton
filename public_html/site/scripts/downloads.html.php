<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print encodeHtml($parent->name) . ', '; } ?><?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>s Files and documents available for download within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> downloads<?php foreach ($dirTree as $parent) { ?> | <?php print encodeHtml($parent->name); } ?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>s Files and documents available for download within the following categories<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
	
	<p>The following documents are available for download. Documents in PDF format can be read using <a href="http://www.adobe.com/products/acrobat/readstep2.html">Adobe Reader</a>.</p>
	<p>MS Word and Powerpoint documents can be read by using their respective applications or any alternatives.</p>
			
<?php
	if (sizeof($allDownloads) > 0) {
?>
	<h2>Available downloads</h2>
<?php
		foreach ($allDownloads as $downloadItem) {
			$allFiles = getAllDownloadFilesForDownload($downloadItem->id, array('live' => true, 'approved' => false));
			
			if (sizeof($allFiles) > 0) {
?>

	<h3><a href="<?php print getSiteRootURL() . buildDownloadsURL(-1, -1, $downloadItem->id); ?>"><?php print encodeHtml($downloadItem->title); ?></a></h3>
	<ul>
<?php
	if (!empty($download->description)) {
?>
		<li><?php print nl2br(encodeHtml($download->description)); ?></li>
<?php
	}
?>

<?php
				foreach ($allFiles as $fileItem) {
					if ($fileItem->url == "") {
						$extension = $fileItem->getFilenameExtension();
						$path = getSiteRootURL() . buildDownloadsURL($_REQUEST['categoryID'], $fileItem->id, $downloadItem->id, false);
					}
					else {
						$extension = $fileItem->getURLExtension();
						$path = encodeHtml($fileItem->url);
					}
?>
		<li><a href="<?php print $path; ?>"><?php print encodeHtml($fileItem->title); ?></a>
		<img src="<?php print getStaticContentRootURL() . $fileItem->getFileIcon(); ?>" alt=" " /> (<?php print encodeHtml($extension); ?>)</li>
		<li>Size: <?php print $fileItem->getHumanReadableSize();?></li>
<?php
				}
?>
	</ul>

<?php
			}

		}
	}

	if (sizeof($categories) > 0) {
?>

	<h2><?php print encodeHtml($parent->name); ?> categories</h2>
			<ul class="list">
<?php
			foreach ($categories as $subCat) {
?>
				<li><a href="<?php print getSiteRootURL() . buildDownloadsURL($subCat->id) ?>"><?php print encodeHtml($subCat->name); ?></a> </li>
<?php
			}
?>
			</ul>
<?php
	}
?>

	<p><a href="<?php print getSiteRootURL() . buildCategoryRSSURL("downloads", $_GET['categoryID']); ?>"><img src="<?php print getStaticContentRootURL(); ?>/site/images/xml.gif" alt=" " /> <?php print encodeHtml(METADATA_GENERIC_NAME . ' ' . $currentCategory->name); ?>  feed</a></p>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>