<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="FAQ, frequently asked question, query, queries, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s Frequently asked questions regarding <?php print encodeHtml($faq->question);?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Frequently asked questions" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s Frequently asked questions regarding <?php print encodeHtml($faq->question);?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<div class="article">
	<p>Question: <?php print encodeHtml($faq->question); ?></p>
	<div class="byEditor answer">
		<p><strong>Answer:</strong></p>
		<?php print processEditorContent($faq->answer); ?>
	</div>
</div>
	
	<!-- post a question -->
	<form action="<?php print getSiteRootURL() . buildNonReadableFAQURL(true);?>" method="post" enctype="multipart/form-data">
		<fieldset>
			<legend>Do you have a question?</legend>
			<p>If there is anything you would like to ask us, about our services, our work or how we can help you, then please do.</p>
			<p>
				<input type="submit" value="Ask us a question" name="submit" />
			</p>
		</fieldset>
	</form>

	<?php include('../includes/bottom_supplements.php'); ?>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>