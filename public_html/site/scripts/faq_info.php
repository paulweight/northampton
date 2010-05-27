<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php"); 
	include_once("websections/JaduFAQ.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");

	if (isset($_GET['faqID']) && is_numeric($_GET['faqID'])) {
		$faq = getFAQ($_GET['faqID']);

		$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$categoryID = getFirstCategoryIDForItemOfType (FAQS_CATEGORIES_TABLE, $faq->id, "LGCL");	
		$currentCategory = $lgclList->getCategory($_GET['categoryID']);
		$dirTree = $lgclList->getFullPath($_GET['categoryID']);
	}
	else {
		header("Location: ./faqs_index.php");
		exit();
	}
	
	$breadcrumb = 'faqInfo';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Frequently asked questions | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="FAQ, frequently asked question, query, queries, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s Frequently asked questions regarding <?php print $faq->question;?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Frequently asked questions" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s Frequently asked questions regarding <?php print $faq->question;?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<div class="answer"><strong>Answer:</strong><div class="byEditor"><?php print $faq->answer;?></div></div>
			
	<div class="displayBox">
		<div class="displayBoxIn">
			<h2>Do you have a Question?</h2>
			<p>Is there anything you would like to know about our services or how we can help?</p>
			<p class="first">Why not <a href="http://<?php print $DOMAIN;?>/site/scripts/faqs_ask.php">ask us a question?</a></p>
		</div>
	</div>
		
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>