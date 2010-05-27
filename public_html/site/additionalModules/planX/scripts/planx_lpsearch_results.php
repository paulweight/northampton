<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("egov/JaduEGovLPI.php");
	include_once("planXLive/JaduPlanXPolicyLocations.php");
	include_once("planXLive/JaduPlanXProposalsMaps.php");
	include_once("planXLive/JaduPlanXProposalsMapsLocations.php");
	include_once("planXLive/JaduPlanXDevelopmentPlanPolicies.php");

	if (isset($_GET['submitPostcodeSearch']) && !empty($_GET['postcode'])) {
	   // get all lpi records that match the post code
	   $lpis = getAllLPIsForPostCode ($_GET['postcode']);

	   // get all map and policy location records for each lpi record
	   $policyLocations = array();
	   $mapLocations = array();

	   if (sizeof($lpis) > 0) {
           foreach ($lpis as $lpi) {
               $policyLocations = array_merge($policyLocations, getAllPolicyLocationsForLPI($lpi->uprn));
               $mapLocations = array_merge($mapLocations, getAllMapLocationsForLPI($lpi->uprn));
           }
	   }

	   // get all policies for the policy location records returned
	   $policies = array();
	   if (sizeof($policyLocations) > 0) {
	       foreach ($policyLocations as $pl) {
               $policies[] = getDevelopmentPlanPolicy($pl->policyID);
           }
	   }

	   // get all map for the map location records returned
	   $maps = array();
	   if (sizeof($mapLocations) > 0) {
	       foreach ($mapLocations as $ml) {
               $maps[] = getProposalsMap($ml->mapID);
           }
	   }
	}
	elseif (isset($_GET['submitKeywordSearch'])) {
        $policies = getAllPoliciesMatchingKeywords($_GET['keywords']);
        
        $maps = getAllProposalsMapsMatchingKeywords($_GET['keywords']);
	}
	$breadcrumb = 'planxLpSearchResults';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Policy Results</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of documents and pages organised within the following categories, Environment, Planning" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> online information | Environment | Planning" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of documents and pages organised within the following categories, Environment, Planning" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
   						
	<h2>Results in Policies</h2>

<?php
    if (sizeof($policies) > 0) {
		foreach ($policies as $policy) {
			$levelCounter = $levelCounter[$policy->level]+1;
			$num_label = $chapter->number;
			 for ($i = 1; $i <= $policy->level; $i++) {
				$num_label .= '.' . $levelCounter[$i];
			}
?>
		<div class="search_result">
			<h3><a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_lppolicy.php?policyID=<?php print $policy->id; ?>"><?php print $policy->title; ?></a></h3>
		</div>
<?php
		}
	} 
	else {
?>
		<p>No policies were found.</p>
<?php
	}
?>
		<h2>Results in Maps</h2>
<?php
	if (sizeof($maps) > 0) {
		foreach ($maps as $map) {
?>
		<div class="search_result">
			<h3><a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_lpmaps.php?mapID=<?php print $map->id; ?>"><?php print $map->title; ?></a></h3>
			<p><?php print $map->description; ?></p>
		</div>
<?php
		}
	} 
	else {
?>
		<p>No maps were found.</p>
<?php
	}
?>

<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/closing.php"); ?>