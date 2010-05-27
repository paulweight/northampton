<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once('JaduLibraryFunctions.php');
	include_once("marketing/JaduUsers.php");
	include_once('marketing/JaduPHPBB3.php');
	
	if (!isset($_SESSION['userID'])) {
		header("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}
	
	$error_array = array();
	$showMessage = false;
	
	if (isset($_SESSION['userID']) && isset($_POST['oldPassword']) && 
		isset($_POST['password']) && isset($_POST['passwordConfirm'])) {

		$user = getUser($_SESSION['userID']);
		
		if ($user->password != getPasswordHash($_POST['oldPassword'])) {
			$error_array['oldPassword'] = true;
		}
				
		if ($_POST['passwordConfirm'] != $_POST['password']) {
			$error_array['passwordsDifferent'] = true;
		}

		$passLength = strlen($_POST['password']);
		if ($passLength < 6 || $passLength > 30) {
			$error_array['passwordLength'] = true;
		}
		
		if (sizeof($error_array) == 0) {
			$user->password = $_POST['password'];
			updateUserPassword($user->id, $_POST['password']);
			$showMessage = true;
			
			if (defined('PHPBB_INTEGRATION') && PHPBB_INTEGRATION == true) {
			    updatePHPBBPassword ($user);
			}
		}
	}
	
	$breadcrumb = 'changePassword';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Change your password | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="account, regstration, user, profile, register, change, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> user account change password" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Change password" />
	<meta name="DC.identifier" content="http://<?php print $DOMAIN.$_SERVER['PHP_SELF'];?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
<?php
	if ($showMessage) {
?>
	<h2>Your password has been <span>changed successfully</span></h2>
	
	<div class="doc_info">
	<h3>Your account</h3>	
	<ul>
		<li><a href="http://<?php print $DOMAIN;?>/site/scripts/user_home.php">Your account</a></li>
		<li><a href="http://<?php print $DOMAIN;?>/site/scripts/change_details.php">Change your registration details</a></li>
	</ul>
	</div>
		
<?php 
	} 
	else { 
?>
		
	<p class="first">If you would like to change your password, please complete the details below. It must be <strong>5-20</strong> characters in length.</p>

<?php 
	if ($error_array['oldPassword']) { 
?>
	<h2 class="warning">! Your old password has been entered incorrectly</h2>
<?php 
	} 
	else if ($error_array['passwordsDifferent']) { 
?>
	<h2 class="warning">! Your new passwords do not match</h2>
<?php 
	}
	else if ($error_array['passwordLength']) { 
?>
	<h2 class="warning">! Your new password is not of the desired length</h2>
	<p>Please enter a password that is 5-20 characters in length</p>
<?php 
	}
 ?>

	<form name="changepass" action="http://<?php print $DOMAIN; ?>/site/scripts/change_password.php" method="post" class="basic_form">
		<fieldset>
			<p>
				<label for="oldPassword">
					<?php if ($error_array['oldPassword']) print "<strong>! ";?>
					Old password <em>(required)</em>
					<?php if ($error_array['oldPassword']) print "</strong>";?>
				</label>
				<input id="oldPassword" type="password" name="oldPassword" class="field<?php if ($error_array['oldPassword']) print " warning";?>" maxlength="20" value="" />
			</p>
			<p>
				<label for="newpassword">
					<?php if ($error_array['passwordsDifferent'] || $error_array['passwordLength']) print "<strong>! ";?>
					New password <em>(required)</em>
					<?php if ($error_array['passwordsDifferent'] || $error_array['passwordLength']) print "</strong>";?>
					
				</label>
				<input id="newpassword" type="password" name="password" class="field<?php if ($error_array['passwordsDifferent'] || $error_array['passwordLength']) print " warning";?>" maxlength="20" value="" />
			</p>
			<p>
				<label for="passwordsDifferent">
					<?php if ($error_array['passwordsDifferent']) print "<strong>! ";?>
					Confirm password <em>(required)</em>
					<?php if ($error_array['passwordsDifferent']) print "</strong>";?>
				</label>
				<input id="passwordsDifferent" type="password" name="passwordConfirm" class="field<?php if ($error_array['passwordsDifferent']) print " warning";?>" maxlength="20" value="" />
			</p>
			<p class="centre">
				<input type="submit" name="getPassword" value="Change it!" class="button" />
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
