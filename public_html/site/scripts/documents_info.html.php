<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($page->title); ?> | <?php print encodeHtml($header->title); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

<?php
	include_once("../includes/stylesheets.php");
	include_once("../includes/metadata.php");
	
	$metadata = getMetadataForItem (DOCUMENTS_METADATA_TABLE, $_GET['documentID']);
	if ($metadata->subject == '') {
		$metadata->subject = 'documents, consultations, policies, information, plans, performance, objectives, facts and figures, strategy, ' . METADATA_GENERIC_KEYWORDS;
	}
	if ($metadata->description == '') {
		$metadata->description = METADATA_GENERIC_NAME . 's ' . $header->title . ' and ' . $page->title . ' information';
		foreach ($dirTree as $parent) {
			$metadata->description .= ' | ' . $parent->name;
		}
	}
?>

	<meta name="Keywords" content="<?php print encodeHtml($metadata->subject); ?>" />
	<meta name="Description" content="<?php print encodeHtml($metadata->description); ?>" />
	
	<?php if ($document->id > 0 && isset($header)) printMetadata(DOCUMENTS_METADATA_TABLE, DOCUMENTS_CATEGORIES_TABLE, $document->id, $header->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>	

	<link rel="canonical" href="<?php print getSiteRootURL() . buildDocumentsURL($document->id, $categoryID, $pageNumber); ?>" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
	   
<?php
	if ($showDocument) {
		if ($document->id == -1) {
?>

	<h2>Sorry, this page is no longer available</h2>

<?php
		}
		else {
			if (trim($page->title) != trim($header->title)) {
?>

	<h2><?php print encodeHtml($page->title); ?></h2>
<?php 
			}
?>
	<div class="byEditor article">
<?php
			if ($page->imageURL != '') {
				if (mb_strlen(getImageProperty($page->imageURL, 'longdesc')) > 0) {
?>
				<div class="figcaption">
					<img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($page->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($page->imageURL, 'altText')); ?> " />
					<p><?php print encodeHtml(getImageProperty($page->imageURL, 'longdesc')); ?></p>
				</div>
<?php
				}
				else {
?>
				<img class="floatRight" src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($page->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($page->imageURL, 'altText')); ?> " />
<?php
				}
			}
?>
		<?php print processEditorContent($page->description); ?>
	</div>

	<!-- Page Navigation list if there is more than one page -->

<div class="bottomSupplements">
	<?php include('../includes/bottom_supplements.php'); ?>
</div>

<?php
		}
	}
	else if ($accessDenied) {
?>
	<p class="warning">You do not have sufficient access privileges to view this document.</p>
	<p>Please contact <?php print encodeHtml(DEFAULT_EMAIL_ADDRESS); ?> for more information.</p>
<?php
	}
	else {
?>
	<h2 class="warning">This document is restricted</h2>
	<form name="documentLoginForm" id="documentLoginForm" method="post" enctype="multipart/form-data" action="<?php print getSiteRootURL() . buildNonReadableDocumentsURL($document->id, $categoryID, $pageCount); ?>" >
		<fieldset>
			<legend>Please enter the password</legend>
			<p>
				<label for="password">Password</label>
				<input type="password" name="password" id="password" value="" />
				<input type="submit" name="submitDocumentLogin" id="submitDocumentLogin" value="Submit" />
			 </p>
		</fieldset>
	</form>
<?php
	}
?>	

	<!-- Social Bookmarks -->
	<?php include("../includes/social_bookmarks.php"); ?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
