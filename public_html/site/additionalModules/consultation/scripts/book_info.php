<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduCategories.php");
	include_once("JaduMetadata.php");
	include_once("eConsultation/JaduBooks.php");
	include_once("eConsultation/JaduConsultations.php");
	include_once("eConsultation/JaduConsultationMappings.php");
	include_once("egov/JaduCL.php");
	
	if (isset($_GET['bookID']) && is_numeric($_GET['bookID']) && $_GET['bookID'] > 0) {
		
		//	Book and Page information retrieval
		$book = getBook($_GET['bookID'], true, true);
		$consultation = -1;
		
		if ($book != -1) {
			$header = getBookHeader($book->headerOriginalID, true);
			$allChapters = getAllBookChaptersForBook($_GET['bookID'], true);
			
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
	else {
		header("Location: ../index.php");
		exit;
	}
	
	$breadcrumb = 'bookInfo';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - <?php print $header->title; ?> Consultation</title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="documents, consultations, policies, information, plans, performance, objectives, facts and figures, strategy, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s <?php print $header->title; ?> Consultation" />

	<?php printMetadata(BOOKS_METADATA_TABLE, BOOKS_CATEGORIES_TABLE, $book->id, $book->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($book == -1) {
?>
	<h2>Sorry, this book is not available.</h2>
<?php
	} 
	
	else {

		if ($header->imageURL != "") { 
?>
			<img src="http://<?php print $DOMAIN . '/images/' . $header->imageURL; ?>" alt="<?php print $header->title;?>" class="contentimage" />
<?php 
		} 
?>
		<div class="byEditor">
			<?php print $header->description; ?>
		</div>

		<!-- Page Navigation if there is more than one page-->
<?php
		if (sizeof($allChapters) > 0) {
?>
		<ol class="noList">
<?php
			foreach ($allChapters as $c) {
				print "<li><h3>";
				
				if ($c->useAsLink == 1) {
					print "<a href=\"$c->link\">";
				}
				if ($header->numbering == BOOK_PAGE_NUMBERING_ON) {
					print $c->number . ". ";
				}
				print $c->title;
		
				if ($c->useAsLink == 1) {
					print "</a>";
				}
				print "</h3>";

				$pageList = getAllBookPagesForChapter($c->id, true);

				if (sizeof($pageList) > 0) {

					$total = sizeof($pageList);
					$previousPage = new BookPage();
					$levelCounter = array();

					foreach ($pageList as $pageIndex => $page) {
						$indent = str_pad('', $page->level*strlen(BOOK_PAGE_LEVEL_INDENT)*BOOK_PAGE_LEVEL_NUM_INDENTS, BOOK_PAGE_LEVEL_INDENT);
					
						if ($page->level < $previousPage->level) {
							$levelCounter[$previousPage->level] = 0;
						}
						$levelCounter[$page->level] = $levelCounter[$page->level]+1;
					
						$pageTitle = $page->title;
						if ($header->numbering == BOOK_PAGE_NUMBERING_ON) {
							$num_label = $c->number;
							for ($i = 1; $i <= $page->level; $i++) {
								$num_label .= '.' . $levelCounter[$i];
							}
							$pageTitle = $num_label . ' ' . $pageTitle;
						}

						print "<p>".$indent ."<a href=\"http://$DOMAIN/site/scripts/book_page.php?".$additionalURL."chapterID=$c->id&amp;pageID=$page->id\">$pageTitle</a></p>";
						$previousPage = $page;
					}
				}
				print "</li>";
			}
?>
	</ol>
<?php
		}
	}
?>
		

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>