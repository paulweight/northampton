<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	include_once("planXLive/JaduPlanXDevelopmentPlans.php");
	include_once("planXLive/JaduPlanXProposalsMaps.php");
	include_once("planXLive/JaduPlanXDevelopmentPlanChapters.php");
	include_once("planXLive/JaduPlanXDevelopmentPlanPolicies.php");

	if (isset($_GET['planID'])) {
        $plan = getDevelopmentPlan($_GET['planID']);
        $chapters = getAllChaptersForDevelopmentPlan($_GET['planID']);
	}
	else {
        // get all live development plans
        $developmentPlans = getAllDevelopmentPlans(1);
    }
    
    $maps = getAllProposalsMaps();
    
    $breadcrumb = 'planxLpIndex';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Planning Policy</title>

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

	<p class="first">Having trouble finding what you need? Why not try our <a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_lpsearch.php">policy search</a>.</p>
<?php
	if (isset($_GET['planID'])) {
?>
	<ul class="list">
<?php
		foreach ($chapters as $chapter) {
			$policies = getAllPoliciesForChapter($chapter->id);
?>
		<li><?php print $chapter->number . ". " . $chapter->title; ?></li>
		<ul>
<?php
			$levelCounter = array();
			$previousPolicy = new DevelopmentPlanPolicy();
			foreach ($policies as $policy) {
				if ($policy->level < $previousPage->level) {
					$levelCounter[$previousPolicy->level] = 0;
				}
				$levelCounter[$policy->level] = $levelCounter[$policy->level]+1;
				$num_label = $chapter->number;
				for ($i = 1; $i <= $policy->level; $i++) {
					$num_label .= '.' . $levelCounter[$i];
				}
				$previousPolicy = $policy;
?>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_lppolicy.php?policyID=<?php print $policy->id; ?>"><?php print $num_label . " " . $policy->title; ?></a></li>
<?php
			}
?>
		</ul>
<?php
		}
?>
	</ul>
<?php
	} 
	else {
?>
	<ul class="list">
<?php
		foreach ($developmentPlans as $plan) {
?>
		<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_lpindex.php?planID=<?php print $plan->id; ?>"><?php print $plan->title; ?></a></li>
<?php
		}
?>
	</ul>
<?php
	}
	if(count($maps) > 0) {
?>
	<h2>Maps</h2>
	<ul class="list">
<?php
	foreach ($maps as $map) {
?>
		<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_lpmaps.php?mapID=<?php print $map->id; ?>"><?php print $map->title; ?></a></li>
<?php
		}
	}
?>
	</ul>
						
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/closing.php"); ?>