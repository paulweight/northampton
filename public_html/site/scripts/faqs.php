<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php"); 

	include_once("websections/JaduFAQ.php");
	include_once("egov/JaduCL.php");
	include_once("JaduCategories.php");

	include_once("../includes/lib.php");
	
	if (isset($_GET['faqID']) && is_numeric($_GET['faqID'])) {
		$faq = getFAQ($_GET['faqID'], FAQ_PROCESSED);
	}

	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		$allFAQs = getAllFAQsWithCategory ($_GET['categoryID'], FAQ_PROCESSED);
		
		//	Category Links
		$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$allCategories = $lgclList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, FAQS_APPLIED_CATEGORIES_TABLE, true);
		$splitArray = splitArray($categories);
		
		//	Category Links
		$currentCategory = $lgclList->getCategory($_GET['categoryID']);
		$dirTree = $lgclList->getFullPath($_GET['categoryID']);		
	} 
	else {
		$dirTree = array();
	}
	
	$breadcrumb = 'faqsCats';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $currentCategory->name;?> FAQs | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print $parent->name . ', '; } ?><?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s Frequently asked questions covering<?php foreach ($dirTree as $parent) { print ', ' . $parent->name; } ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Frequently asked questions<?php foreach ($dirTree as $parent) { ?> | <?php print $parent->name; } ?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s Frequently asked questions covering<?php foreach ($dirTree as $parent) { print ', ' . $parent->name; } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
                
	<p class="first">Click a question to reveal the answer.</p>
        
<?php
	if (sizeof($splitArray['left']) > 0 || sizeof($splitArray['right']) > 0) {
?>
		<div class="cate_info">
			<h2>Categories</h2>
<?php
		if (sizeof($splitArray['left']) > 0) {
			print '<ul class="info_left list">';
			foreach ($splitArray['left'] as $subCat) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/faqs.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a></li>
<?php
	  		}
			print '</ul>';
		}

		if (sizeof($splitArray['right']) > 0) {
			print '<ul class="info_right list">';
			foreach ($splitArray['right'] as $subCat) {
?>
					<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/faqs.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a> </li>
<?php
			}
			print '</ul>';
		}
?>
			<div class="clear"></div>
		</div>
        
<?php
	}
  
	if (sizeof($allFAQs) > 0) {
?>
		
		<h3><?php print $parent->name; ?> <abbr title="Frequently asked questions">FAQs</abbr></h3>
<?php
		foreach ($allFAQs as $faqItem) {
?>
			<p class="first" id="a<?php print $faqItem->id;?>"><strong><abbr title="Question">Q</abbr>:</strong> <a href="http://<?php print $DOMAIN; ?>/site/scripts/faqs.php?categoryID=<?php print $currentCategory->id;?>&amp;faqID=<?php print $faqItem->id;?>#a<?php print $faqItem->id;?>"><?php print $faqItem->question;?></a></p>
<?php
			if ($faq->id == $faqItem->id) {
?>
			<div class="answer">
				<strong>Answer:</strong> <div class="byEditor"><?php print str_replace("\t", "", $faq->answer);?></div>
			</div>
<?php
			}
		}
	}
?>

	<div class="displayBox">
		<div class="displayBoxIn">
			<h3>Do you have a Question?</h3>
			<p>Is there anything you would like to know about our services or how we can help?</p>
			<p class="first">Why not <a href="http://<?php print $DOMAIN;?>/site/scripts/faqs_ask.php">ask us a question?</a></p>
		</div>
	</div>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
    	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
