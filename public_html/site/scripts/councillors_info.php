<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("egov/JaduEGovCouncillors.php");
	include_once("egov/JaduEGovWards.php");
	include_once("egov/JaduEGovParties.php");
	include_once("../includes/lib.php"); 
	
	if (isset($_GET['councillorID'])) {
		
		if ($_GET['councillorID'] != -1 && $_GET['councillorID'] != '') {
			$councillor = getCouncillor($_GET['councillorID'], true, true);
		
			if (!is_int($councillor) || $councillor->id != -1) {
				$ward = getWard($councillor->wardID);
				$party = getParty($councillor->partyID);
			}
		}
		else {
			header("Location: ./councillors.php?viewBy=name");
			exit;
		}
	}

	$typeArray = array();
	if (is_array($COUNCILLOR_TYPES)) {
		$typeArray = array_flip($COUNCILLOR_TYPES);
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Councillor '. $councillor->forename .' '. $councillor->surname;
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildCouncillorsURL() .'">Councillors</a></li><li><span>' .encodeHtml($councillor->forename .' '. $councillor->surname) .'</span></li>';
	
	include("councillors_info.html.php");
?>