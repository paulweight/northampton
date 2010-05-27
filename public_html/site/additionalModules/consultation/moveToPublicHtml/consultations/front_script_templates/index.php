<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduConstants.php");
	include_once("JaduStyles.php");
	include_once("JaduCategories.php");
	include_once("JaduMetadata.php");
	include_once("egov/JaduCL.php");
	include_once("eConsultation/JaduBooks.php");	
	include_once("eConsultation/JaduConsultations.php");
	include_once("eConsultation/JaduConsultationMappings.php");
//	include_once("eConsultation/JaduConsultationProducts.php");
	include_once("eConsultation/JaduConsultationDownloads.php");
	include_once("eConsultation/JaduConsultationNotificationRequestors.php");
	include_once("eConsultation/JaduComments.php");
	
	$dirTree = explode("/",dirname($_SERVER['PHP_SELF']));
	$consultation = getConsultationFromFolderName($dirTree[sizeof($dirTree)-1], true, true);
	
	if ($consultation != -1) {
		
/*
		$allProducts = array();
		foreach ($allProductsMap as $map) {
			$product = getConsultationProduct($map->productID, true);
			if ($product != -1)
				$allProducts[$product->typeID][] = $product;
		}
*/

		$typedDownloads = array();
		$allConsultationDownloads = getAllConsultationDownloadsForConsultation ($consultation->id, true);
		foreach ($allConsultationDownloads as $rightColDownload) {
			$typedDownloads[$rightColDownload->typeID][] = $rightColDownload;
		}
	}

	$breadcrumb = "consultationHome";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php print "$consultation->title"; ?> | Consulations | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	
	<?php include_once($HOME."site/includes/stylesheets.php"); ?>
	<?php include_once($HOME."site/includes/metadata.php"); ?>
	
	<?php printMetadata(CONSULTATIONS_METADATA_TABLE, CONSULTATIONS_CATEGORIES_TABLE, $consultation->id, $consultation->title, CONSULTATIONS_PUBLIC_FOLDER.$consultation->folderName."/index.php"); ?>
	<meta name="Keywords" content="services, a-z, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Full A to Z listing alphabetically details of all services in your area" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Glossary" />
	<meta name="DC.identifier" content="http://<?php print $DOMAIN.$_SERVER['PHP_SELF'];?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include($HOME . "site/includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($consultation == -1) {
?>
	<h2>Sorry, this consultation is not available.</h2>
<?php
	} 
	else {
		if ($consultation->startDate > 0) {
?>
		<p class="first">From: <?php print $consultation->getConsultationDate('start'); ?>. To: <?php print $consultation->getConsultationDate('end'); ?>.</p>
<?php
		}
?>
		<ul class="list">
<?php
		if ($consultation->allowComments == 1) {
			$topic = $consultation->title;
//			$comments = getCommentsByTopic($topic);
			$comments = getCommentsByConsultation($consultation->id);

			if (!isset($_SESSION['userID'])) {
?>
			<li><a href="https://<?php print $DOMAIN;?>/site/index.php?sign_in=true">Sign in</a> to make a comment.</li>
<?php
			} 
			
			else {
?>	
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/comment_input.php?consultationID=<?php print $consultation->id;?>">Make a comment</a></li>
<?php
			}
			
			if (sizeof($comments)) {
?>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/comment_viewer.php?consultationID=<?php print $consultation->id;?>">Read others comments</a></li>
<?php
			}

		}

	}

	if ($consultation->allowNotificationSignups == 1) {	
		$displaySignup = true;
		if (isset($_SESSION['userID'])) {
			$notification = getConsultationNotificationRequestForEmailAndConsultation ($user->email, $consultation->id);
			if ($notification != -1) {
				$displaySignup = false;
			}
		}
		else {
			if (isset($_GET['notificationID']) && isset($_GET['action']) && isset($_GET['email'])) {
				$notification = getConsultationNotificationRequest($_GET['notificationID']);		
				if ($notification != -1) {
					$displaySignup = false;
				}
				else if ($notification->email == $_GET['email']) {
					$displaySignup = false;
				}
			}
		}
	}
	
	else {
		$displaySignup = false;
	}

	if ($displaySignup) {
?>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/consultation_notification.php?consultationID=<?php print $consultation->id;?>">Sign-up for email alerts</a></li> 
<?php
	}
?>
		</ul>

		<div class="byEditor">
			<?php print $consultation->description; ?>
		</div>

	
<?php
	if ($consultation != -1) {		
			
		//	Book and Page information retrieval
		$allBooksMap = getAllConsultationToBooksForConsultation ($consultation->id);
		//$allProductsMap = getAllConsultationToProductsForConsultation ($consultation->id);
			
		$allBooks = array();
		foreach ($allBooksMap as $map) {
			$book = getBook($map->bookID, true, true);
			if ($book != -1) {
				$allBooks[] = $book;
			}
		}
		
		if (sizeof($allBooks) > 0) {
?>	
			<div class="cate_info">
				<h2>Online books</h2>
				<ul class="list">
<?php
			foreach ($allBooks as $book) {
				$header = getBookHeader($book->headerOriginalID);
				print "<li><a href=\"http://$DOMAIN/site/scripts/book_info.php?consultationID=$consultation->id&amp;bookID=$book->id\">$header->title</a></li>";
			}
?>
				</ul>
			</div>
<?php
		}
	}
		
	$typedDownloads = array();
	$allConsultationDownloads = getAllConsultationDownloadsForConsultation($consultation->id, true);
	foreach ($allConsultationDownloads as $rightColDownload) {
		$typedDownloads[$rightColDownload->typeID][] = $rightColDownload;
	}

	if (sizeof($allConsultationDownloads) > 0) {
		foreach ($typedDownloads as $index => $typeArray) {
			$rightColDownloadType = getConsultationDownloadType($index);
?>
			<div class="cate_info">
				<h2><?php print $rightColDownloadType->type; ?></h2>
				<ul class="list">
<?php
				foreach ($typeArray as $rightColDownload) {
					
					$allRightColFiles = getAllConsultationDownloadFilesForConsultationDownload($rightColDownload->id);

					if (sizeof($allRightColFiles) > 0) {
						print "<li><a href=\"http://$DOMAIN/site/scripts/consultation_download_info.php?downloadID=$rightColDownload->id\">$rightColDownload->title</a>.</li>";
					}
				}
?>
				</ul>
			</div>
<?php
			}
		}
?>	


	<!-- The Contact box -->
	<?php include($HOME."site/includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include($HOME."site/includes/closing.php"); ?>
