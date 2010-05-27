<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");

	if (isset($_GET['link'])) {
		$link = "http://" . $DOMAIN . "/site/scripts/" . urldecode($_GET['link']);
	}
	else {
		$link = $_POST['link'];
	}

	$error_array = array();

	if (isset($_POST['sendFriend'])) {

		//	Some validation
		if (trim($_POST['message']) == "") { 
			$error_array['message'] = true;
		}
		if ($_POST['auth'] == 'fail' || $_POST['auth'] != $DOMAIN.date('Y')) {
			$error_array['auth'] = true;
		}		
		//	end validation

		if (sizeof($error_array) == 0) {
		
			if($_POST['email'] !='') {
				$emailAdr = $_POST['email'];
			}
			else {
				$emailAdr = $DEFAULT_EMAIL_ADDRESS;
			}
			
			if($_POST['name'] !='') {
				$emailName = $_POST['name'];
			}
			else {
				$emailName = $DEFAULT_EMAIL_ADDRESS;
			}

			$HEADER = "From: " . $emailAdr . "\r\nReply-to: " . $emailAdr . "\r\nContent-Type: text/plain; charset=iso-8859-1;\r\n";
			$SUBJECT = $emailName . " has sent comments";

			$MESSAGE = $emailName . " has sent you comments on the following content from ".METADATA_GENERIC_COUNCIL_NAME." Online: " . html_entity_decode($link);
			if ($_POST['message'] != "") { 
				$MESSAGE .= "\r\n\r\n Comments: " . $_POST['message'];
			}

			mail($DEFAULT_EMAIL_ADDRESS, $SUBJECT, $MESSAGE, $HEADER);

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

	$breadcrumb = 'comments';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Comment on a page | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="Accessibility, dda, disability discrimination act, disabled access, access keys, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Accessibility features" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

	<script type="text/javascript">
	<!--
		function preSubmit()
		{
			document.getElementById('auth').value = '<?php print $DOMAIN . date('Y'); ?>';
		}
	-->
	</script>
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
		if (!empty($error_array['auth'])) {
?>
		<h2 class="warning">You must have javascript enabled to use the comment on this page feature</h2>
<?php
		} elseif (sizeof($error_array) > 0) {
?>
		<h2 class="warning">Please check details highlighted <strong>!</strong> are entered correctly</h2>
<?php
		}
		if (!isset($_POST['sendFriend']) || sizeof($error_array) > 0) {
?> 	
		<form name="sendFriend" action="http://<?php print $DOMAIN; ?>/site/scripts/pageComments.php" method="post" class="basic_form" onsubmit="preSubmit(); return true;">
			<input type="hidden" name="link" value="<?php print $link;?>" />
			<input type="hidden" name="auth" id="auth" value="fail" />						
			<fieldset>
				<legend>Your details</legend>
				<p>You are commenting on <a href="<?php print $link;?>">this page</a> by completing the form.  Type your comments or details of the problem you have encountered.</p>
				<p>					
					<label for="message"><?php if ($error_array['message']) print "<strong>! ";?>Your comment <?php if ($error_array['message']) print "</strong>";?><em>(required)</em></label>
					<textarea id="message" name="message" rows="3" cols="2" class="field"><?php print $message;?></textarea>
				</p>
			</fieldset>		
			<fieldset>		
				<legend>Your details</legend>
				<p>					
					<label for="name">
						Your name
					</label>
					<input id="name" type="text" name="name" class="field" value="<?php print $name;?>" />
				</p>
				<p>					
					<label for="email">
						Email address
					</label>
					<input id="email" type="text" name="email" class="field" value="<?php print $email;?>" />
				</p>

				<p class="centre">					
					<input type="submit" name="sendFriend" value="Send comment" class="button" />
				</p>
			</fieldset>
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
		<p class="first">An email has been sent to <em><?php print METADATA_GENERIC_COUNCIL_NAME;?></em> on your behalf.</p>
<?php 
 	}
 ?>
                
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
