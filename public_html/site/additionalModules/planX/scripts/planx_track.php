<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("planXLive/JaduPlanXApplications.php");
	include_once("planXLive/JaduPlanXTrackedApplications.php");

	// this well be set to true if an application is added to the 'tracked' list
	$added = false;

	if (isset($_GET['appID'])) {
	   $app = getPlanningApplication($_GET['appID']);

       if (isset($_SESSION['userID'])) {

           if (!isUserTrackingApplication($_SESSION['userID'], $app->id)) {
               $trackedApp = new TrackedApplication();
               $trackedApp->userID = $_SESSION['userID'];
               $trackedApp->applicationID = $app->id;

               addTrackedApplication($trackedApp);

               $added = true;
    	   }
       }
	}
	elseif (isset($_POST['trackMultipleApplications'])) {
	   if (sizeof($_POST['appIDs']) > 0 && isset($_SESSION['userID'])) {

	       foreach ($_POST['appIDs'] as $id) {
	           if (!isUserTrackingApplication($_SESSION['userID'], $id)) {
                   $trackedApp = new TrackedApplication();
                   $trackedApp->userID = $_SESSION['userID'];
                   $trackedApp->applicationID = $id;

                   addTrackedApplication($trackedApp);

                   $added = true;
    	       }
	       }
	   }
	}
	
	if (isset($_POST['removeTrackedApplications'])) {
	    if (sizeof($_POST['trackedAppIDs'])) {
	       foreach ($_POST['trackedAppIDs'] as $id) {
	           deleteTrackedApplication($id);
	       }
	    }
	}

	$trackedApps = array();

	if (isset($_SESSION['userID'])) {
	   $trackedApps = getAllTrackedApplicationsForUser($_SESSION['userID']);
	}
	
	$breadcrumb = 'planxTrack';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Track Application</title>

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
	if (isset($_GET['appID']) && isset($_SESSION['userID'])) {
?>
		<h2>Application number: <?php print $app->getFormattedValueForField('applicationNumber'); ?> added.</h2>
<?php
	}
?>												
<?php
	if (!isset($_SESSION['userID'])) {
?>
	<p class="first">To track a planning application you must first sign in to your account.</p>
			
<?php
		if (isset($_GET['loginFailed'])) {
?>
	<h2 class="warning">You could not be logged in with the details provided.</h2>
<?php
		}
?>
	
	<p><a href="http://<?php print $DOMAIN; ?>/site/scripts/register.php">I do not have an account</a></p>
			
	<form method="post" action="http://<?php print $DOMAIN; ?>/site/scripts/planx_track.php?<?php print $_SERVER['QUERY_STRING']; ?>" class="basic_form">
		<p>
			<label for="username">Email Address:</label>
			<input id="username" type="text" name="email" class="field" />
		</p>
		<p>
			<label for="surname">Password: </label>
			<input id="surname" type="password" name="password" class="field" />
		</p>
		<p class="center">
			<input type="submit" value="Sign In" name="submit" id="planbutton" class="button" />
		</p>
	</form>			
<?php
	}
		
	if ($added) {
		print '<p class="first">This application has now been added to your account.</p>';
	}
			
	// if there are some planning applications then display them
	if (sizeof($trackedApps) > 0) {
?>
		<p>To remove applications tick the box next to the application and click the 'Remove' button at the bottom of the list.</p>
		<form action="http://<?php print $DOMAIN; ?>/site/scripts/planx_track.php" method="post" class="basic_form">
			<p>Your tracked applications:</p>
			<table>
				<tr>
					<th>Development Address</th>
					<th>Application Number</th>
					<th>Development Description</th>
					<th>Remove</th>
				</tr>
<?php
				foreach ($trackedApps as $trackedApp) {
					$app = getPlanningApplication($trackedApp->applicationID);
?>
				<tr>
					<td><a href="http://<?php print $DOMAIN; ?>/site/scripts/planx_details.php?appID=<?php print $app->id; ?>"><?php print $app->getFormattedValueForField('applicationNumber'); ?></a></td>
					<td><?php print $app->getFormattedValueForField('developmentDescription'); ?></td>
					<td><?php print $app->getFormattedValueForField('developmentAddress'); ?></td>
					<td><input type="checkbox" name="trackedAppIDs[]" value="<?php print $trackedApp->id; ?>" /></td>
				</tr>
<?php
				}
?>
			</table>
			<p class="center">
				<input type="submit" class="button" name="removeTrackedApplications" value="Remove Selected" />
			</p>
		</form>		
<?php
		}
		// if there are no applications then display a message
		elseif(isset($_SESSION['userID'])) {
?>
		<p class="first">You have not chosen to track any applications.</p>
<?php
		}
?>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/closing.php"); ?>