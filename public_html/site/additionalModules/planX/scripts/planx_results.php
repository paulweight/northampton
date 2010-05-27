<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("planXLive/JaduPlanXApplications.php");

	// set default search parameters (if these are used all application would be returned)
	$params = array();
	$dateFrom = "";
	$dateTo = "";
	$dateField = "UNIX_TIMESTAMP(decisionDate)";
	$andOr = "or";
	$appsPerPage = 10;
	$lowerLimit = 0;
	$showNextLink = false;
	$showPreviousLink = false;

    // check that if a search has been submitted that the parameters won't be empty
	if ((isset($_GET['locationSearch']) && empty($_GET['location'])) || 
	       (isset($_GET['applicantSearch']) && empty($_GET['applicant']))) {
        header("Location: ./planx_search.php?noResults=true");
        exit();
	}

	if (isset($_GET['advancedSearch'])) {

	    $found = false;

        foreach ($_GET as $name => $val) {
	        if (!empty($val) && $name != 'advancedSearch') {
	           $found = true;
	        }
	    }
	    
	    if (!$found) {
            header("Location: ./planx_advsearch.php?noResults=true");
            exit();
        }
	}

    // search for an applicant or location
	if (isset($_GET['applicantSearch'])) {
	    $params = array("applicantName" => $_GET['applicant']);
	                    //"applicantAddress" => $_GET['applicant']);
	}
	elseif (isset($_GET['locationSearch'])) {
	    $params = array("developmentAddress" => $_GET['location']);
	}

    // perform an advanced search
	elseif (isset($_GET['advancedSearch'])) {
	    $andOr = "and";

	    if (!empty($_GET['location'])) {
	       $params['developmentAddress'] = $_GET['location'];
	    }
	    if (!empty($_GET['applicant'])) {
    	    $params['applicantName'] = $_GET['applicant'];
        }
    	if (!empty($_GET['developmentDescription'])) {
    	    $params['developmentDescription'] = $_GET['developmentDescription'];
    	}
    	if (!empty($_GET['decisionType'])) {
    	    $params['decisionType'] = $_GET['decisionType'];
    	}
    	if (!empty($_GET['decisionDate'])) {
    	    $params['decisionDate'] = $_GET['decisionDate'];
    	}
    	if (!empty($_GET['fromDay']) && !empty($_GET['fromMonth']) && !empty($_GET['fromYear']) &&
    	    !empty($_GET['toDay']) && !empty($_GET['toMonth']) && !empty($_GET['toYear'])) {
    	    
    	   $dateFrom = mktime(0,0,0,$_GET['fromMonth'],$_GET['fromDay'],$_GET['fromYear']);
    	   $dateTo = mktime(0,0,0,$_GET['toMonth'],$_GET['toDay'],$_GET['toYear']);
    	}
	}

	if (isset($_GET['lowerLimit'])) {
	   $lowerLimit = $_GET['lowerLimit'];
	}

	$apps = searchPlanningApplications($params, $dateField, $dateFrom, $dateTo, $andOr, $lowerLimit, $appsPerPage + 1);

	$numResults = searchPlanningApplications($params, $dateField, $dateFrom, $dateTo, $andOr, $lowerLimit, $appsPerPage + 1, true);

	if ($numResults < 1) {
	   header("Location: ./planx_search.php?noResults=true");
	   exit;
	}

	if ($lowerLimit > 0) {
	   $showPreviousLink = true;
	}

	if (sizeof($apps) > $appsPerPage) {
	   $showNextLink = true;
	}
	
	$breadcrumb = 'planxResults';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Planning Results</title>

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

		<p class="first"><?php print $numResults; ?> results found</p>

		<form action="http://<?php print $DOMAIN; ?>/site/scripts/planx_track.php" method="post">
<?php
		for ($i = 0; $i < sizeof($apps); $i++) {
			$app = $apps[$i];
?>
		<div class="search_result">
			<h2><a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_details.php?appID=<?php print $app->getFormattedValueForField('id'); ?>"><?php print $app->getFormattedValueForField('applicationNumber'); ?></a></h2>
			<p><strong><?php print htmlentities($app->getFormattedValueForField('applicantName')); ?></strong> - <?php print htmlentities($app->getFormattedValueForField('developmentAddress')); ?></p>
			<p><input type="checkbox" name="appIDs[]" value="<?php print $app->getFormattedValueForField('id'); ?>" /></p>
		</tr>
<?php
		}
?>
		<p>
<?php
		if ($showPreviousLink) {
			$newLowerLimit = $lowerLimit - $appsPerPage;
			$query = ereg_replace("&amp;lowerLimit=[0-9]*", "", htmlentities($_SERVER['QUERY_STRING']));
			$query .= "&amp;lowerLimit=$newLowerLimit";
?>
		<a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_results.php?<?php print $query; ?>">Previous</a> | 
<?php
		}
		
		$upperAppsNumber = $lowerLimit + $appsPerPage;
		if ($upperAppsNumber > $numResults) {
			$upperAppsNumber = $numResults;
		}
		
		printf("%s to %s of %s results", $lowerLimit + 1, $upperAppsNumber, $numResults);

		if ($showNextLink) {
			$newLowerLimit = $lowerLimit + $appsPerPage;
			$query = ereg_replace("&amp;lowerLimit=[0-9]*", "", htmlentities($_SERVER['QUERY_STRING']));
			$query .= "&amp;lowerLimit=$newLowerLimit";
?>
		 | <a title="next" href="http://<?php print $DOMAIN; ?>/site/scripts/planx_results.php?<?php print $query; ?>">Next</a>
<?php
		}
?>
		</p>
		<p><input type="submit" value="Track These Applications" name="trackMultipleApplications" class="button" /></p>
	</form>

	<h2>Tracking Applications</h2>
	<p>If you are logged in to <?php print METADATA_GENERIC_COUNCIL_NAME;?> then this facility will allow you to watch for updates to any application you choose to track.  This information is shown in your account page.</p>
	<p>Simply click on the check boxes of the applications you want to track and then click the button at the bottom of the search results.</p>
	<br />
	<form method="post" action="<?php print $_SERVER['HTTP_REFERER']; ?>">
		<input type="submit" value="New Search" name="submit" class="button" />
	</form>

<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/closing.php"); ?>