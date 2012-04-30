<?php	
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("egov/JaduEGovJoinedUpServices.php");

	if (mb_strlen($_GET['startsWith']) > 1 || is_numeric($_GET['startsWith'])) {
		header("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}

	if (!isset($_GET['startsWith']) || !preg_match("/^[a-zA-Z]+$/", $_GET['startsWith'])) {// so a user can change to lower or upper if required
		$startsWith = 'A';
	}
	else {
		$startsWith = $_GET['startsWith'];
	}

	$startsWith = mb_strtoupper($startsWith);

	$allServices = getAllServicesWithTitleAliases(true, true);
	$servicesList = getAllServicesWithTitleAliasesStartingWith($allServices, $startsWith);
	$validLetters = getAllValidAlphabetLetters($allServices);
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Council services beginning with '. $startsWith ;
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildAToZURL() . '" >Council services</a></li><li><span>Beginning with '. encodeHtml($startsWith) .'</span></li>';
	
	include("az_index.html.php");
	
?>