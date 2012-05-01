<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Find, Search, Postcode, Councillor, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="Find out who your <?php print encodeHtml(METADATA_GENERIC_NAME); ?> ward councillors are form just your postcode" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Who is my councillor?" />
	<meta name="DC.description" lang="en" content="Find out who your <?php print encodeHtml(METADATA_GENERIC_NAME); ?> ward councillors are form just your postcode" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
					
<?php
	if ($showForm) {
	
		if (isset($error['postcode'])) {
			print $error['postcode'];
		}
		else if (isset($error['uprn'])) {
			print $error['uprn'];
		}
		else if (isset($error['streetname'])) {
			print $error['streetname'];
		}
		else if ($refinementLevel == -1) {
			print '<p>Use your postcode to find out your Councillor and ward name.</p>';
		}
			
		if ($refinementLevel >= 0) { 
		 	print '<p>The postcode you have entered can lie within more than one ward. Please enter house details or click on one of the properties listed below.</p>';
		} 
?>
	
	<form id="councillor_search" action="<?php print getSiteRootURL(); ?>/site/scripts/whos_my_councillor.php" method="get" class="basic_form">
		<fieldset>
			<legend>Where you live</legend>
			
				<label for="YourPostcode"<?php if (isset($error['postcode'])) print "class=\"error\" ";?>>Postcode <?php if (isset($error['postcode'])) print "</strong>";?></label>
<?php 
		if (!isset($_GET['postcode']) || $postcode === false || isset($error['postcode']) || isset($_GET['change'])) { 
?>
				<input class="field" id="YourPostcode" type="text" name="postcode" value="<?php print isset($postcode) ? encodeHtml($postcode) : '';?>" maxlength="10" />
<?php
		} 
		else {
		    print encodeHtml($postcode);
?>
				<input type="hidden" name="postcode" value="<?php print encodeHtml($postcode); ?>" />
<?php 
		} 
?>
			
<?php 
		if ($refinementLevel >= 0) { 
			if (sizeof($streets) > 0) { 
?>
			
				<label for="StreetName"><?php if (isset($error['streetname'])) print "<strong>! ";?>Street name <?php if (isset($error['streetname'])) print "</strong>";?></label>
<?php 
				if (sizeof($streets) > 1) { 
?>
				<select id="StreetName" name="streetname" class="select">
					<option value="-1">Please choose ...</option>
<?php 
					foreach ($streets as $street) { 
					print "<option>"; if (mb_strlen($street) > $MAX_SELECT_LENGTH) $street = mb_substr($street, 0, $MAX_SELECT_LENGTH) . "..."; print encodeHtml(ucwords(mb_strtolower($street))); print "</option>"; 
					} 
?>
				</select>
<?php 
				} 
				else { 
					print encodeHtml(ucwords(mb_strtolower($streets[0]))); 
?>
				<input type="hidden" name="streetname" value="<?php print encodeHtml($_GET['streetname']); ?>" />
<?php 
				} 
?>
			
<?php 
			} 
		} 

		if ($refinementLevel >= 1) { 
			if (sizeof($buildings) > 0) { 
?>
			
				<label for="YourAddress" ><?php if ($error['uprn']) print "<strong>! ";?>Address<?php if ($error['uprn']) print "</strong>";?> </label>
				<select id="YourAddress" class="field" name="uprn" class="select">
					<option value="-1">Please choose...</option>
<?php 
				foreach (array_keys($buildings) as $b) { 
					print "<option value=\"$b\">"; 
					if (mb_strlen($buildings[$b]) > $MAX_SELECT_LENGTH) {
						$buildings[$b] = mb_substr($buildings[$b], 0, $MAX_SELECT_LENGTH) . "...";
					}
					print encodeHtml(ucwords(mb_strtolower($buildings[$b])))."</option>"; 
				} 
?>
				</select>
			
<?php 
			} 
		}

?>
			
<?php 
		if ($refinementLevel >= 0) { 
?>
				<input type="submit" class="button" name="change" id="change" value="Change" /> 
<?php 
		} 
?>									
				<input type="submit" class="genericButton grey" name="find" id="find" value="Find now" />
			
		</fieldset>
	</form>

<?php
	}

		if (isset($wards) && sizeof($wards) == 1) {
			$showSearchAgain = true;
			$wardID = getWardThroughNameMatch($wards[0]);
			if ($wardID > 0) {
    			$councillors = getAllCouncillorsForWard ($wardID, true, true);
?>
    			<h2><?php print encodeHtml($postcode); ?></h2>
    			<p>All the properties with the postcode '<strong><?php print encodeHtml($postcode); ?></strong>' lie within the ward of '<strong><?php print encodeHtml($wards[0]); ?></strong>'.</p>
			
    			<h3>Councillors within the ward of <?php print $wards[0];?> are:</h3>
			<ul>
<?php
    			foreach ($councillors as $councillor) {
    				$party = getParty($councillor->partyID);
?>
    				<li>
    					<h3><a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id); ?>"><?php print encodeHtml($councillor->forename . ' ' . $councillor->surname);?></a></h3>
    					<?php if ($councillor->imageURL != "") { ?><a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id); ?>"><img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($councillor->imageURL); ?>" alt="<?php print encodeHtml(getImageProperty($councillor->imageURL, 'altText')); ?> " class="contentimage" /></a><?php } ?>
    					<p><strong>Ward:</strong> <?php print encodeHtml($wards[0]); ?></p>
    					<p><strong>Party:</strong> <?php if ($party->id != -1) print encodeHtml($party->name); else print "Unknown";?></p>
    					<p><a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id); ?>?viewBy=<?php print encodeHtml($viewBy); ?>">Full details on <?php print encodeHtml($councillor->forename . ' ' . $councillor->surname); ?></a></p>
    				</li>
<?php
    			}
?>
			</ul>
<?php
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
			
			// Get the USRN
			$allEGovLPIToStreets = getAllEGovLPIToStreetsForVariable('uprn', $lpi->uprn);
			
			$street = false;
			if (!empty($allEGovLPIToStreets)) {
				$street = getEGovStreetForUSRN($allEGovLPIToStreets[0]->usrn);
			}
			
			if ($street && !empty($street->town)) {
				$value .= ', ' . $street->town;
			}
			
			if (!empty($lpi->postTown) && (empty($street) OR $street->town != $lpi->postTown)) {
				$value .= ', ' . $lpi->postTown;
			}
			
			if (!empty($lpi->postCode)) {
				$value .= ', ' . $lpi->postCode;
			}
?>
	<h2><?php print encodeHtml($value); ?></h2>
	<p>This property lies within the ward of '<?php print encodeHtml($lpi->ward);?>'. </p>
			
	<h3>Your Councillors are: </h3>
	<ul>
<?php
			foreach ($councillors as $councillor) {
				$party = getParty($councillor->partyID);
?>
				<li>
					<h3><a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id); ?>"><?php print encodeHtml($councillor->forename . ' ' . $councillor->surname);?></a></h3>
					<?php if ($councillor->imageURL != "") { ?><a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id); ?>" ><img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($councillor->imageURL);?>" alt="<?php print encodeHtml(getImageProperty($councillor->imageURL, 'altText')); ?> " class="contentimage" /></a><?php } ?>
					<p><strong>Ward:</strong> <?php print encodeHtml($lpi->ward); ?></p>
					<p><strong>Party:</strong> <?php if ($party->id != -1) print encodeHtml($party->name); else print "Unknown";?></p>
					<p><a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id); ?>">Full details on <?php print encodeHtml($councillor->forename . ' ' . $councillor->surname); ?></a></p>
				</li>
<?php
			}
?>
	</ul>
<?php
		}
		else if (isset($allLPIs) && sizeof($allLPIs) > 0) {
			$showSearchAgain = true;
?>
	<h2><?php print encodeHtml($postcode);?></h2>
	<p>Listed below are all the properties within postcode '<?php print encodeHtml($postcode);?>':</p>
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
				
				// Get the USRN
				$allEGovLPIToStreets = getAllEGovLPIToStreetsForVariable('uprn', $lpi->uprn);
				
				$street = false;
				if (!empty($allEGovLPIToStreets)) {
					$street = getEGovStreetForUSRN($allEGovLPIToStreets[0]->usrn);
				}
				
				if ($street && !empty($street->town)) {
					$value .= ', ' . $street->town;
				}
				
				if (!empty($lpi->postTown) && (empty($street) OR $street->town != $lpi->postTown)) {
					$value .= ', ' . $lpi->postTown;
				}
?>
	<h3><?php print encodeHtml($value);?></h3>
	<p><a href="<?php print getSiteRootURL() . buildCouncillorLookupURL(); ?>?postcode=<?php print encodeHtml($lpi->postCode);?>&amp;uprn=<?php print encodeHtml($lpi->uprn);?>">Fetch My Councillors</a></p>
<?php
			}
		}
?>
		<div class="clear"></div>
<?php
		if ($showSearchAgain) {
?>
		<p><a href="<?php print getSiteRootURL() .buildCouncillorLookupURL(); ?>">Search for my councillors again</a></p>
<?php
		}
?>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
