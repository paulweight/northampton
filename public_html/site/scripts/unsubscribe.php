<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	
	include_once("marketing/JaduUsers.php");
	
	$breadcrumb = 'unsubscribe';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Unsubscribe | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Accessibility, dda, disability discrimination act, disabled access, access keys, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Accessibility features" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
		
<?php
	if (isset($_GET['userID']) && isset($_GET['email']) ) {
		$user = getUser($_GET['userID']);
		if ($user->email == $email) {
			if ($user->dataProtection == "1") {
				unsubscribeUserFromBeingContacted($_GET['userID']);
				$message = "<h2>Thank you</h2>" .
					"<p class=\"first\">Your registration details have now been altered so that you will no longer receive emails from us.<p>".
					"<p>You can opt back into receiving such information by logging into the web-site and using the 'Change details' page.</p>".
					"Your login details have been in no way affected.";
			}
			else {
				$message = "<h2 class=\"warning\">It would appear that you have already opted out of receiving email announcements from us.</h2>";
			}
		}
		else {
			$message = "<h2 class=\"warning\">Sorry, you have not been unsubscribed. The given details do not appear to be correct.</h2>";
			$showForm = true;
		}
		
		print $message;
	}
	else if (isset($_POST['unsubscribeEmail']) && isset($_POST['unsubscribePassword'])) {
		
		$user = isUserRegistered($_POST['unsubscribeEmail']);
		
		if ($user->password == $_POST['unsubscribePassword'] && strlen($user->email) > 3 ) {
			if ($user->dataProtection == "1") {
				unsubscribeUserFromBeingContacted($user->id);
				$message = "<h2>Thank you</h2>" .
					"<p class=\"first\">Your registration details have now been altered so that you will no longer receive emails from us.<p>".
					"<p>You can opt back into receiving such information by logging into the web-site and using the 'Change details' page.</p>".
					"Your login details have been in no way affected.";
			}
			else {
				$message = "<h2 class=\"warning\">It would appear that you have already opted out of receiving email announcements from us.</h2>";
			}
		}
		else {
			$message = "<h2 class=\"warning\">Sorry, you have not been unsubscribed. The given details do not appear to be correct.</h2>";
			$showForm = true;
		}
		
		print $message;
	}
	else {
		$showForm = true;
	}

	if ($showForm) {
?>
		<p class="first">The form below allows registered users to opt-out of receiving general email announcements from <?php print $DOMAIN;?>.</p>
		<form name="main" class="basic_form" action="http://<?php print $DOMAIN;?>/site/scripts/unsubscribe.php" method="post">
			<fieldset>
				<legend>Your details</legend>
				<p>
					<label for="unsubscribeEmail">email address (required)</label>
					<input id="unsubscribeEmail" type="text" name="unsubscribeEmail" value="<?php print $user->email;?>" class="field" />
				</p>
				<p>
					<label for="password">password (required)</label>
					<input id="password" type="password" name="unsubscribePassword" value="" class="field" />
				</p>
				<p class="centre">
					<input type="submit" class="button" name="unsubscribe" value="Unsubscribe me now" />
				</p>
			</fieldset>
		</form>
<?php
	}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>