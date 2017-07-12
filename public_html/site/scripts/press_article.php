<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduPressReleases.php");
	include_once("JaduAppliedCategories.php");
	include_once("JaduMetadata.php");
	include_once("utilities/JaduMostPopular.php");
	
	// Validate inputs
	if (!isset($_GET['pressReleaseID']) || !is_numeric($_GET['pressReleaseID'])) {
		header('Location: ' . buildPressURL());
		exit();
	}
	
	// Default to approved/live
	$approvedOnly = true;
	$liveOnly = true;
	
	// Check whether an administrator is previewing the page
	if (isset($_GET['adminID']) && isset($_GET['preview']) && isset($_GET['expire'])) {
		include_once('utilities/JaduAdministrators.php');
		$approvedOnly = $liveOnly = !validateAdminPreviewHash(getAdministrator($_GET['adminID']), $_GET['preview'], $_GET['expire']);
	}
	
	if (isset($_GET['pressReleaseID'])) {
		$pressRelease = getPressReleases($_GET['pressReleaseID'], $liveOnly, $approvedOnly);
		if ($pressRelease->id == -1) {
			// Need to issue a 404 to prevent google from indexing the result
			header("HTTP/1.0 404 Not Found");
		}
	}
	else {
		$pressRelease = getTopPressReleases($liveOnly, $approvedOnly);
		if ($pressRelease->id < 1) {
			$pressRelease = getLastPressReleases($liveOnly, $approvedOnly);
		}
	}

	// If the press release item doesnt exist, re-direct to index
	if ($pressRelease->id < 1) {
		header('Location: ' . buildPressURL());
		exit();
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = $pressRelease->title;
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildPressURL() . '" >Press releases</a></li><li><span>'. encodeHtml($pressRelease->title) .'</span></li>';

	include("press_article.html.php");
?>