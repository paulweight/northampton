<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("egov/JaduEGovCouncillors.php");
	include_once("egov/JaduEGovWards.php");
	include_once("egov/JaduEGovParties.php");
	
	include_once("../includes/lib.php"); 
	
	if (isset($councillorID)) {
		
		if ($councillorID != -1 && $councillorID != "") {
			$councillor = getCouncillor($councillorID, true, true);
		
			if ($councillor != -1) {
				$ward = getWard($councillor->wardID);
				$party = getParty($councillor->partyID);
			}
		} else {
			header("Location: ./councillors.php?viewBy=name");
			exit;
		}
	}

	$breadcrumb = 'councillorInfo';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
	<title>Councillor <?php print "$councillor->forename $councillor->surname";?> | <?php if ($ward->id != -1) print $ward->name; else print "Unknown Ward"; ?> Ward | <?php if ($party->id != -1) print $party->name; else print "Unknown"; ?> Party | <?php print METADATA_GENERIC_COUNCIL_NAME; ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print "$councillor->forename $councillor->surname";?>, <?php if ($ward->id != -1) print $ward->name; else print "Unknown Ward"; ?> Ward, <?php if ($party->id != -1) print $party->name; else print "Unknown"; ?> Party, local authority, councillors, councillor, member, elect, MPs, MEPs, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="Details of Councillor <?php print "$councillor->forename $councillor->surname";?> of <?php if ($ward->id != -1) print $ward->name; else print "Unknown Ward"; ?> Ward - <?php if ($party->id != -1) print $party->name; else print "Unknown"; ?> Party" />

	<meta name="DC.title" lang="en" content="Councillor <?php print "$councillor->forename $councillor->surname";?> - <?php if ($ward->id != -1) print $ward->name; else print "Unknown Ward"; ?> Ward - <?php if ($party->id != -1) print $party->name; else print "Unknown"; ?> Party - <?php print METADATA_GENERIC_COUNCIL_NAME; ?>" />
	<meta name="DC.identifier" content="http://<?php print $DOMAIN.$_SERVER['PHP_SELF']."?".htmlspecialchars($_SERVER['QUERY_STRING']);?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<?php include("../includes/opening.php"); ?>
<!-- ####################################### -->

<?php 
	if ($councillor->imageURL != "") { 
?>
	<img class="main_image" src="http://<?php print $DOMAIN; ?>/images/<?php print $councillor->imageURL;?>" alt="<?php print "$councillor->forename $councillor->surname";?>" />
<?php 
	} 
?>
	<p class="first">Ward: <?php if ($ward->id != -1) print $ward->name; else print "Unknown Ward"; ?></p>
	<p class="first">Party: <?php if ($party->id != -1) print $party->name; else print "Unknown"; ?></p>
  	
	<div class="contactPage">
		<ul>
<?php
	if ($councillor->telephone != "") { 
		print "<li class=\"icoPhone\">Telephone: $councillor->telephone</li>"; 
	} 
	if ($councillor->fax != "") { 
		print "<li class=\"icoFax\">Fax: $councillor->fax</li>"; 
	} 
	if ($councillor->email != "") { 
		print "<li class=\"icoEmail\">Email: <a href=\"mailto:$councillor->email\">$councillor->email</a></li>"; 
	} 
	if ($councillor->address != "") { 
		print "<li class=\"icoAddress\">" . nl2br($councillor->address) . "</li>"; 
	} 

?>
		</ul>
	</div>         
	<div class="byEditor">
		<?php print $councillor->content;?>
	</div>

    
<?php 
        if ($ward->id != -1) {
            $otherCouncillors = getAllCouncillorsForWard($ward->id, true, true);
            if (sizeof($otherCouncillors) > 1) { //  1 to discard above from being in list
				$splitArray = splitArray($otherCouncillors);
?>
  
            <h2>Other Councillors for <?php print $ward->name;?></h2>    
<?php
 				print '<dl class="person_box">';	
               	foreach ($splitArray['left'] as $otherCouncillor) {
					$ward = getWard($otherCouncillor->wardID);
					$party = getParty($otherCouncillor->partyID);
?>						
				<dt>
					<a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors_info.php?councillorID=<?php print $otherCouncillor->id;?>&amp;viewBy=<?php print $viewBy;?>">
						<strong><?php print "$otherCouncillor->forename $otherCouncillor->surname";?></strong>
					</a>
				</dt>

<?php
				if ($councillor->imageURL != "") { 
?>
				<dd><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors_info.php?councillorID=<?php print $otherCouncillor->id;?>&amp;viewBy=<?php print $viewBy;?>"><img src="http://<?php print $DOMAIN; ?>/images/<?php print $otherCouncillor->imageURL;?>" alt="<?php print "$otherCouncillor->forename $otherCouncillor->surname";?>" /></a></dd>
<?php 
				} 
?>
				<dd>Ward: <?php if ($ward->id != -1) print $ward->name; else print "Unknown";?></dd>
				<dd>Party: <?php if ($party->id != -1) print $party->name; else print "Unknown";?></dd>
<?php
                }
				print '</dl>';
				print '<dl class="person_box">';	
               	foreach ($splitArray['right'] as $otherCouncillor) {
					$ward = getWard($otherCouncillor->wardID);
					$party = getParty($otherCouncillor->partyID);
?>						
				<dt>
					<a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors_info.php?councillorID=<?php print $otherCouncillor->id;?>&amp;viewBy=<?php print $viewBy;?>">
						<strong><?php print "$otherCouncillor->forename $otherCouncillor->surname";?></strong>
					</a>
				</dt>

<?php
				if ($councillor->imageURL != "") { 
?>
				<dd><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors_info.php?councillorID=<?php print $otherCouncillor->id;?>&amp;viewBy=<?php print $viewBy;?>"><img src="http://<?php print $DOMAIN; ?>/images/<?php print $otherCouncillor->imageURL;?>" alt="<?php print "$otherCouncillor->forename $otherCouncillor->surname";?>" /></a></dd>
<?php 
				} 
?>
				<dd>Ward: <?php if ($ward->id != -1) print $ward->name; else print "Unknown";?></dd>
				<dd>Party: <?php if ($party->id != -1) print $party->name; else print "Unknown";?></dd>
<?php
                }
				print '</dl>';
            }
        }
?>
			<br class="clear" />
		<p class="first">Find Councillors <a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors.php?viewBy=name">by name</a>, <a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors.php?viewBy=ward">by ward</a>, <a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors.php?viewBy=party">by party</a> or <a href="http://<?php print $DOMAIN; ?>/site/scripts/whos_my_councillor.php">by Postcode</a></p>
			             
		<!-- The Contact box -->
		<?php include("../includes/contactbox.php"); ?>
		<!-- END of the Contact box -->

			
<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>