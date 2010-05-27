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
						$all_party_types[] = array('shaddow'=>array($type_id=>$councillors));
					}		
				} 
				else if ($party->type == 2) {
					$councillors = getCouncillorOfTypeForParty($party->id, $type, true, true);
					if (!empty($councillors)) {
						$all_party_types[] = 	array('governing'=>array($type_id=>$councillors));
					}					
				}
			}
		}
	}	
	
	$breadcrumb = 'councillorsIndex';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
	<title>Councillors | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s directory of all local Councillors" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Councillors" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s directory of all local Councillors" />	

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ####*###### MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
	
	<h2>Local Councillors</h2>

	<div class="cate_info">
		<ul class="info_left list">
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors.php?viewBy=name">Find councillors by name</a></li>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors.php?viewBy=ward">Find councillors by ward</a></li>
		</ul>
		<ul class="info_right list">
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors.php?viewBy=party">Find councillors by party</a></li>		
		</ul>
	</div>
	

	<p class="first"><?php print METADATA_GENERIC_COUNCIL_NAME;?> is a democratic organisation.  It comprises of <a href="http://<?php print $DOMAIN; ?>/site/scripts/councillors.php?viewBy=name"><?php print $numberOfCouncillors; ?> elected Councillors</a> who are responsible for agreeing policies about provision of services and how the Council's money is spent. The Council employs officers who are responsible for its day to day management.</p>
	<p>Councillors decide which policies the Council should pursue, ensure that they are carried out and monitor services provided to ensure that they are delivered in the most efficient and effective way.</p>
	<p>The local Councillor is there to represent the views and opinions of individuals. It is also his or her responsibility to help those with difficulties which the Council could help solve. <?php print METADATA_GENERIC_COUNCIL_NAME;?> Councillors decide how the Council should carry out its many important functions.</p>
	<p>Local Councillors are elected by the community to decide how the Council should carry out its various activities. They represent public interest as well as individuals living within the ward in which he or she has been elected to serve a term of office.</p>
	<p>To do this they have regular contact with the general public through either Council meetings, telephone calls or surgeries. Surgeries provide an opportunity for any ward resident to go and talk their councillor face to face and these take place on a regular basis.</p>
	<p>Your Councillor will discuss any concerns or problems relating to Council services and listen to your views on issues that you feel are important.</p>
	<p>Councillors are not paid a salary for their work, but they do receive allowances. By law, all members of the Council are required to complete a Declaration of Interest form, the details of which are published annually.</p>

            
<?php
		if (!empty($all_party_types)) {
?>
	<h2>Political leaders</h2>
<?php
	}	

	foreach ($all_party_types as $party_type) {
		if (!empty($party_type['governing'])) {
			foreach($party_type['governing'] as $position=>$councillors) {
				foreach ($councillors as $councillor) {
					if ($councillor->id != "" && $councillor->id != "-1") {
						print '<dl class="person_box">';
						if (!empty($councillor->imageURL)) {
							print '<dt><a href="http://'.$DOMAIN.'/site/scripts/councillors_info.php?councillorID='.$councillor->id.'&amp;viewBy=name">'.$councillor->forename.' '.$councillor->surname.'</a> - '.$position.'</dt>';
						}	
						print '<dd><a href = "http://'.$DOMAIN.'/site/scripts/councillors_info.php'.$councillor->id.'&amp;viewBy=name" ><img src="http://'.$DOMAIN.'/images/'.$councillor->imageURL.'" alt="'.getImageProperty($councillor->imageURL, 'altText').' "/></a></dd>';
						$ward = getWard($councillor->wardID);
						print '<dd>Ward:'.$ward->name.'</dd>';
						$party = getParty($councillor->partyID);
						print '<dd>Party: '.$party->name.'</dd>';
						print '</dl>';
					}
				}
			}
		}
	}		
		
	foreach ($all_party_types as $party_type) {
		if (!empty($party_type['shaddow'])) {
			foreach($party_type['shaddow'] as $position=>$councillors) {
				foreach ($councillors as $councillor) {
					if ($councillor->id != "" && $councillor->id != "-1") {
						print '<dl class="person_box">';
						if (!empty($councillor->imageURL)) {							
							print '<dt><a href="http://'.$DOMAIN.'/site/scripts/councillors_info.php?councillorID='.$councillor->id.'&amp;viewBy=name">'.$councillor->forename.' '.$councillor->surname.'</a> - '.$position.'</dt>';
						}								
						print '<dd><a href = "http://'.$DOMAIN.'/site/scripts/councillors_info.php'.$councillor->id.'&amp;viewBy=name"><img src="http://'.$DOMAIN.'/images/'.$councillor->imageURL.'" alt="'.getImageProperty($councillor->imageURL, 'altText').' " /></a></dd>';
						$ward = getWard($councillor->wardID);
						print '<dd>Ward:'.$ward->name.'</dd>';
						$party = getParty($councillor->partyID);
						print '<dd>'.$party->name.'</dd>';
						print '</dl>';
					}
				}
			}
		}
	}
?>            
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>			
<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>