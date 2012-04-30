<?php
    include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("egov/JaduEGovCouncillors.php");
	include_once("egov/JaduEGovParties.php");
	include_once("egov/JaduEGovWards.php");

	$allCouncillors = getAllCouncillors(true, true);
	$numberOfCouncillors = sizeof($allCouncillors);
	
	$allParties = getAllParties();
	
	$all_party_types = array();
	
	$party_types = array();
	
	foreach ($allParties as $party) {
		foreach ($COUNCILLOR_TYPES as $type_id=>$type) {
			if ($type != 0) {
				if ($party->type == 1) {
					$councillors = getCouncillorOfTypeForParty($party->id, $type, true, true);
					if (!empty($councillors)) {
						$all_party_types[] = array('shadow'=>array($type_id=>$councillors));
					}		
				} 
				else if ($party->type == 2) {
					$councillors = getCouncillorOfTypeForParty($party->id, $type, true, true);
					if (!empty($councillors)) {
						$all_party_types[] = array('governing'=>array($type_id=>$councillors));
					}					
				}
			}
		}
	}	
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Councillors';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Councillors</span></li>';
	
	include("council_democracy_index.html.php");
?>