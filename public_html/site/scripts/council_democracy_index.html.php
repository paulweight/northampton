<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>s directory of all local Councillors" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Councillors" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>s directory of all local Councillors" />	

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ####*###### MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
	
	<p><?php print encodeHtml(METADATA_GENERIC_NAME); ?> is a democratic organisation.  It comprises of <a href="<?php print getSiteRootURL() . buildCouncillorsGroupURL('name'); ?>"><?php print (int) $numberOfCouncillors; ?> elected Councillors</a> who are responsible for agreeing policies about provision of services and how the Council's money is spent. The Council employs officers who are responsible for its day to day management.</p>
	<p>Councillors decide which policies the Council should pursue, ensure that they are carried out and monitor services provided to ensure that they are delivered in the most efficient and effective way.</p>
	<p>The local Councillor is there to represent the views and opinions of individuals. It is also his or her responsibility to help those with difficulties which the Council could help solve. <?php print encodeHtml(METADATA_GENERIC_NAME); ?> Councillors decide how the Council should carry out its many important functions.</p>
	<p>Local Councillors are elected by the community to decide how the Council should carry out its various activities. They represent public interest as well as individuals living within the ward in which he or she has been elected to serve a term of office.</p>
	<p>To do this they have regular contact with the general public through either Council meetings, telephone calls or surgeries. Surgeries provide an opportunity for any ward resident to go and talk their councillor face to face and these take place on a regular basis.</p>
	<p>Your Councillor will discuss any concerns or problems relating to Council services and listen to your views on issues that you feel are important.</p>
	<p>Councillors are not paid a salary for their work, but they do receive allowances. By law, all members of the Council are required to complete a Declaration of Interest form, the details of which are published annually.</p>
    
<?php
		if (!empty($all_party_types)) {
?>
	<h2>Political Leaders</h2>
	<ul class="archive">
<?php
		}	

		foreach ($all_party_types as $party_type) {
			if (!empty($party_type['governing'])) {
				foreach($party_type['governing'] as $position=>$councillors) {
					foreach ($councillors as $councillor) {
						if ($councillor->id != "" && $councillor->id != "-1") {
							print '<li class="lead">';
							if (!empty($councillor->imageURL)) {
								print '<a href="'. getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id) .'"><img src="' . getStaticContentRootURL() . '/images/' . encodeHtml($councillor->imageURL) . '" alt="' . encodeHtml(getImageProperty($councillor->imageURL, 'altText')) . '" /></a>';
							}	
							print '<h3><a href="' . getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id) .'">'. encodeHtml($councillor->forename) . ' ' . encodeHtml($councillor->surname) . '</a> - ' . encodeHtml($position) . '</h3>';
							$ward = getWard($councillor->wardID);
							print '<p>Ward: ' . encodeHtml($ward->name) . '</p>';
							$party = getParty($councillor->partyID);
							print '<p>Party: ' . encodeHtml($party->name) . '</p>';
							print '<p><a href="' . getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id) .'">Full details for  '.encodeHtml($councillor->forename).' '.encodeHtml($councillor->surname).'</a></p>';
							print '<div class="clear"></div></li>';
						}
					}
				}
			}
		}	foreach ($all_party_types as $party_type) {
			if (!empty($party_type['shadow'])) {
				foreach($party_type['shadow'] as $position => $councillors) {
					foreach ($councillors as $councillor) {
						if ($councillor->id != "" && $councillor->id != "-1") {
							print '<li>';
							if (!empty($councillor->imageURL)) {							
								print '<a href="'. getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id) .'"><img src="'.getSiteRootURL().'/images/'.$councillor->imageURL.'" alt="'.encodeHtml(getImageProperty($councillor->imageURL, 'altText')).' " /></a>';
							}								
							print '<h3><a href="' . getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id) .'">'.encodeHtml($councillor->forename).' '.encodeHtml($councillor->surname).'</a> - '.$position.'</h3>';
							$ward = getWard($councillor->wardID);
							print '<p>Ward: '.encodeHtml($ward->name).'</p>';
							$party = getParty($councillor->partyID);
							print '<p>Party: ' .encodeHtml($party->name).'</p>';
							print '<p><a href="' . getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id) .'">Full details for  '.encodeHtml($councillor->forename).' '.encodeHtml($councillor->surname).'</a></p>';
							print '<div class="clear"></div></li>';
						}
					}
				}
			}
		}
?></ul>
	<ul class="list icons councillors">
		<li><a href="<?php print getSiteRootURL() . buildCouncillorsGroupURL('name'); ?>">View Councillors by Name</a></li>
		<li><a href="<?php print getSiteRootURL() . buildCouncillorsGroupURL('ward'); ?>">View Councillors by Ward</a></li>
		<li><a href="<?php print getSiteRootURL() . buildCouncillorsGroupURL('party'); ?>">View Councillors by Party</a></li>		
		<li><a href="<?php print getSiteRootURL() . buildCouncillorLookupURL(); ?>">Find Councillors by Postcode</a></li>
	</ul>	
<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>
