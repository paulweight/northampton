<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("egov/JaduEGovCouncillors.php");
	include_once("egov/JaduEGovWards.php");
	include_once("egov/JaduEGovParties.php");

	include_once("../includes/lib.php");
	
	$pageTitle = "";
	$array = array();
	
	if (isset($_GET['viewBy'])) {
		switch (mb_strtolower($_GET['viewBy'])) {
			case "name" :
				$pageTitle = "Name";
				$array = getAllCouncillors(true, true);
				break;
			case "ward" :
				$pageTitle = "Ward";
				$array = getAllWards();
				break;
			case "party" :
				$pageTitle = "Political Party";
				$array = getAllParties();
				break;
			case "specificward" :
				if (isset($_GET['wardID'])) {
					$ward = getWard($_GET['wardID']);
					$pageTitle = "Ward: $ward->name";
					$array = getAllCouncillorsForWard($_GET['wardID'], true, true);
				}
				break;
			case "specificparty" :
				if (isset($_GET['partyID'])) {
					$party = getParty($_GET['partyID']);
					$pageTitle = "Political Party: $party->name";
					$array = getAllCouncillorsForParty($_GET['partyID'], true, true);
				}
				break;
		}
	}
	
	if (isset($_POST["councillorSearchSubmit"])) {
		
		$wardVal = "";
		$partyVal = "";
		if (isset($_POST["wardID"]) && $_POST["wardID"] != "any") {
			$wardVal = $_POST["wardID"];
		} else {
			$wardVal = "*";
		}
		if (isset($_POST["partyID"]) && $_POST["partyID"] != "any") {
			$partyVal = $_POST["partyID"];
		} else {
			$partyVal = "*";
		}
		
		$array = getAllCouncillorsForWardAndParty ($wardVal, $partyVal, true, true);
		$viewBy="name";
	}

	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Councillors by '. $pageTitle;
	$MAST_BREADCRUMB = ' <li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildCouncillorsURL() .'" >Councillors</a></li><li><span>'. $pageTitle .'</li>';
	
	include("councillors.html.php");	
?>