<?php
	include_once("utilities/JaduStatus.php");
	include_once("marketing/JaduUsers.php");
	include_once("JaduStyles.php");

	if (isset($_GET['sendAuthEmail'])) {

		$sentAuthEmail = false;

		$user = isUserRegistered($_GET['sendAuthEmail']);

		if ($user->id != -1 && !$user->isAuthenticated()) {
			emailNewUser($user->id);
			$sentAuthEmail = true;
			$_GET['email'] = $_GET['sendAuthEmail'];
		}
	}

	if (isset($_GET['auth']) && isset($_GET['email'])) {
		$user = isUserRegistered($_GET['email']);

		if ($user->getAuthenticationHash() == $_GET['auth']) {
			setUserAuthenticated($user->id);
			emailNewUser($user->id);
			$userID = $user->id;
			session_register('userID');
			$address = "http://" . $DOMAIN . "/site/scripts/register_accept.php";
			header("Location: $address");
			exit();
		}
	}
	$breadcrumb = 'registerAuthorisation';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Register | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="account, regstration, user, profile, register, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Registration" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Registration" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Registration" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
<?php
	if (isset($_GET['new']) || $sentAuthEmail) {
?>
		<h2>Thank you</h2>
		<p class="first">An email has been sent to <strong><?php print $_GET['email']; ?></strong>.</p>
		<p class="first">Please click on the link in the email to complete your registration.</p>
<?php
	}
	elseif (isset($_GET['auth'])) {
?>
		<h2>The authorisation failed</h2>
<?php
	}
	elseif (isset($_GET['authFailed'])) {
?>
		<p class="first">You have not confirmed your registration.</p>
		<p class="first">You have two options:</p>
		<ul class="list">
			<li><a href="<?php print $AUTHENTICATION_URL; ?>?sendAuthEmail=<?php print $_GET['authFailed']?>">Send an authorisation email to <?php print $_GET['authFailed']; ?></a></li>
			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/register.php">Register again</a></li>
		</ul>
<?php
	}
	elseif (!$sentAuthEmail) {
?>
		<p class="first">The email address <?php print $_GET['sendAuthEmail']; ?> was not found.</p>
<?php
	}
?>	

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>