<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="site map, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> sign in" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> sign in" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> sign in" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
<?php
	if (Jadu_Service_User::getInstance()->canUpdateUserPassword()) {
?>
	<p>If you have <a href="<?php print getSecureSiteRootURL() . buildForgotPasswordURL();?>">forgotten your password</a>, you can request it to be emailed to you.</p>
<?php
	}
?>

	<!-- Sign in -->
	<form class="basic_form xform" action="<?php print getSecureSiteRootURL() . buildNonReadableSignInURL(); ?>" method="post" enctype="multipart/form-data">
<?php
	if (isset($referer)) {
?>
	<input type="hidden" value="<?php print encodeHtml($referer); ?>" name="referer" autocomplete="off" />
<?php
	}
	
	if (isset($_GET['loginFailed'])) { 
?>
	<h2 class="warning">Sign-in failed, please try again</h2>
<?php 
	}
?>
		<fieldset>
			<ol>
				<li>
					<label for="YourEmail">Email</label>
					<input size="17" type="text" maxlength="50" name="email" id="YourEmail" autocomplete="off" />
				</li>
				<li>
					<label for="YourPassword">Password</label>
					<input size="17" type="password" name="password" maxlength="22" id="YourPassword" autocomplete="off" />
				</li>
				<li class="centre">
					<input type="submit" value="Sign-in" name="jaduSignInButton" class="genericButton grey" />
				</li>
			</ol>
		</fieldset>
	</form>	

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
