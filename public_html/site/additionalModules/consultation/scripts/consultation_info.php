<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduCategories.php");
	include_once("JaduMetadata.php");
	include_once("egov/JaduCL.php");
	include_once("eConsultation/JaduConsultations.php");
	include_once("eConsultation/JaduConsultationMappings.php");
	include_once("eConsultation/JaduConsultationProducts.php");
	include_once("eConsultation/JaduConsultationDownloads.php");
	include_once("eConsultation/JaduBooks.php");
	include_once("eConsultation/JaduConsultationNotificationRequestors.php");
		
	if (isset($consultationID) && is_numeric($consultationID) && !empty($consultationID)) {
	
		$consultation = getConsultation($consultationID, true, true);	
	
		if ($consultation != -1) {
		
			//	Book and Page information retrieval
			$allBooksMap = getAllConsultationToBooksForConsultation ($consultation->id);
			$allProductsMap = getAllConsultationToProductsForConsultation ($consultation->id);
	
			$allBooks = array();
			foreach ($allBooksMap as $map) {
				$book = getBook($map->bookID, true, true);
				if ($book != -1)
					$allBooks[] = $book;
			}
			
			$allProducts = array();
			foreach ($allProductsMap as $map) {
				$product = getConsultationProduct($map->productID, true);
				if ($product != -1)
					$allProducts[$product->typeID][] = $product;
			}
			
			$typedDownloads = array();
			$allConsultationDownloads = getAllConsultationDownloadsForConsultation ($consultation->id, true);
			foreach ($allConsultationDownloads as $rightColDownload) {
				$typedDownloads[$rightColDownload->typeID][] = $rightColDownload;
			}
		}
		else {
			header("Location: http://$DOMAIN/site/scripts/consultation_open.php");
			exit;
		}
	}
	else {
		header("Location: http://$DOMAIN/site/scripts/consultation_open.php");
		exit;
	}

	$breadcrumb = 'consultationInfo';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> Consulation - <?php print $consultation->title; ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Consultation, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Consulation - <?php print $consultation->title; ?>" />

	<?php printMetadata(CONSULTATIONS_METADATA_TABLE, CONSULTATIONS_CATEGORIES_TABLE, $consultation->id, $consultation->title, CONSULTATIONS_PUBLIC_FOLDER.$consultation->folderName."/index.php"); ?>

</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ####################################### -->

<?php
	if ($consultation == -1) {
?>
	<h2>Sorry, this consultation is not available.</h2>
<?php
	} 
	else {
?>

		<h2><?php print $consultation->title; ?></h2>

<?php
			if ($consultation->startDate > 0) {
				print "<p class=\"date\">From: " . $consultation->getConsultationDate('start') . " To: " . $consultation->getConsultationDate('end') . ".</p>";
			}
?>
		<div class="byEditor">
			<?php print $consultation->description; ?>
		</div>

		<p><span class="email"><a href="http://<?php print $DOMAIN;?>/site/scripts/consultation_notification.php?consultationID=<?php print $consultation->id;?>">Sign-up for email alerts on this consultation </a></span></p>

		<!-- Applicable Books -->

<?php
		if (sizeof($allBooks) > 0) {
?>

		
		<h3>Online books</h3>
			<ul class="list">
<?php
			foreach ($allBooks as $book) {
				$header = getBookHeader($book->headerOriginalID);
				print "<li><a href=\"http://$DOMAIN/site/scripts/book_info.php?consultationID=$consultation->id&amp;bookID=$book->id\">$header->title</a></li>";
			}
?>
			</ul>

<?php
		}
?>

		<!-- Applicable Products -->
<?php
		if (sizeof($allProducts) > 0) {
?>
		<h3>Media to order</h3>
	<?php
			foreach ($allProducts as $index => $productTypeArray) {
				$productType = getConsultationProductType($index);
				print "<h4>$productType->title </h4>";
				print "<ul class=\"list\">";
				
				foreach ($productTypeArray as $product) {
					print "<li><a href=\"http://$DOMAIN/site/scripts/product_info.php?consultationID=$consultation->id&amp;productID=$product->id\">$product->title</a></li>";
				}
				print "</ul>";
			}
		}
?>

		<!-- Applicable Downloads -->
<?php
		if (sizeof($allConsultationDownloads) > 0) {
?>
	
		<h3>Downloads</h3>
		
<?php
			foreach ($typedDownloads as $index => $typeArray) {
				$rightColDownloadType = getConsultationDownloadType($index);
				print "<h4>$rightColDownloadType->type</h4>";
				print "<ul class=\"list\">";
				
				foreach ($typeArray as $rightColDownload) {
					$allRightColFiles = getAllConsultationDownloadFilesForConsultationDownload($rightColDownload->id);
					if (sizeof($allRightColFiles) > 0) {
						print "<li><a href=\"http://$DOMAIN/site/scripts/consultation_download_info.php?downloadID=$rightColDownload->id\" title=\"$rightColDownload->title\">$rightColDownload->title</a></li>";
					}
				}
				print "</ul>";
			}
		}
?>




<?php
	if ($consultation->allowComments == 1) {
		if (!isset($_SESSION['userID'])) {
?>
		<p><span class="comment">Log in to make a comment about <?php print $consultation->title; ?></span></p>
<?php
		} 
		else {
?>	
		<form name="comment" action="http://<?php print $DOMAIN;?>/site/scripts/comment_input.php" method="post" class="basic_form">
			<input type="hidden" name="topic" value="General <?php print $consultation->title; ?>" />
			<p class="center">
				<input type="submit" class="button" name="comment" value="Comment On <?php print $consultation->title; ?>" />
			</p>
		</form>
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