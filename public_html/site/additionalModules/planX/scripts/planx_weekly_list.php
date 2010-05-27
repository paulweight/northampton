<?php
    include_once("planXLive/JaduPlanXApplications.php");

    $numWeeklyListApps = sizeof(getPlanningApplicationWeeklyList());

    $lowerLimit = 0;
    $offset = 10;
    
    if (isset($_GET['lowerLimit']) && isset($_GET['offset'])) {
        $lowerLimit = $_GET['lowerLimit'];
        $offset = $_GET['offset'];
    }

	$apps = getPlanningApplicationWeeklyList($lowerLimit, $offset);

	$currentPage = 'http://' . $DOMAIN . $_SERVER['PHP_SELF'];
	
	$breadcrumb = 'planxWeekly';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Planning weekly list</title>

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
 <?php
     if (sizeof($apps) > 0) {
?>
 <table summary="Planning Applications">
        <thead>
            <tr>
                <th class="firstcol">App. N&ordm;</th>
                <th>Applicant &amp; Location</th>
            </tr>
        </thead>
        <tbody>

<?php
        foreach ($apps as $app) {
?>
            <tr>
                    <td><a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_details.php?appID=<?php print $app->id; ?>"><?php print $app->getFormattedValueForField('applicationNumber'); ?></a></td>
                    <td><span class="b">
                            <?php print htmlEntities($app->getFormattedValueForField('applicantName')); ?>
                        </span> <?php print $app->getFormattedValueForField('developmentAddress'); ?></td>
            </tr>
<?php
        }
?>

    <?php
        if ($numWeeklyListApps > $lowerLimit + $offset || $lowerLimit > 0) {
            $tmpNumApps = $lowerLimit + $offset;
            if ($tmpNumApps > $numWeeklyListApps) {
                $tmpNumApps = $numWeeklyListApps;
            }
    ?>
            <tr>
                <td colspan="2">
            <?php
                if ($lowerLimit > 0) {
            ?>
                    <a href="<?php print $currentPage; ?>?lowerLimit=<?php print $lowerLimit-$offset; ?>&offset=<?php print $offset; ?>#weeklyList">Previous</a> | 
            <?php
                }

                printf("%s to %s of %s applications ", $lowerLimit + 1, $tmpNumApps, $numWeeklyListApps);

                if ($numWeeklyListApps > $lowerLimit + $offset) {
            ?>
                    | <a href="<?php print $currentPage; ?>?lowerLimit=<?php print $lowerLimit+$offset; ?>&amp;offset=<?php print $offset; ?>#weeklyList">Next</a>
            <?php
                }
            ?>
                
                </td>
            </tr>
    <?php
        }
    ?>
            <tr>
                <td colspan="2">Last updated: <?php print date("d F Y", getWeeklyListStartDate() + ((60 * 60) * 24) * 6); ?> (updated every Friday)</td>
            </tr>
       </tbody>
      
    </table>
<?php
    }
    else {
?>
        <p class="first">No applications have been published this week.</p>
<?php
    }
?>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/closing.php"); ?>