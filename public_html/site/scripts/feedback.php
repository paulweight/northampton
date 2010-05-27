<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	
	$salutation = "";
	$forename = "";
	$surname = "";
	$address = "";
	$postcode = "";
	$country = "";
	$email = "";
	$telephone = "";
	$comments = "";
	
	if (isset($_POST['submit'])) {
	
		if ($_POST['forename'] == "") {
			$error_array['forename'] = true;
		}
		if ($_POST['surname'] == "") {
			$error_array['surname'] = true;
		}
		if ($_POST['address'] == "") {
			$error_array['address'] = true;
		}
		if ($_POST['email'] == "") {
			$error_array['email'] = true;
		}
		if ($_POST['telephone'] == "") {
			$error_array['telephone'] = true;
		}
		if ($_POST['comments'] == "") {
			$error_array['comments'] = true;
		}
		if ($_POST['auth'] == 'fail' || $_POST['auth'] != $DOMAIN.date('Y')) {
				$error_array['auth'] = true;		    
		}		
		
		if (sizeof($error_array) == 0) {

			$headerEmail = $_POST['email'];
			if ($headerEmail == "") {
				$headerEmail = $DEFAULT_EMAIL_ADDRESS;
			}
			
			$HEADER = "From: $headerEmail\r\nReply-to: $headerEmail\r\nContent-Type: text/plain; charset=iso-8859-1;\r\n";
			$SUBJECT = $DOMAIN. " feedback enclosed.";
			$MESSAGE = "Please find here some feedback from the $DOMAIN website.\n\n";

			$CONTACT_STRING = "";
			if ($_POST['forename'] != "" || $_POST['surname'] != "") {
				$CONTACT_STRING .= "Provided by: " . $_POST['salutation'] . " " . $_POST['forename'] . " " . $_POST['surname'] . "\n";
			}
			if ($_POST['address'] != "") {
				$CONTACT_STRING .= "Location: " . nl2br($_POST['address']). "\n";
			}
			if ($_POST['country'] != "" && $_POST['country'] != -1) {
				$CONTACT_STRING .= "Country: " . $_POST['country']. "\n";
			}
			if ($_POST['postcode'] != "") {
				$CONTACT_STRING .= "Postcode: " . $_POST['postcode']. "\n";
			}
			if ($_POST['email'] != "") {
				$CONTACT_STRING .= "Email: " . $_POST['email']. "\n";
			}
			if ($_POST['telephone'] != "") {
				$CONTACT_STRING .= "Telephone: " . $_POST['telephone']. "\n";
			}
			
			if ($CONTACT_STRING != "") {
				$MESSAGE .= "CONTACT DETAILS\n" . $CONTACT_STRING;
			}
			
			if ($_POST['comments'] != "") {
				$MESSAGE .= "COMMENTS\n" . nl2br($_POST['comments']) . "\n";
			}

			mail($DEFAULT_EMAIL_ADDRESS, $SUBJECT, $MESSAGE, $HEADER);		
			header("Location: ./thanks.php");
			exit();

		}
	}

	if (!isset($_POST['submit']) && isset($_SESSION['userID'])) {
		$user = getUser($_SESSION['userID']);
		$salutation = $user->salutation;
		$forename = $user->forename;
		$surname = $user->surname;
		$address = $user->address;
		$postcode = $user->postcode;
		$country = $user->country;
		$email = $user->email;
		$telephone = $user->telephone;
		$comments = "";
	}
	elseif(isset($_POST['submit'])) {
		$salutation = $_POST['salutation'];
		$forename = $_POST['forename'];
		$surname = $_POST['surname'];
		$address = $_POST['address'];
		$postcode = $_POST['postcode'];
		$country = $_POST['country'];
		$email = $_POST['email'];
		$telephone = $_POST['telephone'];
	}

	$breadcrumb = 'feedback';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Feedback | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="feedback, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="Send your feedback directly to <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> feedback" />
	<meta name="DC.description" lang="en" content="Send your feedback directly to <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

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
	<h2 class="warning">You must have javascript enabled to use submit feedback</h3>
<?php
	} 
	else if (sizeof($error_array) > 0) {
?>
	<h2 class="warning">Please check details highlighted <strong>!</strong> are entered correctly.</h2>
<?php
	}
?>
	<form action="http://<?php print $DOMAIN; ?>/site/scripts/feedback.php" method="post" enctype="x-www-form-encoded" class="basic_form" onsubmit="preSubmit(); return true;">
		<fieldset>
			<legend>Your comments or suggestions</legend>
			<input type="hidden" name="auth" id="auth" value="fail" />	
			<p>
				<label for="Salutation"> Salutation</label>
				<select class="select" name="salutation" id="Salutation">
					<option <?php if ($salutation == "Select...") print "selected"; ?> value="">Select...</option>
					<option <?php if ($salutation == "Mr") print "selected"; ?> value="Mr">Mr</option>
					<option <?php if ($salutation == "Miss") print "selected"; ?> value="Miss">Miss</option>
					<option <?php if ($salutation == "Mrs") print "selected"; ?> value="Mrs">Mrs</option>
					<option <?php if ($salutation == "Ms") print "selected"; ?> value="Ms">Ms</option>
					<option <?php if ($salutation == "Dr") print "selected"; ?> value="Dr">Dr</option>
					<option <?php if ($salutation == "Other") print "selected"; ?> value="Other">Other</option>
				</select>
			</p>
			<p>
				<label for="Forename"><?php if ($error_array['forename']) { ?><strong>! <?php } ?>Forename (required)<?php if ($error_array['forename']) { ?></strong><?php } ?> </label>
				<input id="Forename" type="text" name="forename" class="field<?php if ($error_array['forename']) { ?> warning<?php } ?>" value="<?php print $forename;?>" />
			</p>
			<p>
				<label for="Surname"><?php if ($error_array['surname']) { ?><strong>! <?php } ?>Surname (required)<?php if ($error_array['surname']) { ?></strong><?php } ?></label>
				<input id="Surname" type="text" name="surname" class="field<?php if ($error_array['surname']) { ?> warning<?php } ?>" value="<?php print $surname;?>" />
			</p>
			<p>
				<label for="Address"><?php if ($error_array['address']) { ?><strong>! <?php } ?>Location (required)<?php if ($error_array['address']) { ?></strong><?php } ?> </label>
				<input id="Address" type="text" name="address" class="field<?php if ($error_array['address']) { ?> warning<?php } ?>" value="<?php print $address;?>" />
			</p>
			<p>
				<label for="Email"><?php if ($error_array['email']) { ?><strong>! <?php } ?>Email Address (required)<?php if ($error_array['email']) { ?></strong><?php } ?> </label>
				<input id="Email" type="text" name="email" class="field<?php if ($error_array['email']) { ?> warning<?php } ?>" value="<?php print $email;?>" />
			</p>
			<p>
				<label for="Telephone"><?php if ($error_array['telephone']) { ?><strong>! <?php } ?>Telephone (required)<?php if ($error_array['telephone']) { ?></strong><?php } ?></label>
				<input id="Telephone" type="text" name="telephone" class="field<?php if ($error_array['telephone']) { ?> warning<?php } ?>" value="<?php print $telephone;?>" />
			</p>
			<p>
				<label for="comments"><?php if ($error_array['comments']) { ?><strong>! <?php } ?>Your comments (required)<?php if ($error_array['comments']) { ?></strong><?php } ?> </label>
				<textarea id="comments" name="comments" class="field<?php if ($error_array['comments']) { ?> warning<?php } ?>" cols="2" rows="5"><?php print $comments;?></textarea>
			</p>
			<p class="centre">
				<input type="submit" value="Send your feedback" name="submit" class="button" />
			</p>
		</fieldset>
	</form>


	<h2>Data Protection</h2>
	<p>The details you provide on this page will not be used to send unsolicited e-mail, and will not be sold to a 3rd party. <a href="http://<?php print $DOMAIN; ?>/site/scripts/terms.php">Privacy Statement</a></p>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>