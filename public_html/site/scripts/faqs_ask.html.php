<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="FAQ, frequently asked question, ask, query, queries, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s ask a question feature" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Ask a Question" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s ask a question feature" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
        
	<p>If there is anything you would like to ask us please use the form below. We will endeavour to reply as soon as possible.</p> 
	
<?php
	if (isset($validation_array) && !$validation_array['email']) { 		 
?>
	<h2 class="warning">! Please enter a valid email address before sending your question</h2>
<?php
	}
	if (isset($validation_array) && !$validation_array['question']) {
?>	
	<h2 class="warning">! Please ask us a question</h2>
<?php
	}
	if (isset($validation_array) && !in_array(false, $validation_array) ) {
		print "<h2>Thank you for submitting your question</h2>";
	}
?>  
	<!-- Post a question -->
	<form action="<?php print getSiteRootURL() . buildNonReadableFAQURL(true) ;?>" method="post" enctype="multipart/form-data">
		<fieldset>
			<legend>Ask us your question</legend>
			<ol>
			<li>
				<label for="email">
					<?php if (isset($validation_array) && !$validation_array['email']) print "<strong>! ";?>
					Your email
					<?php if (isset($validation_array) && !$validation_array['email']) print "</strong>";?>
					<em>(required)</em>
				</label>
				<input id="email" type="text" name="email" value="<?php print encodeHtml((isset($validation_array) && !$validation_array['email']) || $error ? $_POST['email'] : (isset($user) ? $user->email : '')); ?>" />
			</li>
			<li>
				<label for="question">
				<?php if (isset($validation_array) && !$validation_array['question']) print "<strong>! ";?>
				Your question?
				<?php if (isset($validation_array) && !$validation_array['question']) print "</strong>";?>
				<em>(required)</em>
				</label>
				<textarea id="question" name="question" rows="3" cols="2"><?php if ((isset($validation_array) && !$validation_array['question']) || ($error)) print encodeHtml($_POST['question']); ?></textarea>
			</li>
			<li>
				<input type="submit" value="Send Your Question" name="submit" />
			</li>
			</ol>
		</fieldset>
	</form>
		
	<p class="note">Note: Your question may be added to our FAQ database and used on this site.</p>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
