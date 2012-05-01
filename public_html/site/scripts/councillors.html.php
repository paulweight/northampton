<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="councillor, search, find, finder, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>s directory of all local Councillors" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Find Councillors" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>s directory of all local Councillors" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	$splitArray = splitArray($array);
	if (sizeof($array) > 0) {			
		switch (mb_strtolower($_GET['viewBy'])) {
			case "name" :
			case "specificward" :
			case "specificparty" :
			if(sizeof($array) > 0) {
				print '<ul class="archive">';
				foreach ($array as $councillor) {
					$ward = getWard($councillor->wardID);
					$party = getParty($councillor->partyID);
?>
	<li>
		
<?php
					if ($councillor->imageURL != "") { 
?>
		<p><a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id); ?>">
				<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($councillor->imageURL); ?>" alt="<?php print encodeHtml($councillor->forename . ' ' . $councillor->surname); ?>" />
			</a>
		</p>
<?php 
					} 
?><h3><a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id); ?>"><?php print encodeHtml($councillor->forename . ' ' . $councillor->surname); ?></a></h3>
		<p>Ward: <?php if ($ward->id != -1) print encodeHtml($ward->name); else print "Unknown";?></p>
		<p>Party: <?php if ($party->id != -1) print encodeHtml($party->name); else print "Unknown";?></p>
		<p>Full details on <a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id); ?>"><?php print encodeHtml($councillor->forename . ' ' . $councillor->surname); ?></a></p>
		<div class="clear"></div>
	</li>
<?php
				}
				print '</ul>';
			}	?>
			<ul class="list icons councillors">
				<li><a href="<?php print getSiteRootURL() . buildCouncillorsGroupURL('name'); ?>">View Councillors by Name</a></li>
				<li><a href="<?php print getSiteRootURL() . buildCouncillorsGroupURL('ward'); ?>">View Councillors by Ward</a></li>
				<li><a href="<?php print getSiteRootURL() . buildCouncillorsGroupURL('party'); ?>">View Councillors by Party</a></li>		
				<li><a href="<?php print getSiteRootURL() . buildCouncillorLookupURL(); ?>">Find Councillors by Postcode</a></li>
			</ul>
<?php			break;
					   
			case "ward" :   
				print "<ul class=\"list icons councillors\">";
				foreach ($array as $ward) {
?>
		<li><a href="<?php print getSiteRootURL() . buildCouncillorsGroupURL('specificWard', $ward->id); ?>"><?php print encodeHtml($ward->name); ?></a></li>
<?php
				}
				print "</ul>";
			break;
						
			case "party" :
				print "<ul class=\"list icons councillors\">";
				foreach ($array as $party) {
?>
							<li> <a href="<?php print getSiteRootURL() . buildCouncillorsGroupURL('specificParty', $party->id); ?>"><?php print encodeHtml($party->name); ?></a></li>
<?php
				}
				print "</ul>";
			break;
		}
	}
    else {
?>
            <p>Sorry. There were no matches found.</p>
<?php
	}
?>
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>