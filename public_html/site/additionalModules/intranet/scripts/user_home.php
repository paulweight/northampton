<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	
	include_once("egov/JaduXFormsForm.php");
	include_once("egov/JaduXFormsUserForms.php");
	include_once("egov/JaduXFormsUserQuestionAnswers.php");
	include_once("intranet/JaduForSaleBoardItems.php");
	include_once("intranet/JaduIntranetRoomBooking.php");
	include_once("intranet/JaduIntranetRoomBookingRooms.php");

	$confirmRemove = false;

	if (isset($_SESSION['userID'])) {
			
		if (isset($_GET['userFormID'])) {
			$userForm = getXFormsUserForm($_GET['userFormID']);
			
			if ($userForm->userID != $_SESSION['userID']) {
				header ("Location: $ERROR_REDIRECT_PAGE");
				exit();
			}
			
			$allAnswers = getAllXFormsQuestionAnswersForForm ($userForm->id);
			$form = getXFormsForm($userForm->formID, true);
			
			if (isset($_GET['remove']) && $_GET['remove'] == "true") {
				deleteXFormsUserForm($userForm->id);
			}
		}
		elseif (isset($_GET['userAppID']) || isset($_POST['userAppID'])) {
		    if (isset($_GET['remove']) && $_GET['remove'] == "true" && !isset($_POST['confirmRemove'])) {
		    	$app = getApplication($_GET['userAppID']);
		    	
		    	if ($app != null) {
		    		$confirmRemove = true;
		    	}
			}
			elseif(isset($_POST['confirmRemove'])) {
				$app = getApplication($_POST['userAppID']);

				// check that the logged in user owns this application
				if ($app->userID == $_SESSION['userID']) {
					deleteApplication($_POST['userAppID']);
				}
				unset($app);
			}
		}

		$allSubmittedUserForms = getAllXFormsUserFormsForUser ($_SESSION['userID'], true);
		$allUnsubmittedUserForms = getAllXFormsUserFormsForUser ($_SESSION['userID'], false);
		$forSaleItems = getAllForSaleItems('-1', 'viewCount', 'false', $_SESSION['userID']);
		$bookings = getUserBookings($_SESSION['userID']);
	}
	else {
		header ("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}

	$loginString = getLastLoginAsString($_SESSION['userID']);
	
	$breadcrumb = 'userHome';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Personal details</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="account, regstration, user, profile, personal, details, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> User personal details" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Personal details" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> User personal details" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
						
<?php
	if ($confirmRemove) {
		$app = getApplication($_GET['userAppID']);
		$job = getRecruitmentJob($app->jobID);
?>

		<p class="first">Are you sure you want to <span class="warning">delete</span> your application for <strong><?php print $job->title; ?></strong></p>
		<p>
			<form action="http://<?php print $DOMAIN; ?>/site/scripts/user_home.php" method="post">
				<input type="hidden" name="userAppID" value="<?php print $_GET['userAppID']; ?>" />
				<input type="submit" name="confirmRemove" class="button" value="Yes" />
				<input type="submit" name="declineRemove" class="button" value="No" />
			</form>
		<p>

<?php
		unset($app);
		unset($job);
	}
	else {
?>
		
		<h2>Hello, <em>
<?php 
	
		if ($user->salutation != "" && $user->surname != "") {
			print $user->salutation .  " ";
		}
	
		if ($user->forename != "") {
			print $user->forename . " ";
		}
		
		if  ($user->surname != "") {
			print $user->surname; 
		}
	
		if ($user->forename == "" && $user->surname == "") {
			print $user->email;
		}
	
?>
		</em></h2>
					
		<p class="first">Keep track of your activities and details right here.</p>
					
		<!-- Account options -->
		<div class="content_box">
			<h2>Your personal details <?php if (isset($detailsChanged)) { ?><em>have been updated.</em><? } ?></h2>
			<ul class="list">
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/change_details.php">Change your details</a></li>
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/change_password.php">Change your password</a></li>
				<li><a href="http://<?php print $DOMAIN;?>/site/index.php?logout=true">Sign out</a></li>
			</ul>
		</div>
			
		<!-- Online Forms -->
		<div class="content_box">
			<h2>Your online forms</h2>
			
<?php 
		if (sizeof($allSubmittedUserForms) > 0) {
?>
			<ul class="list">
				<li><a href="http://<?php print $DOMAIN ?>/site/scripts/user_form_archive.php"><?php print sizeof($allSubmittedUserForms) . ' forms submitted online';?></a></li>
			</ul>
<?php		
		}

		if (sizeof($allUnsubmittedUserForms) > 0) {
?>	
						
		<h3>Awaiting completion</h3>
		<p>You have <strong><?php print sizeof($allUnsubmittedUserForms);?> recent forms</strong> awaiting completion.</p>
<?php
			foreach ($allUnsubmittedUserForms as $userForm) {
				$actualForm = getXFormsForm($userForm->formID, false);
?>
			<p><a href="http://<?php print $DOMAIN;?>/site/scripts/xforms_form.php?formID=<?php print $actualForm->id;?>"><?php print $actualForm->title;?></a></p>
			<ul class="list">
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/xforms_form.php?formID=<?php print $actualForm->id;?>">Complete</a></li>
				<li><a href="http://<?php print $DOMAIN;?>/site/scripts/user_home.php?userFormID=<?php print $userForm->id;?>&amp;remove=true">Remove</a></li>
			</ul>
<?php
			}
?>
			
<?php
		}

		if (sizeof($allSubmittedUserForms) == 0 && sizeof($allUnsubmittedUserForms) == 0) {
?>
			<p>You have <strong>no online forms in progress</strong> or submitted.</p>
<?php
		}
?>
				</div>
				<!-- END Online Forms -->
<?php
	}
?>
			<!-- Sales Board items -->
			<div class="content_box">
				<h2>Your Current Sales Board Items</h2>
<?php
				if (sizeof($forSaleItems) > 0) {
				print '<ul class="list">';
					foreach ($forSaleItems as $item) {
?>
						<li><a href="http://<?php print $DOMAIN;?>/site/scripts/for_sale_item_details.php?itemID=<?php print $item->id; ?>"> <?php print $item->title; ?></a> - <a href="http://<?php print $DOMAIN;?>/site/scripts/for_sale_item_admin.php?itemID=<?php print $item->id; ?>"><strong>Edit</strong></a> - Viewed <strong><?php print $item->viewCount; ?></strong> times</li>
<?php
					}
				print '</ul>';
				}
				else {
?>
					<p>You don't currently have any items on the for sale board. </p>
<?php
				}
?>
				<p><a href="http://<?php print $DOMAIN;?>/site/scripts/for_sale_item_admin.php">Sell something now</a>.</p>				
			</div>
			
			<div class="content_box">
				<h2>Your room bookings</h2>
<?php
				if (sizeof($bookings) > 0) {
				print '<ul class="list">';
					foreach ($bookings as $item) {
						$roomName = getRoom($item->roomID);
?>
					<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/room_availability.php?date=<?php print $item->date ?>&roomIDs=<?php print $item->roomID; ?>"><?php print date("l jS F Y", $item->date) ?> in <?php print $roomName->title; ?></a></li>
<?php
					}
				print '</ul>';
				}
				else {
?>
					<p>You don't currently have any rooms booked.</p>
<?php
				}
?>
			</div>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>