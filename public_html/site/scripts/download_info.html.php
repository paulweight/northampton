<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="downloads, download, documents, pdf, word, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> download - <?php print encodeHtml($download->title);?><?php foreach ($dirTree as $parent) { ?> | <?php print encodeHtml($parent->name); ?><?php } ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> download - <?php print encodeHtml($download->title);?><?php foreach ($dirTree as $parent) { ?> | <?php print encodeHtml($parent->name); ?><?php } ?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> download - <?php print encodeHtml($download->title);?><?php foreach ($dirTree as $parent) { ?> | <?php print encodeHtml($parent->name); ?><?php } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (!isset($download) || $download->id < 1) {
		print '<h2>Sorry, this download is no longer available</h2>';
	}
	else if ($addToBasket) {
?>
	<h2><?php print encodeHtml($fileItem->title);?></h2>
	
	<p>Some text explaining the download is purchasable as a product and the user should add the product to there basket</p>
	
	<ul>
<?php
		if (!empty($download->description)) {
?>
		<li><?php print nl2br(encodeHtml($download->description)); ?></li>
<?php
		}
?>
		<li>Size: <?php print $fileItem->getHumanReadableSize(); ?></li>
		<li>Extension: <?php print encodeHtml($extension); ?> <img src="<?php print getStaticContentRootURL() . $fileItem->getFileIcon(); ?>" alt=" " /></li>
	</ul>
		
	<form enctype="multipart/form-data" action="http://<?php print DOMAIN . buildRetailBasketURL(); ?>" method="post" name="basketform">
		<input type="submit" name="add_to_basket" value="Add to basket" />
		<input type="hidden" name="action_basket" value="ADD" />
		<input type="hidden" name="id" value="<?php print $product->id; ?>" />
		<input type="hidden" name="q" value="1"/>
	</form>
	
<?php
	}
	else if ($showProductLink) {
?>

	<h2><?php print encodeHtml($fileItem->title);?></h2>
	
	<p>Some text explaining the download is purchasable as can be purchased through multiple products, <a href="/site/scripts/retail_products_with_download.php?downloadFileID=<?php print intval($_GET['fileID']); ?>">linking to the products list page</a>.</p>
		
<?php
		if (!empty($download->description)) {
?>

	<p><?php print nl2br(encodeHtml($download->description)); ?></p>

<?php
		}
?>

		
<?php
	}
	else if ($showDownload) {
		if (isset($fileItem) && $fileItem->id > 0) {
			if ($fileItem->url == '') {
				$extension = $fileItem->getFilenameExtension();
				$filename = 'http://' . $DOMAIN . buildDownloadsURL(-1, $fileItem->id, $download->id, true);
			}
			else {
				$filename = encodeHtml($fileItem->url);
				$extension = $fileItem->getURLExtension();
			}
?>

	<h2><?php print encodeHtml($fileItem->title);?></h2>
	<h3><a href="<?php print $filename; ?>">Download now</a></h3>
	<ul>
<?php
	if (!empty($download->description)) {
?>
		<li><?php print nl2br(encodeHtml($download->description)); ?></li>
<?php
	}
?>
		<li>Size: <?php print $fileItem->getHumanReadableSize(); ?></li>
		<li>Extension: <?php print encodeHtml($extension); ?> <img src="<?php print getStaticContentRootURL() . $fileItem->getFileIcon(); ?>" alt=" " /></li>
	</ul>
	
<?php
		}
		else if (count($allFiles) > 0) {
?>

	<ul>
		<li><?php print nl2br(encodeHtml($download->description)); ?></li>
<?php
			foreach ($allFiles as $fileItem) {
				if ($fileItem->url == '') {
					$extension = $fileItem->getFilenameExtension();
					$path = 'http://'. DOMAIN . buildDownloadsURL(-1, $fileItem->id, $download->id);
				}
				else {
					$extension = $fileItem->getURLExtension();
					$path = $fileItem->url;
				}
?>
		<li><a href="<?php print encodeHtml($path); ?>"><?php print encodeHtml($fileItem->title); ?></a></li>
<?php
				if ($extension != '') {
?>
		<li><img src="<?php print getStaticContentRootURL() . $fileItem->getFileIcon(); ?>" alt=" " /> (<?php print encodeHtml($extension); ?>)</li>
<?php
				}
?>
		<li>Size: <?php print $fileItem->getHumanReadableSize();?></li>
<?php
			}
?>
	</ul>

<?php
		}
	}
	else {

?>
	<h2 class="warning">This download is restricted</h2>
	<form name="downloadLoginForm" id="downloadLoginForm" method="post" enctype="multipart/form-data" action="<?php print getSiteRootURL() . buildNonReadableDownloadsURL(-1, isset($fileItem) ? $fileItem->id : -1, $download->id); ?>" >
		<fieldset>
			<legend>Please enter the password</legend>
			<p>
				<label for="password">Password</label>
				<input type="password" name="password" id="password" value="" />
				<input type="submit" name="submitDownloadLogin" id="submitDownloadLogin" value="Submit" />
			 </p>
		</fieldset>
	</form>
<?php
	}
?>
					
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
