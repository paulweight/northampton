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
				print '<ul>';
				foreach ($array as $councillor) {
					$ward = getWard($councillor->wardID);
					$party = getParty($councillor->partyID);
?>
	<li>
		<h3><a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id); ?>"><?php print encodeHtml($councillor->forename . ' ' . $councillor->surname); ?></a></h3>
<?php
					if ($councillor->imageURL != "") { 
?>
		<p><a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id); ?>">
				<img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($councillor->imageURL); ?>" alt="<?php print encodeHtml($councillor->forename . ' ' . $councillor->surname); ?>" />
			</a>
		</p>
<?php 
					} 
?>
		<p>Ward: <?php if ($ward->id != -1) print encodeHtml($ward->name); else print "Unknown";?></p>
		<p>Party: <?php if ($party->id != -1) print encodeHtml($party->name); else print "Unknown";?></p>
		<p>Full details on <a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($councillor->id); ?>"><?php print encodeHtml($councillor->forename . ' ' . $councillor->surname); ?></a></p>
	</li>
<?php
				}
				print '</ul>';
			}	
			break;
					   
			case "ward" :   
				print "<ul class=\"list\">";
				foreach ($array as $ward) {
?>
		<li><a href="<?php print getSiteRootURL() . buildCouncillorsGroupURL('specificWard', $ward->id); ?>"><?php print encodeHtml($ward->name); ?></a></li>
<?php
				}
				print "</ul>";
			break;
						
			case "party" :
				print "<ul class=\"list\">";
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