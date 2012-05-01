<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="<?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="Submit a comment directly to <?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> comment submission" />
	<meta name="DC.description" lang="en" content="Submit a comment directly to <?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

	<script type="text/javascript">
	<!--
		function preSubmit()
		{
			document.getElementById('auth').value = '<?php print md5(DOMAIN . date('Y')); ?>';
		}
	-->
	</script>
</head>
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
	<h2 class="warning">Please check details highlighted with ! are entered correctly</h2>
<?php
		}
		if (!isset($_POST['sendComment']) || sizeof($error_array) > 0) {
?> 	
	<p>You are commenting on <a href="<?php print encodeHtml($link); ?>">this page</a> by completing the form.  Type your comments or details of the problem you have encountered.</p>

	<form class="basic_form xform" action="<?php print getSiteRootURL() . buildNonReadablePageCommentsURL(base64_decode($_GET['link'])); ?>" method="post" enctype="multipart/form-data" onsubmit="preSubmit(); return true;">
		<div><input type="hidden" name="auth" id="auth" value="fail" /></div>
		<fieldset>
			<legend>Your comment and details</legend>
			<ol>
				<li>					
					<label for="name">Your name</label>
					<input id="name" type="text" name="name" value="<?php print encodeHtml($name); ?>" />
				</li>
				<li>					
					<label for="email">Email address</label>
					<input id="email" type="text" name="email" value="<?php print encodeHtml($email); ?>" />
				</li>
				<li>					
					<label for="message"><?php if (isset($error_array['message'])) print "<strong>! ";?>Your comment <?php if (isset($error_array['message'])) print "</strong>";?><em>(required)</em></label>
					<textarea id="message" name="message" rows="3" cols="2"><?php print encodeHtml($message); ?></textarea>
				</li>
				<li class="centre">					
					<input type="submit" name="sendComment" value="Send your comment" class="genericButton grey" />
				</li>
			</ol>
		</fieldset>
	</form>

	<h2>Data Protection</h2>
	<p>The details you provide on this page will not be used to send unsolicited e-mail, and will not be sold to a 3rd party. <a href="<?php print getSiteRootURL() . buildTermsURL(); ?>">Privacy statement</a>.</p>
		        
<?php 
	} 
	else { 
?>
		<h2>Thank you</h2>
		<p>An email has been sent to <?php print encodeHtml(METADATA_GENERIC_NAME); ?> on your behalf.</p>
<?php 
 	}
 ?>
                
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
