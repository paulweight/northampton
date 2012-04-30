<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("egov/JaduEGovJoinedUpServices.php");
	include_once("egov/JaduEGovJoinedUpServicesContacts.php");
	include_once("egov/JaduPIDList.php");
	include_once("JaduCategories.php");
	include_once("websections/JaduFAQ.php");
	include_once("websections/JaduDocuments.php");
	include_once("egov/JaduXFormsForm.php");
	include_once("utilities/JaduMostPopular.php");

	$PID = null;
	$allLGCLCategories = array();
	
	// Default to approved/live
	$approvedOnly = true;
	$liveOnly = true;
	
	// Check whether an administrator is previewing the page
	if (isset($_GET['adminID']) && isset($_GET['preview']) && isset($_GET['expire'])) {
		include_once('utilities/JaduAdministrators.php');
		$approvedOnly = $liveOnly = !validateAdminPreviewHash(getAdministrator($_GET['adminID']), $_GET['preview'], $_GET['expire']);
	}
	
	if (isset($_GET['pid']) && $_GET['pid'] > 0) {
		$services = getAllServicesWithPID_ID($_GET['pid']);
	
		if (sizeof($services) > 0) {
			$serviceID = $services[0]->id;
		}
	}

	if (isset($_GET['serviceID']) && $_GET['serviceID'] > 0) {
		$serviceID = intval($_GET['serviceID']);
	}
	
	$service = null;
	
	if (isset($serviceID) && $serviceID > 0) {
		$service = getService($serviceID, $liveOnly, $approvedOnly);
		if ($service->id > 0) {
			$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
			$allCategories = getAllCategoriesOfType(SERVICES_CATEGORIES_TABLE, $service->id, BESPOKE_CATEGORY_LIST_NAME);
			foreach ($allCategories as $category) {
				$allLGCLCategories[] = $lgclList->getCategory($category->categoryID);
			}
			
			if ($service->PID_ID > 0) {
				$PID = getPIDListElement($service->PID_ID);
			}
			
			if ($service->redirect) {
				header('Location: ' . $service->externalURL);
				exit;
			}
		}
	}
	
	if ($service == null || $service->id < 1) {
	    header("HTTP/1.0 404 Not Found");
	}

	$allServices = getAllServicesWithTitleAliases(true, true);
	$validLetters = getAllValidAlphabetLetters($allServices);

	$title = $service->title;
	if (mb_strlen($title) > 64) {
		$title = mb_substr($title, 0, 61) . "...";
	}

	$MAST_HEADING = 'A to Z';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildAToZURL() .'">A to Z of services</a></li><li class="bc_end"><span>'. encodeHtml($service->title) .'</span></li>';
	
	include("services_info.html.php");
?>