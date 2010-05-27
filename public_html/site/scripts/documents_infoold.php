<?php
	include_once("utilities/JaduStatus.php");
	
	include_once("JaduCategories.php");
	include_once("JaduMetadata.php");
	include_once("websections/JaduDocuments.php");
	include_once("websections/JaduDocumentsCategoryDefaults.php");
	include_once("websections/JaduDocumentPasswords.php");	
	include_once("websections/JaduDocumentPageStructures.php");
	include_once("utilities/JaduMostPopular.php");
	include_once("egov/JaduCL.php");

	// supplements
	include_once("websections/JaduPageSupplements.php");
	include_once("websections/JaduPageSupplementWidgets.php");
	include_once("websections/JaduPageSupplementWidgetPublicCode.php");


	$showDocument = true;
	
	if (!isset($_GET['pageNumber'])) {
		$pageNumber = 1;
	}
	else {
		$pageNumber = $_GET['pageNumber'];
	}

	if (isset($_GET['documentID']) && is_numeric($_GET['documentID'])) {
	
		//	Document and Page information retrieval
		$document = getDocumentLiveVersion($_GET['documentID']);

		if ($document->id != -1) {

			$header = getDocumentHeaderLiveVersion($document->headerOriginalID);

			//get the password for this document (if there is one set)
			$password = getJaduDocumentPassword($header->passwordId);
    	        
    		if ($password->password != '') {
    	        if ($_SESSION['documentPasswordId'] == '') {
        	        if ($password->password == $_POST['password']) {
        	            $showDocument = true;   
        	            $_SESSION['documentPasswordId'] = $password->id;
        	        } 
        	        else {
        	            $showDocument = false;   
        	        }
        	    }
				else {
        	        if ($password->id == $_SESSION['documentPasswordId']) {
        	            $showDocument = true;   
        	        }
					else {
        	            $showDocument = false;
        	            $_SESSION['documentPasswordId'] = '';
        	        }   
        	    }
    	    }
			else {
    	        $showDocument = true;   
    	    }
    	    
	    	if ($_SESSION['userID'] != '') {
            	//do access level stuff
            	$user = getUser($_SESSION['userID']);
            
            	if ($user->accessLevel < $header->accessLevel) {
            		$showDocument = false;
            		$accessDenied = true;
            	}				
            }
			else if ($header->accessLevel > 1) {
            	$showDocument = false;
            	$accessDenied = true;			
            }			
			
			$allPages = getDocumentPagesLiveVersions(explode(',', $document->pageOriginalIDs));
			$page = $allPages[$pageNumber -1];

			$pageStructure = getPageStructure($page->pageStructureID);		
			if($page->pageStructureID == -1 || $page->pageStructureID == '') {
				$pageStructure = getPageStructure($header->pageStructureID);
			}

			//	in case don't follow hierarchy but find document from search etc.
			if (!isset($_GET['categoryID'])) {
				$categoryID = getFirstCategoryIDForItemOfType (DOCUMENTS_CATEGORIES_TABLE, $document->id, BESPOKE_CATEGORY_LIST_NAME);
			}

			//	Category Links
			$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
			$currentCategory = $lgclList->getCategory($categoryID);
			$dirTree = $lgclList->getFullPath($categoryID);
		}
		else {
			$dirTree = array();
		}
	}
	else {
		header("Location: documents_index.php");
		exit;
	}	

	// most popular
	if (strpos($_SERVER['HTTP_REFERER'], 'google_results.php') !== false) {

		$url = '/site/scripts/documents_info.php?documentID=' . $document->id;

		$mostPopularItem = getMostPopularItem ('url', $url);

		if ($mostPopularItem->id != -1) {
			$mostPopularItem->hits++;
			updateMostPopularItem($mostPopularItem);
		}
		else {
			$mostPopularItem->hits = 1;
			$mostPopularItem->url = $url;
			$mostPopularItem->title = $header->title;

			newMostPopularItem($mostPopularItem);
		}
	}

	if ($showDocument) {
		if ($document->id == -1) {
			header("HTTP/1.0 404 Not Found");
		}
	}

	$breadcrumb = 'documentsInfo';
	include_once("JaduStyles.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $page->title; ?> | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="documents, consultations, policies, information, plans, performance, objectives, facts and figures, strategy, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s <?php print $header->title; ?> and <?php print $page->title; ?> information" />

	<?php printMetadata(DOCUMENTS_METADATA_TABLE, DOCUMENTS_CATEGORIES_TABLE, $documentID, $header->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
       
<?php
	if ($showDocument) {
		if ($document->id == -1) {
?>
			<h2>Sorry, this document is no longer available</h2>
<?php
		}
		else {
			if (sizeof($allPages) > 0) {
?>
			<h2><?php print $page->title; ?></h2>

<?php
			}
?>
<?php 
			if ($page->imageURL != "") {
?>
            <img src="http://<?php print $DOMAIN . '/images/' . $page->imageURL; ?>" alt="<?php print getImageProperty($page->imageURL, 'altText'); ?> " class="main_image" />
<?php 
			}
?>
        
	<div class="byEditor">
		<?php print $page->description; ?>
	</div>


<!-- Page Navigation if there is more than one page-->
<?php
	        if (sizeof($allPages) > 1) {
?>

	<h3 id="pagenavbox">Pages in <em><?php print $header->title;?></em></h3>
	<ul class="pagenav">
<?php
				$pageCount = 1;
				foreach ($allPages as $p) {
?>
		<li>
			<?php print $pageCount;?>. <?php if ($pageCount == $pageNumber) { ?><strong>You are here</strong><?php } ?> <a href="http://<?php print $DOMAIN; ?>/site/scripts/documents_info.php?documentID=<?php print $document->id;?>&amp;pageNumber=<?php print $pageCount; ?>" <?php if ($pageCount == $pageNumber) { ?>class="noLink"<?php } ?>><?php print "$p->title";?></a>
		</li>
<?php
					$pageCount++;
				}
?>
	</ul>

<?php
			}
?>				
<!-- end page Nav -->

		<!-- Bottom Supplements -->
<?php
		// get bottom supplements 
		if (isset($page) || isset($homepage)) {
			if (isset($page)) {
				$bottomSupplements = getAllPageSupplements('', $page->id, '', 'Bottom');
			}
			elseif (isset($homepage)) {
				$bottomSupplements = getAllPageSupplements('', '', $homepage->id, 'Bottom');
			}
			// loop through each supplement
			foreach ($bottomSupplements as $supplement) {
				// include supplement front-end code
				$publicCode = getSupplementPublicCode($supplement->supplementWidgetID, $supplement->locationOnPage);
				$supplementWidget = getPageSupplementWidget($supplement->supplementWidgetID);

				include_once($supplementWidget->classFile);

				$record = new $supplementWidget->className;
				$record->id = $supplement->supplementRecordID;
				$record->get();
				include($HOME . '/site/includes/supplements/' . $publicCode->code);
			}
		}
?>
		<!-- End bottom supplements -->

<?php
		}
	}

	else if ($accessDenied) {
?>
			<p class="accessDenied warning">You do not have sufficient access privileges to view this document.</p>
			<p class="accessDenied">Please contact <?php print $DEFAULT_EMAIL_ADDRESS ?> for more information.</p>
<?php
	}
	else {
?>
			
			<form class="basic_form" name="documentLoginForm" id="documentLoginForm" method="post" action="<?php print $_SERVER['REQUEST_URI']; ?>">
				<h2 class="warning">This document is restricted!</h2>
				<fieldset>
					<legend>Please enter the password</legend>
					<p>
						<label for="password">Password</label>
						<input type="password" name="password" id="password" class="field" value="" />
						<input type="submit" name="submitDocumentLogin" id="submitDocumentLogin" class="button" value="Submit" />
					 </p>
				</fieldset>
			</form>
<?php
	}
?>	
	  <!-- END further information box -->

	<!-- Social Bookmarks -->
	<!-- <?php include("../includes/social_bookmarks.php"); ?> -->

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
