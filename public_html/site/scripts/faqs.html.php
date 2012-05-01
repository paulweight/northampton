<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print encodeHtml($parent->name) . ', '; } ?><?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s Frequently asked questions covering<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Frequently asked questions<?php foreach ($dirTree as $parent) { ?> | <?php print encodeHtml($parent->name); } ?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?>&#39;s Frequently asked questions covering<?php foreach ($dirTree as $parent) { print ', ' . encodeHtml($parent->name); } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
        
<?php  
	if (sizeof($allFAQs) > 0) {
?>
		
	<h2><?php print encodeHtml($parent->name); ?> questions</h2>
<ul class="list icons faqs">	
<?php
		foreach ($allFAQs as $faqItem) {
?>
<li class="long">
	
		<strong>Q:</strong> <a href="<?php print getSiteRootURL() . buildFAQURL(false, $currentCategory->id, $faqItem->id); ?>#a<?php print (int) $faqItem->id;?>"><?php print encodeHtml($faqItem->question); ?></a>
	
<?php
			if (isset($faq) && $faq->id == $faqItem->id) {
?>
	<div class="byEditor answer">
		<p><strong>Answer:</strong></p>	
		<?php print processEditorContent($faq->answer); ?>
	</div>
		
<?php
			}
		}
?>
</li>	
<?php
	} ?> </ul><?php

	if (sizeof($categories) > 0) {
?>
		<div class="cate_info">
			<h3>Categories</h3>
<?php
		if (sizeof($categories) > 0) {
			print '<ul class="list icons faqs">';
			foreach ($categories as $subCat) {
?>
				<li><a href="<?php print getSiteRootURL() .buildFAQURL(false, $subCat->id) ;?>"><?php print encodeHtml($subCat->name); ?></a></li>
<?php
	  		}
			print '</ul>';
		}
?>
		</div>
        
<?php
	}
?>

	<!-- post a question -->
	<form action="<?php print getSiteRootURL() . buildNonReadableFAQURL(true);?>" method="post" enctype="multipart/form-data">
		<fieldset>
			<legend>Do you have a question?</legend>
			<p>If there is anything you would like to ask us, about our services, our work or how we can help you, then please do.</p>
			<p class="centre">
				<input type="submit" value="Ask us a question" name="submit" class="genericButton grey" />
			</p>
		</fieldset>
	</form>
	
	<p><a class="rss" href="<?php print getSiteRootURL() . buildCategoryRSSURL("faqs", $_GET['categoryID']); ?>"><?php print encodeHtml(METADATA_GENERIC_NAME . ' ' . $currentCategory->name); ?>  feed</a></p>
	
	<?php include('../includes/bottom_supplements.php'); ?>
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
