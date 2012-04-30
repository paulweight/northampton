<?php
	include_once('utilities/JaduStatus.php');
	include_once('websections/JaduDownloads.php');
	include_once('websections/JaduDownloadPasswords.php');
	
	if (defined('RETAIL_PRODUCT_DOWNLOADS_ENABLED') && RETAIL_PRODUCT_DOWNLOADS_ENABLED) {
		include_once('retail/JaduRetailProductsToDownloads.php');
		include_once('retail/JaduRetailDownloadLog.php');
	}
	
	$allowDownload = true;
	$dirTree = array();
	
	if (isset($_GET['type']) && $_GET['type'] == 'meetings' && isset($_GET['fileID']) && is_numeric($_GET['fileID'])) {
		include_once('egov/JaduEGovMeetingMinutes.php');
		include_once('egov/JaduEGovMeetingAttachments.php');	
		include_once('library/JaduFileSystemFunctions.php');		
		
		$download = getMeetingAttachment ($_GET['fileID']);		
		$meeting = getMeetingMinutes($download->meetingID);	
		$header = getMeetingMinutesHeader($meeting->headerID);
		
		if (isset($download) && $download->id > 0 && $download->contentType == 'file') {
			$filepath = $download->getFilePath() . $download->content;	
			if (file_exists($filepath)) {
				ob_start();
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header('Content-Type: ' . mimeContentType($filepath));
				header('Content-Disposition: attachment; filename="' . $download->content . '"');

				// Check if mod_xsendfile is enabled
				if (defined('XSENDFILE_ENABLED') && XSENDFILE_ENABLED) {
					header('X-Sendfile: ' . $filepath);
					ob_end_clean();
				}
				else {
					header('Content-Length: ' . $download->size);
					header('Pragma: public');
					ob_end_clean();
						
					// Disable max execution time
					set_time_limit(0);
					readfile($filepath);
				}
				
				// Halt the script
				exit();
			}
		}
		
		$breadcrumb = 'meetingsInfo';
	}
	else if (isset($_GET['type']) && $_GET['type'] == 'downloads' && isset($_GET['fileID']) && is_numeric($_GET['fileID'])) { 
		$criteria = array(
			'approved' => true, 
			'live' => true
		);
		
		$file = getDownloadFile($_GET['fileID'], $criteria);

		if ($file->id > 0) {
			if (defined('RETAIL_PRODUCT_DOWNLOADS_ENABLED') && RETAIL_PRODUCT_DOWNLOADS_ENABLED) {
				if (isset($_SESSION['purchasedFiles'][$file->id]) && isDownloadFileAssignedToAProduct($file->id)) {
					$download = getDownload($file->downloadID, $criteria);
				}
				else {
					$allowDownload = false;
				}
			}
			else {
				$download = getDownload($file->downloadID, $criteria);
			}
		}

		if (isset($download) && $download->id > 0) {
			if ($download->passwordID < 1 || (isset($_SESSION['authenticaedDownloadIDs']) && in_array($download->id, $_SESSION['authenticaedDownloadIDs']))) {
				$filepath = $file->getFilePath();
		
				if (file_exists($filepath)) {
					ob_start();
					header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header('Content-Type: ' . $file->mimeType);
					header('Content-Disposition: attachment; filename="' . $file->filename . '"');

					// Check if mod_xsendfile is enabled
					if (defined('XSENDFILE_ENABLED') && XSENDFILE_ENABLED) {
						header('X-Sendfile: ' . $filepath);
						ob_end_clean();
					}
					else {
						header('Content-Length: ' . $file->size);
						header('Pragma: public');
						ob_end_clean();
						
						// Disable max execution time
						set_time_limit(0);
						readfile($filepath);
					}
					
					// Halt the script
					exit();
				}
			}
			else {
				$allowDownload = false;
			}
			
			// Get the category list for the download
			include_once("JaduCategories.php");
			include_once("egov/JaduCL.php");

			$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
			$categoryID = getFirstCategoryIDForItemOfType (DOWNLOADS_CATEGORIES_TABLE, $download->id, BESPOKE_CATEGORY_LIST_NAME);	
			$currentCategory = $lgclList->getCategory($categoryID);
			$dirTree = $lgclList->getFullPath($categoryID);
		}
		
		// Breadcrumb, H1 and Title
		if ($download->id == '-1'){
			$MAST_HEADING = 'Download not found';
		}
		else {
			$MAST_HEADING = $download->title;
		}
		
		$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildDownloadsURL(). '">Downloads</a></li>';
		foreach ($dirTree as $parent) {
			$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildDownloadsURL($parent->id).'" >'. encodeHtml($parent->name) .'</a></li>';
		}
		$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($download->title) .'</span></li>';

	}
	
	// These are only required if file not found
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	
	if (!$allowDownload) {
		// Download password protected and not authenticated
		header('HTTP/1.0 403 Forbidden');
	}
	else {
		// Download file doesn't exist
		header('HTTP/1.0 404 Not Found');
	}
	
	include("download.html.php");
?>