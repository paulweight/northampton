<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Accessibility, dda, disability discrimination act, disabled access, access keys, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Accessibility features" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
		
<?php
	if (isset($_GET['userID']) && is_numeric($_GET['userID']) && isset($_GET['email']) ) {
		$user = Jadu_Service_User::getInstance()->getUser($_GET['userID']);
		if ($user->email == $_GET['email']) {
			if ($user->dataProtection == "1") {
				unsubscribeUserFromBeingContacted($_GET['userID']);
				$message = "<h2>Thank you</h2>" .
					"<p class=\"first\">Your registration details have now been altered so that you will no longer receive emails from us.<p>".
					"<p>You can opt back into receiving such information by logging into the web-site and using the 'Change details' page.</p>".
					"Your login details have been in no way affected.";
			}
			else {
				$message = "<h2 class=\"warning\">It would appear that you have already opted out of receiving email announcements from us</h2>";
			}
		}
		else {
			$message = "<h2 class=\"warning\">Sorry, you have not been unsubscribed. The given details do not appear to be correct</h2>";
			$showForm = true;
		}
		
		print $message;
	}
	else if (isset($_POST['unsubscribeEmail']) && isset($_POST['unsubscribePassword'])) {
		
		$user = Jadu_Service_User::getInstance()->getUserByIdentity($_POST['unsubscribeEmail']);
		
		if ($user->password == getPasswordHash($_POST['unsubscribePassword']) && mb_strlen($user->email) > 3) {
			if ($user->dataProtection == "1") {
				unsubscribeUserFromBeingContacted($user->id);
				$message = "<h2>Thank you</h2>" .
					"<p class=\"first\">Your registration details have now been altered so that you will no longer receive emails from us.<p>".
					"<p>You can opt back into receiving such information by logging into the web-site and using the 'Change details' page.</p>".
					"Your login details have been in no way affected.";
			}
			else {
				$message = "<h2 class=\"warning\">It would appear that you have already opted out of receiving email announcements from us</h2>";
			}
		}
		else {
			$message = "<h2 class=\"warning\">Sorry, you have not been unsubscribed. The given details do not appear to be correct</h2>";
			$showForm = true;
		}
		
		print $message;
	}
	else {
		$showForm = true;
	}

	if ($showForm) {
?>
	<p>The form below allows registered users to opt-out of receiving general email announcements from <?php print DOMAIN; ?>.</p>
	<form class="basic_form xform" name="main" action="<?php print getSiteRootURL() . buildNonReadableUnsubscribeURL(); ?>" method="post" enctype="multipart/form-data">
		<ol>
			<li>
				<label for="unsubscribeEmail">Email address <em>(required)</em></label>
				<input id="unsubscribeEmail" type="text" name="unsubscribeEmail" value="<?php print encodeHtml($user->email); ?>" />
			</li>
			<li>
				<label for="password">Password <em>(required)</em></label>
				<input id="password" type="password" name="unsubscribePassword" value="" />
			</li>
			<li class="center">
				<input type="submit" name="unsubscribe" value="Unsubscribe me now" class="genericButton grey" />
			</li>
		</ol>
	</form>
	
<?php
	}
?>
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>