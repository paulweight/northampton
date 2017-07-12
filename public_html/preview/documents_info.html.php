<?php include("../site/includes/doctype.php"); ?><head>
	<title><?php print encodeHtml($page->title); ?> - <?php print encodeHtml($header->title); ?> - <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

<?php
	include_once(HOME  . "site/includes/stylesheets.php");
	include_once(HOME . "site/includes/metadata.php");
	
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
	
<?php
	if ($document->id > 0 && isset($header)) {
		printMetadata(DOCUMENTS_METADATA_TABLE, DOCUMENTS_CATEGORIES_TABLE, $document->id, $header->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
		print outputJavascriptConstants();
	}
?>	

	<link rel="canonical" href="<?php print getSiteRootURL() . buildDocumentsURL($document->id, $categoryID, $pageNumber); ?>" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include(HOME . "site/includes/opening.php"); ?>
<!-- ########################## -->
	   
<?php
	if ($document->id == -1) {
?>
		<h2>Sorry, this document is no longer available</h2>
<?php
	}
	else {
		if (trim($page->title) != trim($header->title)) {
?>
		<h2><?php print encodeHtml($page->title); ?></h2>

<?php
		}
		
		if (sizeof($allPages) > 1) {
?>
		<p class="page_down"><a href="<?php print getSiteRootURL() . buildDocumentsURL($document->id, $categoryID, $pageNumber); ?>#pagenavbox">View pages in this section</a></p>
<?php
		}
?>
	
		<div class="byEditor article">
			<?php include(HOME . 'site/includes/right_supplements.php'); ?>
<?php
		if ($page->imageURL != '') {
?>
			<img src="<?php print getStaticContentRootURL() . '/images/' . encodeHtml($page->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($page->imageURL, 'altText')); ?> " class="main_image" />
<?php
		}
?>
			<?php print $page->description; ?>
		</div>

		<!-- Page Navigation if there is more than one page-->
<?php
		if (sizeof($allPages) > 1) {
?>
			<h3>Pages in <?php print encodeHtml($header->title); ?></h3>
			<ol class="pageList">
<?php
			$pageCount = 1;
			foreach ($allPages as $p) {
?>
				<li>
					<?php if ($pageCount == $pageNumber) { ?><strong>You are here:</strong><?php } ?> <?php if ($pageCount != $pageNumber) { ?><a href="<?php print getSiteRootURL() . buildDocumentsURL($document->id, $categoryID, $pageCount);?>"><?php } ?><?php print encodeHtml($p->title); ?><?php if ($pageCount != $pageNumber) { ?></a><?php } ?>
				</li>
<?php
				$pageCount++;
			}
?>
			</ol>
<?php
		}
		if (sizeof($allPages) > 1) {
			$pageNumberPrev = $pageNumber -1;
			$pageNumberNext = $pageNumber +1;
			$pageTotal = count($allPages);
?>
			<ul class="pagePagination">
				<?php if($pageNumberPrev != 0){ ?><li><a href="<?php print getSiteRootURL() . buildDocumentsURL($document->id, $categoryID, $pageNumberPrev);?>">Previous page</a></li><?php } ?>
				<?php if($pageNumberNext != $pageTotal+1){ ?><li><a href="<?php print getSiteRootURL() . buildDocumentsURL($document->id, $categoryID, $pageNumberNext);?>">Next page</a></li><?php } ?>
			</ul>
<?php
		}
?>	

		<!-- end page Nav -->

	<?php include(HOME . 'site/includes/bottom_supplements.php'); ?>

<?php
	}
?>	
	  <!-- END further information box -->

	<!-- Social Bookmarks -->
	<?php include(HOME . "site/includes/social_bookmarks.php"); ?>


<!-- ################ MAIN STRUCTURE ############ -->
<?php
	include(HOME . "site/includes/closing.php"); 
