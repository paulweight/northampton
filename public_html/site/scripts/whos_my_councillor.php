<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	include_once("egov/JaduEGovLPI.php");
	include_once("egov/JaduEGovCouncillors.php");
	include_once("egov/JaduEGovWards.php");	
	include_once("egov/JaduEGovParties.php");
	
	$POSTCODE_ERROR = '<h2 class="warning">This postcode has not been recognised within the '.METADATA_GENERIC_NAME .' area - Please try again</h2>';
	$UPRN_ERROR = '<h2 class="warning">This UPRN has not been recognised within the '.METADATA_GENERIC_NAME .' area - Please enter your postcode below</h2>';
	$STREETNAME_ERROR = '<h2 class="warning">Please choose a valid street name</h2>';
	$MAX_SELECT_LENGTH = 36;

	$errors = array();
	
	$showForm = true;
	$showSearchAgain = false;
	$refinementLevel = -1;

	//	May be made spcific to each council that is not using BS7666 addressable objects
	//	Is purely for printing out addresses to users.
	function bespokeFormatAddressableObjects ($sao, $pao)
	{
		$sao = trim($sao);
		$pao = trim($pao);
		if ($sao{0} == '0') {
			$sao = trim(mb_substr($sao, 1));
		}
		if ($pao{0} == '0') {
			$pao = trim(mb_substr($pao, 1));
		}
		if (mb_substr($pao, mb_strlen($pao)-2) == ' 0') {
			$pao = trim(mb_substr($pao, 0, -2));
		}
		
		$value = $pao;
		if (mb_strlen($value) > 0) {
			$value .= ', ';
		}
		$value .= $sao;
								
		return $value;
	}
	
	function addressComparison ($a, $b)
	{
		$valueA = bespokeFormatAddressableObjects ($a->sao, $a->pao);
		$valueB = bespokeFormatAddressableObjects ($b->sao, $b->pao);

		if ($valueA == $valueB) {
			return 0;
		}
		return ($valueA < $valueB) ? -1 : 1;
	}

	
	if (isset($_GET['postcode'])) {
	
		if (isset($_GET['change'])) {
			$postcode = $_GET['postcode'];
			unset($streetname);
			unset($uprn);
		}
		else {
		
			$showForm = false;
			$postcode = validateUKPostcode ($_GET['postcode']);
				
			if ($postcode === false) {
				$error['postcode'] = $POSTCODE_ERROR;
				$postcode = $_GET['postcode']; // put it back to what they entered so they can see in the form
				$showForm = true;
			}
			else {				
				
				//	If we can create a match then do so
				if (isset($_GET['uprn'])) {
					$uprn = $_GET['uprn'];
					$lpi = getLPIForUPRN ($uprn);
					
					if ($lpi === false) {
						$error['uprn'] = $UPRN_ERROR;
					}
					$streetname = "";
				}
				else {
					$refinementLevel = 0;
					
					if (isset($_GET['streetname'])) {
						if ($_GET['streetname'] != "-1") {
							$streetname = mb_strtoupper(ltrim(rtrim($_GET['streetname'])));
							$refinementLevel = 1;
						}
						else {
							$error['streetname'] = $STREETNAME_ERROR;
						}
					}
				}
				
				//	If we still have no match, keep going with the listing and refining
				if (!isset($lpi) || $lpi === false) {
					$showForm = true;
					$allLPIs = getAllLPIsForPostCode($postcode);
				}
				
				if (sizeof($allLPIs) > 0) {
					$buildings = array();
					$streets = array();
					$wards = array();
					
					foreach ($allLPIs as $anLPI) {
						if (!in_array($anLPI->ward, $wards) && !empty($anLPI->ward)) {
							$wards[] = $anLPI->ward;
						}
					}
					
					if (sizeof($wards) == 1) {
						$showForm = false;
					}
					else {
						
						if ($refinementLevel >= 1) {
							$new = array();
							foreach ($allLPIs as $l) {
								if ($streetname == $l->streetName) {
									$new[] = $l;
								}
							}
							$allLPIs = $new;
						}
						
						if (sizeof($allLPIs) == 1) {
							$lpi = $allLPIs[0];
							$showForm = false;
						}
						else {
							foreach ($allLPIs as $anLPI) {
								$value = bespokeFormatAddressableObjects ($anLPI->sao, $anLPI->pao);
								
								if (!in_array($value, $buildings) && !empty($value)) {
									$buildings[$anLPI->uprn] = $value;
								}
								if (!in_array($anLPI->streetName, $streets) && !empty($anLPI->streetName)) {
									$streets[] = $anLPI->streetName;
								}
								if (!in_array($anLPI->ward, $wards) && !empty($anLPI->ward)) {
									$wards[] = $anLPI->ward;
								}
							}
						
							if (sizeof($streets) == 1) {
								$streetname = $streets[0];
								$refinementLevel = 1;
							}
							else {
								sort($streets);
							}
							
							asort($buildings);
							
							usort($allLPIs, 'addressComparison');
						}
					}
				}
				else if (!isset($lpi) || $lpi === false) {
					$error['postcode'] = $POSTCODE_ERROR;
					$showForm = true;
					$refinementLevel = -1;
				}
			}
		}
	}
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = "Who's my Councillor?";
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . getSiteRootURL() . buildCouncillorsURL() .'">Councillors</a></li><li><span>Who\'s my Councillor?</span></li>';
	
	include("whos_my_councillor.html.php");
?>