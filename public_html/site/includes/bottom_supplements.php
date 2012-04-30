<?php
	include_once('websections/JaduPageSupplements.php');
	include_once('websections/JaduPageSupplementWidgets.php');
	include_once('websections/JaduPageSupplementWidgetPublicCode.php');
	
	$showBottomSupplements = false;
	
	if (($_SERVER['SCRIPT_NAME'] == '/site/scripts/faq_info.php' || 
		$_SERVER['SCRIPT_NAME'] == '/site/scripts/faqs_index.php' || 
		$_SERVER['SCRIPT_NAME'] == '/site/scripts/faqs.php') && 
		isset($faq) && $faq->id > 0) {
		$showBottomSupplements = true;
		$contentType = 'faq';
		$itemID = $faq->id;
	}
	else if (($_SERVER['SCRIPT_NAME'] == '/preview/documents_info.php' || $_SERVER['SCRIPT_NAME'] == '/site/scripts/documents_info.php') &&
		isset($page) && $page->id > 0) {
		$showBottomSupplements = true;
		$contentType = 'document';
		$itemID = $page->id;
	}	
	else if (($_SERVER['SCRIPT_NAME'] == '/site/scripts/home_info.php' || 
		$_SERVER['SCRIPT_NAME'] == '/site/scripts/documents.php' || 
		$_SERVER['SCRIPT_NAME'] == '/site/index.php') &&
		isset($homepage) && $homepage->id > 0) {
		$showBottomSupplements = true;
		$contentType = 'homepage';
		$itemID = $homepage->id;
	}
	
	if ($showBottomSupplements) {
		$bottomSupplements = getAllPageSupplements(array(
			'contentType' => $contentType, 
			'itemID' => $itemID, 
			'locationOnPage' => 'bottom'
		), 'position ASC');
		
		foreach ($bottomSupplements as $supplement) {
			$record = $supplement->getRecord();
			if ($record->id > 0) {
				print '<!-- Bottom Supplements -->';
				$publicCode = getSupplementPublicCode($supplement->supplementWidgetID, $supplement->locationOnPage);
				include(HOME . '/site/includes/supplements/' . $publicCode->code);
				print '<!-- End bottom supplements -->';
			}
		}
		
		unset($record);
		unset($publicCode);
	}
?>