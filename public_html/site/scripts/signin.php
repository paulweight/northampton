<?php 
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");

	if (isset($_GET['logout'])) {
		session_unregister("userID");
		unset($userID);
		
		if (defined('PHPBB_INTEGRATION') && PHPBB_INTEGRATION == true) {
		    header("Location: http://$DOMAIN/site/scripts/phpbb_login.php?logout=true");
		    exit();
	    }
	} 
	
	else if (isset($_SESSION['userID'])) {
		unset($_GET['sign_in']);
		header("Location: http://$DOMAIN/site/scripts/user_home.php");
	}
	
	if (isset($_GET['loginFailed'])) {
		$_GET['sign_in'] = 'true';
	}		
	
	$breadcrumb = 'signIn';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Sign in to your account | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY;?>generic/homepages.css" media="screen" />	
	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY;?>generic/homepageElements.php?homepageID=<?php print $allIndependantHomepages[0]->id; ?>" media="screen" />

	<meta name="Keywords" content="home, homepage, index, root, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print $DOMAIN;?> is the online resource for <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online - Tel: <?php print $address->telephone;?>" />
	<meta name="DC.description" lang="en" content="<?php print $DOMAIN;?> is the online resource for <?php print METADATA_GENERIC_COUNCIL_NAME;?>" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

		
<?php
	if ($_SERVER['QUERY_STRING'] == "logout=true") {
		$action = 'https://'.$DOMAIN."/site/signin.php";
	}	
	else {
		$action = 'http://'.$DOMAIN.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	}
?>

			<!-- Sign in -->
			<form action="<?php print htmlentities($action, ENT_QUOTES); ?>" method="post" class="basic_form">
<?php
		if (isset($_REQUEST['referer'])) {
?>
			<input type="hidden" value="<?php print htmlentities($_REQUEST['referer'], ENT_QUOTES); ?>" name="referer" />
<?php
		}
			
		if (isset($loginFailed)) { 
?>
			<h2 class="warning">Sign-in failed, please try again</h2>
<?php 
		} 
?>
				<fieldset>
					<p>
						<label for="YourEmail">Email:</label>
						<input size="17" type="text" maxlength="50" name="email" class="field" id="YourEmail" />
					</p>
					<p>
						<label for="YourPassword">Password:</label>
						<input size="17" type="password" name="password" maxlength="22" class="field" id="YourPassword" />
					</p>
					<p class="centre">
						<input type="submit" value="Sign-in" class="button" />
					</p>
				</fieldset>
			</form>
	<p class="first">Not have an account? <a href="http://<?php print $DOMAIN;?>/site/scripts/register.php">Create one here</a>.</p>
		
	<p class="first">If you have <a href="http://<?php print $DOMAIN;?>/site/scripts/forgot_password.php">forgotten your password</a>, you can request it to be emailed to you.</p>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
	

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
