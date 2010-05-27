<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	
	include_once("egov/JaduEGovCouncillors.php");
	include_once("egov/JaduEGovWards.php");
	include_once("egov/JaduEGovParties.php");

	include_once("../includes/lib.php");
	
	$pageTitle = "";
	$array = array();
	
	if (isset($viewBy)) {
		switch (strtolower($viewBy)) {
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
				if (isset($wardID)) {
					$ward = getWard($wardID);
					$pageTitle = "Ward: $ward->name";
					$array = getAllCouncillorsForWard ($wardID, true, true);
				}
				break;
			case "specificparty" :
				if (isset($partyID)) {
					$party = getParty($partyID);
					$pageTitle = "Political Party: $party->name";
					$array = getAllCouncillorsForParty ($partyID, true, true);
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

	$breadcrumb = 'councillorsView';	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
	<title>Councillors | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="councillor, search, find, finder, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s directory of all local Councillors" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Find Councillors" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s directory of all local Councillors" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<p class="first">Find Councillors <a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors.php?viewBy=name">by name</a>, <a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors.php?viewBy=ward">by ward</a> or <a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors.php?viewBy=party">by party</a></p>

<?php
	$splitArray = splitArray($array);
	if (sizeof($splitArray['left']) > 0) {			
		switch (strtolower($viewBy)) {
			case "name" :
			case "specificward" :
			case "specificparty" :
				print '<dl class="person_box">';
				foreach ($splitArray['left'] as $councillor) {
					$ward = getWard($councillor->wardID);
					$party = getParty($councillor->partyID);
?>
		<dt><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors_info.php?councillorID=<?php print $councillor->id;?>&amp;viewBy=<?php print $viewBy;?>"><?php print "$councillor->forename $councillor->surname";?></a></dt>
<?php
					if ($councillor->imageURL != "") { 
?>
		<dd><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors_info.php?councillorID=<?php print $councillor->id;?>&amp;viewBy=<?php print $viewBy;?>"><img src="http://<?php print $DOMAIN; ?>/images/<?php print $councillor->imageURL;?>" alt="<?php print "$councillor->forename $councillor->surname";?>" /></a></dd>
<?php 
					} 
?>
		<dd>Ward: <?php if ($ward->id != -1) print $ward->name; else print "Unknown";?></dd>
		<dd>Party: <?php if ($party->id != -1) print $party->name; else print "Unknown";?></dd>

<?php
				}
				print '</dl>';
				
				print '<dl class="person_box">';
				foreach ($splitArray['right'] as $councillor) {
					$ward = getWard($councillor->wardID);
					$party = getParty($councillor->partyID);
?>
		<dt><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors_info.php?councillorID=<?php print $councillor->id;?>&amp;viewBy=<?php print $viewBy;?>"><?php print "$councillor->forename $councillor->surname";?></a></dt>
<?php
					if ($councillor->imageURL != "") { 
?>
		<dd><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors_info.php?councillorID=<?php print $councillor->id;?>&amp;viewBy=<?php print $viewBy;?>"><img src="http://<?php print $DOMAIN; ?>/images/<?php print $councillor->imageURL;?>" alt="<?php print "$councillor->forename $councillor->surname";?>" /></a></dd>
<?php 
					} 
?>
		<dd>Ward: <?php if ($ward->id != -1) print $ward->name; else print "Unknown";?></dd>
		<dd>Party: <?php if ($party->id != -1) print $party->name; else print "Unknown";?></dd>

<?php
				}                    
				print '</dl>';
			break;
					   
			case "ward" :   
				print "<ul class=\"list\">";
				foreach ($array as $ward) {
?>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors.php?viewBy=specificWard&amp;wardID=<?php print $ward->id;?>"><?php print $ward->name;?></a></li>
<?php
				}
				print "</ul>";
			break;
						
			case "party" :
				print "<ul class=\"list\">";
				foreach ($array as $party) {
?>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors.php?viewBy=specificParty&amp;partyID=<?php print $party->id;?>"><?php print $party->name;?></a></li>
<?php
				}
				print "</ul>";
			break;
		}
	}
    else {
?>
            <p class="first">Sorry. There were no matches found.</p>
<?php
	}
?>
   
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>