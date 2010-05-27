<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	include_once("egov/JaduEGovLPI.php");
	include_once("egov/JaduEGovCouncillors.php");
	include_once("egov/JaduEGovWards.php");	
	include_once("egov/JaduEGovParties.php");
	
	$POSTCODE_ERROR = '<h2 class="warning">This postcode has not been recognised within the '.AUTHORITY_SHORT_NAME.' area - Please try again</h2>';
	$UPRN_ERROR = '<h2 class="warning">This UPRN has not been recognised within the '.AUTHORITY_SHORT_NAME.' area - Please enter your postcode below</h2>';
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
			$sao = trim(substr($sao, 1));
		}
		if ($pao{0} == '0') {
			$pao = trim(substr($pao, 1));
		}
		if (substr($pao, strlen($pao)-2) == ' 0') {
			$pao = trim(substr($pao, 0, -2));
		}
		
		$value = $pao;
		if (strlen($value) > 0) {
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
							$streetname = strtoupper(ltrim(rtrim($_GET['streetname'])));
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
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Who is my councillor? | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Find, Search, Postcode, Councillor, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="Find out who your <?php print METADATA_GENERIC_COUNCIL_NAME;?> ward councillors are form just your postcode" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Who is my councillor?" />
	<meta name="DC.description" lang="en" content="Find out who your <?php print METADATA_GENERIC_COUNCIL_NAME;?> ward councillors are form just your postcode" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
					
<?php
	if ($showForm) {
	
		if ($error['postcode']) {
			print $error['postcode'];
		}
			
		else if ($error['uprn']) {
			print $error['uprn'];
		}
		
		else if ($error['streetname']) {
			print $error['streetname'];
		}
		
		else if ($refinementLevel == -1) {
			print '<p class="first">Use your postcode to find out your Councillor and ward name.</p>';
		}
			
		if ($refinementLevel >= 0) { 
		 	print '<p class="first">The postcode you have entered can lie within more than one ward. Please enter house details or click on one of the properties listed below.</p>';
		} 
?>
			<form id="councillor_search" name="councillor_search" action="http://<?php print $DOMAIN; ?>/site/scripts/whos_my_councillor.php" method="get" class="basic_form">
				<fieldset>
					<legend>Where you live</legend>
					<p>
						<label for="YourPostcode"><?php if ($error['postcode']) print "<strong>! ";?>Postcode <?php if ($error['postcode']) print "</strong>";?></label>
<?php 
		if (!isset($postcode) || $postcode === false || $error['postcode'] || isset($_GET['change'])) { 
?>
						<input class="dob" id="YourPostcode" type="text" name="postcode" value="<?php print $postcode;?>" maxlength="10" />
<?php
		} 
		else { 
			print $postcode; 
?>
						<input type="hidden" name="postcode" value="<?php print $postcode; ?>" />
<?php 
		} 
?>
					</p>
<?php 
		if ($refinementLevel >= 0) { 
			if (sizeof($streets) > 0) { 
?>
					<p>
						<label for="StreetName"><?php if ($error['streetname']) print "<strong>! ";?>Street name <?php if ($error['streetname']) print "</strong>";?></label>
<?php 
				if (sizeof($streets) > 1) { 
?>
						<select id="StreetName" name="streetname" >
							<option value="-1">Please choose ...</option>
<?php 
					foreach ($streets as $street) { 
						print "<option>"; if (strlen($street) > $MAX_SELECT_LENGTH) $street = substr($street, 0, $MAX_SELECT_LENGTH) . "..."; print ucwords(strtolower($street)); print "</option>"; 
					} 
?>
						</select>
<?php 
				} 
			
				else { 
					print ucwords(strtolower($streets[0])); 
?>
						<input type="hidden" name="streetname" value="<?php print $_GET['streetname']; ?>" />
<?php 
				} 
?>
					</p>
<?php 
			} 
		} 

		if ($refinementLevel >= 1) { 
			if (sizeof($buildings) > 0) { 
?>
					<p>
						<label for="YourAddress" ><?php if ($error['uprn']) print "<strong>! ";?>Address<?php if ($error['uprn']) print "</strong>";?> </label>
						<select id="YourAddress" class="field" name="uprn">
							<option value="-1">Please choose...</option>
<?php 
				foreach (array_keys($buildings) as $b) { 
					print "<option value=\"$b\">"; 
					if (strlen($buildings[$b]) > $MAX_SELECT_LENGTH) {
						$buildings[$b] = substr($buildings[$b], 0, $MAX_SELECT_LENGTH) . "..."; print ucwords(strtolower($buildings[$b]))."</option>"; 
					} 
?>
						</select>
					</p>
<?php 
				} 
			} 
		}

?>
					<p class="center">
<?php 
			if ($refinementLevel >= 0) { 
?>
							<input type="submit" class="button" name="change" id="change" value="Change" />
<?php 
			} 
?>									
							<input type="submit" class="button" name="find" id="find" value="Find now" />
					</p>
				</fieldset>
			</form>

<?php
		if (sizeof($wards) == 1) {
			$showSearchAgain = true;
			$wardID = getWardThroughNameMatch($wards[0]);
			if ($wardID > 0) {
    			$councillors = getAllCouncillorsForWard ($wardID, true, true);
?>
    			<h2><?php print $postcode;?></h2>
    			<p class="first">All the properties with the postcode '<strong><?php print $postcode;?></strong>' lie within the ward of '<strong><?php print $wards[0];?></strong>'.</p>
			
    			<h3>Councillors within the ward of <?php print $wards[0];?> are:</h3>
<?php
    			foreach ($councillors as $councillor) {
    				$party = getParty($councillor->partyID);
?>
    				<div class="search_box">
    					<h3><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors_info.php?councillorID=<?php print $councillor->id;?>"><?php print "$councillor->forename $councillor->surname";?></a></h3>
    					<?php if ($councillor->imageURL != "") { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors_info.php?councillorID=<?php print $councillor->id;?>"><img src="http://<?php print $DOMAIN; ?>/images/<?php print $councillor->imageURL;?>" alt="<?php print getImageProperty($councillor->imageURL, 'altText'); ?> " class="contentimage" /></a><?php } ?>
    					<p><strong>Ward:</strong> <?php print $wards[0];?></p>
    					<p><strong>Party:</strong> <?php if ($party->id != -1) print $party->name; else print "Unknown";?></p>
    					<p><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors_info.php?councillorID=<?php print $councillor->id;?>&amp;viewBy=<?php print $viewBy;?>">Full details on <?php print "$councillor->forename $councillor->surname";?></a></p>
    				</div>
<?php
    			}
    		}
		}
		else if (isset($lpi) && $lpi !== false) {
			$showSearchAgain = true;
			$wardID = getWardThroughNameMatch($lpi->ward);
			$councillors = getAllCouncillorsForWard ($wardID, true, true);

			$value = bespokeFormatAddressableObjects ($lpi->sao, $lpi->pao);
			if (!empty($lpi->streetName)) {
				$value .= ', ' . $lpi->streetName;
			}
			
			//	Obtain the locality and put into address line 4
			if (!empty($lpi->locality)) {
				$value .= ', ' . $lpi->locality;
			}
			
			if (!empty($lpi->postTown)) {
				$value .= ', ' . $lpi->postTown;			
			}
			
			if (!empty($lpi->postCode)) {
				$value .= ', ' . $lpi->postCode;
			}			
?>
			<h2><?php print $value; ?></h2>
			<p class="first">This property lies within the ward of '<?php print $lpi->ward;?>'. </p>
			
			<h3>Your Councillors are: </h3>
<?php
			foreach ($councillors as $councillor) {
				$party = getParty($councillor->partyID);
?>
				<div class="search_box">
					<h3><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors_info.php?councillorID=<?php print $councillor->id;?>"><?php print "$councillor->forename $councillor->surname";?></a></h3>
					<?php if ($councillor->imageURL != "") { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors_info.php?councillorID=<?php print $councillor->id;?>" ><img src="http://<?php print $DOMAIN; ?>/images/<?php print $councillor->imageURL;?>" alt="<?php print getImageProperty($councillor->imageURL, 'altText'); ?> " class="contentimage" /></a><?php } ?>
					<p><strong>Ward:</strong> <?php print $lpi->ward;?></p>
					<p><strong>Party:</strong> <?php if ($party->id != -1) print $party->name; else print "Unknown";?></p>
					<p><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors_info.php?councillorID=<?php print $councillor->id;?>&amp;viewBy=<?php print $viewBy;?>">Full details on <?php print "$councillor->forename $councillor->surname";?></a></p>
				</div>
<?php
			}
		}
		else if (sizeof($allLPIs) > 0) {
			$showSearchAgain = true;
?>
			<h2><?php print $postcode;?></h2>
			<p class="first">Listed below are all the properties within postcode '<?php print $postcode;?>':</p>
<?php
			foreach ($allLPIs as $lpi) {
				$value = bespokeFormatAddressableObjects ($lpi->sao, $lpi->pao);
				if (!empty($lpi->streetName)) {
					$value .= ', ' . $lpi->streetName;
				}
				
				//	Obtain the locality and put into address line 4
				if (!empty($lpi->locality)) {
					$value .= ', ' . $lpi->locality;
				}
				
				if (!empty($lpi->postTown)) {
					$value .= ', ' . $lpi->postTown;
				}
?>
				<h3><?php print $value;?></h3>
				<p><a href="http://<?php print $DOMAIN; ?>/site/scripts/whos_my_councillor.php?postcode=<?php print urlencode($lpi->postCode);?>&amp;uprn=<?php print $lpi->uprn;?>">Fetch My Councillors</a></p>
<?php
			}
		}
?>
		<div class="clear"></div>
<?php
		if ($showSearchAgain) {
?>
		<p><a href="http://<?php print $DOMAIN; ?>/site/scripts/whos_my_councillor.php">Search for my councillors again</a></p>
<?php
		}
	}
?>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
