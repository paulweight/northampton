<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduCategories.php");
	include_once("JaduMetadata.php");
	include_once("eConsultation/JaduBooks.php");
	include_once("eConsultation/JaduConsultations.php");
	include_once("eConsultation/JaduConsultationMappings.php");
	include_once("egov/JaduCL.php");
	
	if (isset($chapterID) && is_numeric($chapterID)) {
		$chapter = getBookChapter($chapterID, true);
		$bookID = $chapter->bookID;
		$pageList = getAllBookPagesForChapter ($chapterID, true);
		if (!isset($pageID) && sizeof($pageList) > 0) {
			$pageID = $pageList[0]->id;
		}
	}
	
	if (isset($pageID) && is_numeric($pageID)) {
		$page = getBookPage($pageID);
		if (!isset($chapter)) {
			$chapter = getBookChapter($page->chapterID);
			$pageList = getAllBookPagesForChapter ($chapter->id, true);
		}
		$bookID = $page->bookID;
	}
	
	if (isset($bookID) && is_numeric($bookID) && $bookID > 0) {
		
		//	Book and Page information retrieval
		$book = getBook($bookID, true, true);
		$consultation = -1;
		
		if ($book != -1) {
			$header = getBookHeader($book->headerOriginalID, true);
			$allChapters = getAllBookChaptersForBook($bookID, true);
			
			if (isset($consultationID) && $consultationID > 0) {
				$consultation = getConsultation($consultationID, true, true);
				$additionalURL = "consultationID=$consultationID&amp;";
			}
			
			//	If we havent got a consultation to work with, then find one from mapping table
			if ($consultation == -1) {
				$mappings = getAllConsultationToBooksForBook ($book->id);
				$consultationID = $mappings[0]->consultationID;
				$consultation = getConsultation($consultationID, true, true);
				$additionalURL = "consultationID=$consultationID&amp;";				
			}
		}
	}
	
	$breadcrumb = 'bookPage';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - <?php print $header->title; ?> Consultation - <?php print $page->title; ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="documents, consultations, policies, information, plans, performance, objectives, facts and figures, strategy, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s <?php print $header->title; ?> Consultation - <?php print $page->title; ?>" />

	<?php printMetadata(BOOKS_METADATA_TABLE, BOOKS_CATEGORIES_TABLE, $book->id, $book->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
		
<?php
	if ($book == -1) {
?>
		<h2>Sorry, this online book is not available.</h2>
<?php
	} 
	else {
?>
		<h2><?php print $chapter->title; ?></h2>

		<h3><?php print $page->title; ?></h3>
				
		<div class="byEditor">
			<?php print $page->description; ?>
		</div>

		<p>		
<?php
	if ($chapter->number > 1) {
		$previousChapterNumber = $chapter->number-2;
		$previousChapter = $allChapters[$previousChapterNumber];
		print "<a href=\"http://$DOMAIN/site/scripts/book_page.php?".$additionalURL."chapterID=".$previousChapter->id."\">Previous chapter</a> | ";
	}
	
	if ($page->position > 1) {
		$previous = $pageList[$page->position-2];
		print "<a href=\"http://$DOMAIN/site/scripts/book_page.php?".$additionalURL."chapterID=".$chapter->id."&amp;pageID=".$previous->id."\">Previous page</a> | ";
	}
	
	if ($page->position < sizeof($pageList)) {
		$next = $pageList[$page->position];
		print "<a href=\"http://$DOMAIN/site/scripts/book_page.php?".$additionalURL."chapterID=".$chapter->id."&amp;pageID=".$next->id."\">Next page</a> | ";
	}
	
	if ($chapter->number < sizeof($allChapters)) {
		$nextChapter = $allChapters[$chapter->number];
		print "<a href=\"http://$DOMAIN/site/scripts/book_page.php?".$additionalURL."chapterID=".$nextChapter->id."\">Next chapter</a>";
	}
?>
		</p>

		<div class="plain_box">
			<h3 id="pagenavbox"><?php if ($header->numbering == BOOK_PAGE_NUMBERING_ON) { print $chapter->number . ". "; }	print $chapter->title; ?></h3>		
<?php
	if (sizeof($pageList) > 0) {
?>
			<ol class="noList">
<?php
		$total = sizeof($pageList);
		$previousPage = new BookPage();
		$levelCounter = array();

		foreach ($pageList as $pageIndex => $p) {
		
//			$indent = str_pad('&nbsp;', $p->level*strlen(BOOK_PAGE_LEVEL_INDENT)*BOOK_PAGE_LEVEL_NUM_INDENTS, BOOK_PAGE_LEVEL_INDENT);
			
			if ($p->level < $previousPage->level) {
				$levelCounter[$previousPage->level] = 0;
			}
			
			$levelCounter[$p->level] = $levelCounter[$p->level] + 1;
			
			$num_label = $chapter->number;
			
			for ($i = 1; $i <= $p->level; $i++) {
				if ($header->numbering == BOOK_PAGE_NUMBERING_ON) {					
					$num_label .= '.' . $levelCounter[$i];
				}
			}	
?>
				<li><?php print $num_label ?> <?php if ($p->id == $page->id) { ?><strong>You are here</strong> <?php } ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/book_page.php?<?php print $additionalURL;?>chapterID=<?php print $chapter->id;?>&amp;pageID=<?php print $p->id;?>"><?php print $p->title;?></a></li>
			
<?php
			$previousPage = $p;
		}
?>
			</ol>
<?php
	}
?>
			<p><a href="http://<?php print $DOMAIN; ?>/site/scripts/book_info.php?<?php print $additionalURL;?>bookID=<?php print $bookID; ?>">Full Table of Contents</a></p>	
		</div>

<?php
		$linksList = getBookPagesLinksInPage($page->id);					

		if (sizeof($linksList) > 0) {
?>
		<h3>Further Information:</h3>
<?php
			foreach ($linksList as $linkItem) {
?>
			<div class="search_result">
				<h4><?php print $linkItem->title; ?></h4>
				<p><?php print $linkItem->description; ?></p>
<?php
				if ($linkItem->url != "") { 
?>
				<p><a href="<?php print $linkItem->url;?>">View details on <?php print $linkItem->title;?></a></p>
<?php
				}
?>
			</div>
<?php
			}
		}
	}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>