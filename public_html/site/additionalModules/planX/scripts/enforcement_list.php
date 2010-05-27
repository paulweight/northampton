<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("planXLive/JaduPlanXEnforcementNotices.php");
	include_once("planXLive/JaduPlanXConfiguration.php");

	$constants = $constants = getAllPlanXConfigurationValues();

	// set default search parameters (if these are used all application would be returned)
	$params = array();
	$dateFrom = "";
	$dateTo = "";
	$dateField = "dateIssued";
	$andOr = "or";
	$appsPerPage = 10;
	$lowerLimit = 0;
	$showNextLink = false;
	$showPreviousLink = false;

	if (isset($_GET['submitAppealDate'])) {
		$dateFrom = date("Ymd", mktime(0, 0, 0, $_GET['fromMonth'], $_GET['fromDay'], $_GET['fromYear']));
		$dateTo = date("Ymd", mktime(0, 0, 0, $_GET['toMonth'], $_GET['toDay'], $_GET['toYear']));
		$dateField = "appealStartDate";
	}

	if (isset($_GET['submitLocation'])) {
		$params = array("town" => $_GET['location'], 
						"address" => $_GET['location']);
	}

	if (isset($_GET['submitKeyword'])) {
		$params = array("applicant" => $_GET['keywords'], 
						"proposal" => $_GET['keywords']);
	}

	if (isset($_GET['noticeRef'])) {
		$params = array("noticeRef" => $_GET['noticeRef']);
	}

	if (isset($_GET['submitWard'])) {
		$params = array("ward" => $_GET['ward']);
	}

	if (isset($_GET['submitParish'])) {
		$params = array("parish" => $_GET['parish']);
	}

	if (isset($_GET['submitNoticeType'])) {
		$params = array("noticeType" => $_GET['noticeType']);
	}

	if (isset($_GET['lowerLimit'])) {
	   $lowerLimit = $_GET['lowerLimit'];
	}

	$notices = searchEnforcementNotices($params, $dateField, $dateFrom, $dateTo, $andOr, $lowerLimit, $appsPerPage + 1);

	$numResults = searchEnforcementNotices($params, $dateField, $dateFrom, $dateTo, $andOr, $lowerLimit, $appsPerPage + 1, true);

	if ($numResults < 1) {
		header("Location: ./enforcement.php?noResults=true");
		exit();
	}

	if ($lowerLimit > 0) {
	   $showPreviousLink = true;
	}

	if (sizeof($notices) > $appsPerPage) {
	   $showNextLink = true;
	}

	$upperAppsNumber = $lowerLimit + $appsPerPage;
    if ($upperAppsNumber > $numResults) {
        $upperAppsNumber = $numResults;
    }
    
    $breadcrumb = 'enforcementList';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?>, planning application search</title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="planning, plans, applications, planning, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Planning Application Search" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Planning Application Search" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Planning Application Search" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<p class="first">Search for a notice by street, town or village</p>
	<form action="http://<?php print $DOMAIN; ?>/site/scripts/enforcement_list.php" method="get" class="basic">
		<p>
			<label for="location">Location: </label>
			<input type="text" name="location" class="field" id="location" />
		</p>
		<p class="center">
			<input class="button" value="Go" type="submit" name="submitLocation" />
		</p>
	</form>
	<p><a href="http://<?php print $DOMAIN; ?>/site/scripts/enforcement.php">Advanced Search</a></p>

	<h2>Enforcement Notice Search Results</h2>
	<p>
<?php
		if ($showPreviousLink) {
			$newLowerLimit = $lowerLimit - $appsPerPage;
			$query = ereg_replace("&amp;lowerLimit=[0-9]*", "", htmlentities($_SERVER['QUERY_STRING']));
			$query .= "&amp;lowerLimit=$newLowerLimit";
?>
			<a href="http://<?php print $DOMAIN; ?>/site/scripts/enforcement_list.php?<?php print $query; ?>">Previous</a> | 
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
			| <a href="http://<?php print $DOMAIN; ?>/site/scripts/enforcement_list.php?<?php print $query; ?>">Next</a>
<?php
		}
?>
	</p>
            <table summary="Enforcement Notices">
				<thead>
					<tr>
						<th>Notice N&ordm;</th>
						<th>Address</th>
						<th>Type</th>
					</tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="2">Last updated: <?php print date("d M Y", $constants['lastEnforcementImportTimestamp']->value); ?></td>
                    </tr>
                </tfoot>
                <tbody>
<?php
				foreach ($notices as $notice) {
?>
                    <tr>
                        <td><a href="http://<?php print $DOMAIN; ?>/site/scripts/enforcement_details.php?id=<?php print $notice->id; ?>"><?php print $notice->noticeRef; ?></a></td>
                        <td>
<?php 
							print $notice->address . " " .
								  $notice->getFormattedValueForField('town') . ", " .
								  $notice->getFormattedValueForField('postcode');
?>
						</td>
						<td><?php print $notice->getFormattedValueForField('noticeType'); ?></td>
                    </tr>
<?php
				}
?>
                </tbody>
            </table>

	<p>
<?php
		if ($showPreviousLink) {
			$newLowerLimit = $lowerLimit - $appsPerPage;
			$query = ereg_replace("&amp;lowerLimit=[0-9]*", "", htmlentities($_SERVER['QUERY_STRING']));
			$query .= "&amp;lowerLimit=$newLowerLimit";
?>
			<a href="http://<?php print $DOMAIN; ?>/site/scripts/enforcement_list.php?<?php print $query; ?>">Previous</a> | 
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
			| <a href="http://<?php print $DOMAIN; ?>/site/scripts/enforcement_list.php?<?php print $query; ?>">Next</a>
<?php
		}
?>
	</p>
	
    <!--  contact box  -->
    <?php include("../includes/contactbox.php"); ?>
    <!--  END contact box  -->
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>