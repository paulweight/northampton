<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("egov/JaduCL.php");
	include_once("eConsultation/JaduConsultations.php");
	include_once("eConsultation/JaduConsultationNotificationRequestors.php");
	
	$instructions = 0;
	
	if (isset($_GET['consultationID']) && && is_numeric($_GET['consultationID']) && $_GET['consultationID'] > 0) {
		$consultation = getConsultation($_GET['consultationID'], true);	
		
		if ($consultation != -1 && $consultation->allowNotificationSignups) {
				
			//	If they have used a form to do something
			if (isset($_POST['notificationSignUp']) && isset($_POST['notificationSignUpEmail'])) {
				$notification = getConsultationNotificationRequestForEmailAndConsultation ($_POST['notificationSignUpEmail'], $_GET['consultationID']);
				if ($notification == -1) {
				
					if (($_POST['notificationSignUpEmail'] == '') || (!ereg("^[^@ ]+@[^@ ]+\.[^@ \.]+$", $_POST['notificationSignUpEmail'], $trash))) {
						$instructions = 9;
					}
					else {
						$result = newConsultationNotificationRequest ($_GET['consultationID'], $consultation->title, $_POST['notificationSignUpEmail']);
						$instructions = 1;
					}
				}
				else if ($notification->authentication == NOTIFICATION_UNAUTHENTICATED) {
					if (isset($_POST['reminderSent'])) {
						$instructions = 4;
						sendAuthenticationEmail($_GET['consultationID'], $consultation->title, $notification->email, $notification->id);
					} else {
						$instructions = 2;
					}
				}
				else if ($notification->authentication == NOTIFICATION_AUTHENTICATED) {
					$instructions = 3;
				}
			}
			
			//	If they are coming from an email to authenticate etc.
			else if (isset($_GET['notificationID']) && is_numeric($_GET['notificationID']) && isset($_GET['action']) && isset($_GET['email'])) {
			
				$notification = getConsultationNotificationRequest($_GET['notificationID']);
				
				if ($notification == -1) {
					$instructions = 8;
				}
	
				//	Check that someone isnt playing with the id's etc passed int the script.
				else if ($notification->email == $_GET['email']) {
					if ($_GET['action'] == NOTIFICATION_REMOVAL) {
						deleteConsultationNotificationRequest ($notification->id);
						$instructions = 5;
					}
					else if ($_GET['action'] == NOTIFICATION_AUTHENTICATED) {
						updateNotificationRequestAuthentication ($notification->id, NOTIFICATION_AUTHENTICATED);
						$instructions = 6;
					}
					else {
						$instructions = 7;
					}
				}
				else {
					$instructions = 7;
				}
			}
		} 
		
		//	If someone is playing with the consultationID, the thrown them back to open consultations
		else {
			header("Location: consultation_open.php");
			exit;
		}
	}
	else {
		header("Location: consultation_open.php");
		exit;
	}	
	
	$breadcrumb = 'consultationNotifications';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> Consulations - Keep up to date with <?php print $consultation->title; ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Consultation, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Consulations - Keep up to date with <?php print $consultation->title; ?>" />

	<?php printMetadata(CONSULTATIONS_METADATA_TABLE, CONSULTATIONS_CATEGORIES_TABLE, $consultation->id, $consultation->title, CONSULTATIONS_PUBLIC_FOLDER.$consultation->folderName."/index.php"); ?>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ####################################### -->	
	
		<h2>Email alert</h2>

<?php
		if ($instructions == 0) {
?>
			<p class="first">You can sign up to receive notification on any public consultation on this website.</p>
			<p>When signing up, you should firstly provide your email address. After doing this, we will send you an email (to the given address) from which you should confirm yourself as the account holder by following the provided link, which will then activate your notifications.</p>

			<form name="consultationNotification" action="http://<?php print $DOMAIN;?>/site/scripts/consultation_notification.php?consultationID=<?php print $consultation->id;?>" method="post" class="basic_form">
				<fieldset>
					<legend>Sign-up now</legend>
					<p>
						<label for="notificationSignUpEmail">Enter your email: </label>
						<input type="text" size="13" maxlength="30" id="notificationSignUpEmail" name="notificationSignUpEmail" class="field" value="<?php if (isset($_SESSION['userID'])) print $user->email;?>" />
					</p>
					<p class="center">
						<input type="submit" class="button" name="notificationSignUp" value="Sign-up" />
					</p>
				</fieldset>
			</form>
<?php
		}
		else if ($instructions == 1) {
?>
			<p class="first">Thank you for completing the first stage of the signup process.</p>
			<p class="first">You shall shortly receive an email to the given email address.</p>
			<p>From this email you need to authenticate its use by following the supplied link. After doing this, you will be notified of changes to <?php print $consultation->title;?> as and when they occurr.</p>
<?php
		}
		else if ($instructions == 2) {
?>
			<p class="first">This email address has already been submitted for this particular consultation, and is currently awaiting authentication.</p>
			<p>If you have lost the email regarding authentication, then you can resend this email from here ...</p>
			<form name="consultationNotification" action="http://<?php print $DOMAIN;?>/site/scripts/consultation_notification.php?consultationID=<?php print $consultation->id;?>" method="post">
				<input type="hidden" name="reminderSent" value="true" />
				<input type="hidden" name="notificationSignUpEmail" value="<?php print $_POST['notificationSignUpEmail']; ?>" />
				<input type="submit" class="bigbutton" name="notificationSignUp" value="Email me again" />
			</form>
<?php
		}
		else if ($instructions == 3) {
?>
			<p class="first">This email address is already activated for notifications on this consultation.</p>
<?php
		}
		else if ($instructions == 4) {
?>
			<p class="first">Another authentication email has been sent to <?php print $notification->email;?>.</p>
<?php
		}
		else if ($instructions == 5) {
?>
			<p class="first">Your request for consultation updates on <?php print $consultation->title;?> has been deleted successfully.</p>
<?php
		}
		else if ($instructions == 6) {
?>
			<p class="first">You have successfully authenticated yourself. You will in future receive all updates concerning <?php print $consultation->title;?>.</p>
<?php
		}
		else if ($instructions == 7) {
?>
			<p class="first">Error: you have provided some incorrect information.</p>
<?php
		}
		else if ($instructions == 8) {
?>
			<p class="first">Error: you may have already removed yourself from receiving <?php print $consultation->title;?> updates. No action has been taken.</p>
<?php
		}
		else if ($instructions == 9) {
?>
			<p class="first">Error: the email you have provided appears to be invalid. Please check your email is correct below. No action has been taken.</p>

			<form name="consultationNotification" action="http://<?php print $DOMAIN;?>/site/scripts/consultation_notification.php?consultationID=<?php print $consultation->id;?>" method="post" class="basic_form">
				<fieldset>
					<legend>Sign-up now</legend>
					<p>
						<label for="notificationSignUpEmail">Enter your email:</label>
						<input type="text" size="13" maxlength="30" id="notificationSignUpEmail" name="notificationSignUpEmail" class="field" value="<?php if (isset($_SESSION['userID'])) print $user->email;?>" />
					</p>
					<p class="center">
						<input type="submit" class="button" name="notificationSignUp" value="Sign-up" />
					</p>
				</fieldset>
			</form>
<?php
		}
?>
			
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>