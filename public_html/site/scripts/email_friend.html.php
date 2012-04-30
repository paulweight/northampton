<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="email, friend, send, link, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="Email a page to a friend from <?php print encodeHtml(METADATA_GENERIC_NAME); ?>s website" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> email a friend" />
	<meta name="DC.description" lang="en" content="Email a page to a friend from <?php print encodeHtml(METADATA_GENERIC_NAME); ?>s website" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
		if (sizeof($error_array) > 0) {
?>
		<h2 class="warning">Please check details highlighted <strong>!</strong> are entered correctly</h2>
<?php 
			if ($error_array['message']) {
?>
		<h3 class="warning"><strong>Note: The message must not contain URLs.</strong></h3>
<?php
			}
		}
		elseif (sizeof($spam_error_array) > 0) {
?>
		<h2 class="warning">There has been an issue sending your message. Please verify the content and try again.</h2>
<?php
		}
		if (!isset($_POST['sendFriend']) || sizeof($error_array) > 0 || sizeof($spam_error_array) > 0) {
?>

	<p>You can email the link <a href="<?php print encodeHtml($link); ?>">for this page</a> to a friend by completing the details below, along with a message.</p>

	<form name="sendFriend" action="<?php print getSiteRootURL() . buildNonReadableEmailFriendURL(base64_decode($_GET['link'])); ?>" method="post" enctype="multipart/form-data" onsubmit="document.getElementById('auth').value = '<?php print md5(DOMAIN . date('Y')); ?>'; return true;">
		<input type="hidden" name="auth" id="auth" value="fail" />
		<input type="hidden" name="authCode" id="authCode" value="<?php print isset($timerHash) ? $timerHash : ''; ?>" />
		<fieldset>
		<legend>Send the link to</legend>
		<ol>	
			<li>
				<label for="friend">
					<?php if (isset($error_array['friend'])) print "<strong>! ";?>
					Email address
					<?php if (isset($error_array['friend'])) print "</strong>";?>
					<em>(required)</em>
				</label>
				<input id="friend" type="text" name="friend" value="<?php print encodeHtml($friend); ?>" />
			</li>
		</ol>
		</fieldset>
		
		<fieldset>
		<legend>Your details</legend>
		<ol>
			<li>
				<label for="name">
					<?php if (isset($error_array['name'])) print "<strong>! ";?>
					Your name
					<?php if (isset($error_array['name'])) print "</strong>";?>
					<em>(required)</em>
				</label>
				<input id="name" type="text" name="name" value="<?php print encodeHtml($name); ?>" />
			</li>
			<li>
				<label for="email">
					<?php if (isset($error_array['email'])) print "<strong>! ";?>
					Email address
					<?php if (isset($error_array['email'])) print "</strong>";?>
					<em>(required)</em>
				</label>
				<input id="email" type="text" name="email" value="<?php print encodeHtml($email); ?>" />
			</li>
			<li>
				<label for="message">
					<?php if (isset($error_array['message'])) print "<strong>! ";?>
					Your message 
					<?php if (isset($error_array['message'])) print "</strong>";?>
					<em>(optional)</em>
				</label>
				<textarea id="message" name="message" rows="3" cols="2"><?php print encodeHtml($message); ?></textarea>
			</li>
<?php
	   if ($displayCAPTCHA) {
?>
			<li>
				<label for="recaptcha_challenge_field">
					<?php if (isset($error_array['recaptcha'])) print "<strong>! ";?>
				   Verification
				   <?php if (isset($error_array['recaptcha'])) print "</strong>";?>
				   <em>(required)</em>
				</label>
			   <?php echo $captcha->render(); ?>
			</li>
<?php
	   }
?>
			<li>
				<input type="submit" name="sendFriend" value="Send to my friend" />
			</li>
		</ol>	
		</fieldset>
	</form>

	<h2>Data Protection</h2>
	<p>The details you provide on this page will not be used to send unsolicited e-mail, and will not be sold to a 3rd party. <a href="<?php print getSiteRootURL() . buildTermsURL(); ?>">Privacy statement</a></p>

<?php 
	} 
	else { 
?>
	<h2>Thank you</h2>
	<p>An email has been sent to <?php print encodeHtml($_POST['friend']); ?> on your behalf.</p>
<?php 
	} 
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
