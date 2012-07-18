<?php
include('../../404.php');
exit;
?>

<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("egov/JaduEGovMeetingMinutes.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");

	include_once("../includes/lib.php");

	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		$allMeetingMinutes = getAllMeetingMinutesWithCategory ($_GET['categoryID'], true);
		
		//	Category Links
		$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
		$allCategories = $lgclList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, MEETING_MINUTES_APPLIED_CATEGORIES_TABLE, true);
		
		//	Category Links
		$currentCategory = $lgclList->getCategory($_GET['categoryID']);
		if ($currentCategory === null) {
			include_once("../../404.php");
			exit;	
		}
		$dirTree = $lgclList->getFullPath($_GET['categoryID']);
	}
	else {
		header("Location: meetings_index.php");
		exit;
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Agendas, Reports and Minutes';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildMeetingsURL(). '" >Agendas, Reports and Minutes</a></li>';
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMeetingsURL($parent->id) .'" >'. encodeHtml($parent->name) .'</a></li>';
		}
		else {
			$MAST_BREADCRUMB .= '<li><span>'. encodeHtml($parent->name) .'</span></li>';
		}
		$count++;
		$levelNo++;
	}
	
	include("meetings.html.php");
?>