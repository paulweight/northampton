<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	
	$error = false;
	if (!preg_match('/[0-9A-Za-z\.\-_]{1,127}@[0-9A-Za-z\.\-_]{1,127}/', $_POST['email'])) {
		$error = true;	
	}
	
	if (isset($_POST['getPassword']) && !$error) {
		$u = isUserRegistered($_POST['email']);
		if (strtolower($u->email) == strtolower($_POST['email'])) {
			sendUserPassword($_POST['email']);	
			unset($u);
		}
		else {
			$error = true;
		}
	}
	
	$breadcrumb = 'forgotPassword';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Password reminder | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="password, reminder, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Forgotten password reminder" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Password reminder" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Forgotten password reminder" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (isset($_POST['getPassword']) && $error) {
?>
		<h2 class="warning">Please check your email address <strong>!</strong> is entered correctly</h2>
<?php
	}
	if (isset($_POST['getPassword']) && !$error) {
?>
		<h2>Thank you</h2>
		<p class="first">An email has been sent to <em><?php print $_POST['email'];?></em> containing the requested password.</p>
<?php 
	} 
	else { 
?>
		<p>If you have forgotton your password, please enter your email address below. Your password will then be emailed to you straight away.</p>

		<form name="getpass" action="http://<?php print $DOMAIN; ?>/site/scripts/forgot_password.php" method="post" class="basic_form">
			<fieldset>
				<legend>Please enter your email address</legend>
					<p>
						<label for="forgot_email">
<?php 
		if (isset($_POST['getPassword']) && $error) {
			print "<strong>! Your email address</strong>";
		}
		else {
			print 'Your email address';
		}
?>
						</label>
						<input id="forgot_email" type="text" name="email" class="field" />
						<span class="clear"></span>
					</p>
				<p class="centre">
					<input type="submit" name="getPassword" value="Remind me" class="button" />
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
