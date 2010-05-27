<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");

	if (isset($_GET['link'])) {
		$link = "http://" . $DOMAIN . "/site/scripts/" . urldecode($_GET['link']);
	}
	else {
		$link = $_POST['link'];
	}

	$error_array = array('spam' => true);

	if (isset($_POST['sendFriend'])) {

		//	Some validation
		if (!preg_match("/^[-.,£$@&:;(\)\+\=\"\'\?\!a-zA-Z0-9\s]+$/", $_POST['name']))
			$error_array['name'] = true;
		if (!preg_match('/[0-9A-Za-z\.\-_]{1,127}@[0-9A-Za-z\.\-_]{1,127}/', $_POST['email']))
			$error_array['email'] = true;
		if (!preg_match('/[0-9A-Za-z\.\-_]{1,127}@[0-9A-Za-z\.\-_]{1,127}/', $_POST['friend']))
			$error_array['friend'] = true;
		if ($_POST['friend'] == $_POST['email']) {
			$error_array['email'] = true;
			$error_array['friend'] = true;
		}

		// A hidden field used to determine whether a bot submitted the form, assuming bots don't execute JavaScript
		if (!isset($_POST['auth']) || $_POST['auth'] != DOMAIN . date('Y')) {
			$error_array['auth'] = true;
		}
		
		// A hidden field used to lure bots into submitting some data, genuine users shouldn't see the field
		if (isset($_POST['email_address']) && $_POST['email_address'] != '') {
			$error_array['human_test'] = true;
		}

		//	end validation

		if (sizeof($error_array) == 0) {

			$HEADER = "From: " . $_POST['email'] . "\r\nReply-to: " . $_POST['email'] . "\r\nContent-Type: text/plain; charset=iso-8859-1;\r\n";
			$SUBJECT = $_POST['name'] . " has sent you a link";

			$MESSAGE = $_POST['name'] . " has sent you a link to the following content from ".METADATA_GENERIC_COUNCIL_NAME." Online: " . $_POST['link'];
			if ($_POST['message'] != "") { 
				$MESSAGE .= "\r\n\r\n" . $_POST['name'] . " has added the following message: " . $_POST['message'];
			}
			$MESSAGE .= "\r\n\r\nKind Regards,\r\n\r\n" . METADATA_GENERIC_COUNCIL_NAME;

			mail($_POST['friend'], $SUBJECT, $MESSAGE, $HEADER);

		} else {
			$name = $_POST['name'];
			$email = $_POST['email'];
			$message = $_POST['message'];
			$friend = $_POST['friend'];
		}
	}
	else {

		$name = "";
		$email = "";
		$message = "";
		$friend = "";

		if (!isset($_POST['name']) && isset($_SESSION['userID'])) {
			$name = "$user->forename $user->surname";
		}
		if (!isset($_POST['email']) && isset($_SESSION['userID'])) {
			$email = $user->email;
		}
	}

	$breadcrumb = 'emailFriend';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Email a friend | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="email, friend, send, link, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="Email a page to a friend from <?php print METADATA_GENERIC_COUNCIL_NAME;?>s website" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> email a friend" />
	<meta name="DC.description" lang="en" content="Email a page to a friend from <?php print METADATA_GENERIC_COUNCIL_NAME;?>s website" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
        
<?php
		if (sizeof($error_array) > 0) {
?>
		<h2 class="warning">Please check details highlighted <strong>!</strong> are entered correctly</h2>
<?php
		}
		if (!isset($_POST['sendFriend']) || sizeof($error_array) > 0) {
?>
	
		<p class="first">You can email the link <a href="<?php print $link;?>">for this page</a> to a friend by completing the form below, along with a message (optional).</p>
		
		<form name="sendFriend" onsubmit="document.getElementById('auth').value = '<?php print DOMAIN . date('Y'); ?>'; return true;" action="http://<?php print $DOMAIN; ?>/site/scripts/email_friend.php" method="post" class="basic_form">
			<input type="hidden" name="link" value="<?php print $link;?>" />
			<input type="hidden" name="auth" id="auth" value="fail" />
			<fieldset>
				<legend>Send link to...</legend>
				<p>
					<label for="friend">
						<?php if ($error_array['friend']) print "<strong>! ";?>
						Email address
						<?php if ($error_array['friend']) print "</strong>";?>
						<em>(required)</em>
					</label>
					<input id="friend" type="text" name="friend" class="field" value="<?php print $friend;?>" />
					<span class="clear"></span>
				</p>
			</fieldset>
			<fieldset>
				<legend>Your details</legend>
				<p>
					<label for="name">
						<?php if ($error_array['name']) print "<strong>! ";?>
						Your name
						<?php if ($error_array['friend']) print "</strong>";?>
						<em>(required)</em>
					</label>
					<input id="name" type="text" name="name" class="field" value="<?php print $name;?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="email">
						<?php if ($error_array['email']) print "<strong>! ";?>
						Email address
						<?php if ($error_array['friend']) print "</strong>";?>
						<em>(required)</em>
					</label>
					<input id="email" type="text" name="email" class="field" value="<?php print $email;?>" />
					<span class="clear"></span>
				</p>
				<p>
					<label for="message">Your message <em>(optional)</em></label>
					<textarea id="message" name="message" rows="3" cols="2" class="field"><?php print $message;?></textarea>
					<span class="clear"></span>
				</p>
				<p class="centre">
					<input type="submit" name="sendFriend" value="Send to my friend" class="button" />
				</p>
			</fieldset>
			<input type="text" name="email_address" id="email_address" value="" style="display:none;" />
		</form>
        
		<div class="content_box">
			<h2>Data Protection</h2>
			<p>The details you provide on this page will not be used to send unsolicited e-mail, and will not be sold to a 3rd party. <a href="http://<?php print $DOMAIN; ?>/site/scripts/terms.php">Privacy Statement</a></p>
		</div>
<?php 
	} 
  	else { 
?>
		<h2>Thank you</h2>
		<p class="first">An email has been sent to <em><?php print $_POST['friend'];?></em> on your behalf.</p>
<?php 
	} 
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
