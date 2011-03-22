<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("JaduCategories.php");
	include_once("websections/JaduFAQ.php");

	include_once("../includes/lib.php");
	
	if (isset($_GET['faqID']) && is_numeric($_GET['faqID'])) {
		$faq = getFAQ($_GET['faqID']);
	}	

	$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$allRootCategories = $lgclList->getTopLevelCategories();
	$rootCategories = filterCategoriesInUse($allRootCategories, FAQS_APPLIED_CATEGORIES_TABLE, true);

	$commonFAQs = getTopXFAQs (10);
	$breadcrumb = 'faqsIndex';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Frequently asked questions | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="FAQ, frequently asked question, query, queries, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s Frequently asked questions" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Frequently asked questions" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s Frequently asked questions" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
   
	<p class="first">Browse questions frequently asked <?php print METADATA_GENERIC_COUNCIL_NAME;?>.</p>

	<!-- Common FAQs -->
	<div class="divBox">
<?php
    if (sizeof($commonFAQs) > 0) {
?>
		
	<h2>Common <abbr title="Frequently asked questions">FAQs</abbr></h2>
	
<?php
		 foreach ($commonFAQs as $faqItem) {
?>
		
		<p class="first" id="a<?php print $faqItem->id;?>"><strong><abbr title="Question">Q</abbr>:</strong> <a href="http://<?php print $DOMAIN; ?>/site/scripts/faqs_index.php?faqID=<?php print $faqItem->id;?>#a<?php print $faqItem->id;?>" title="<?php print $faqItem->question;?>."><?php print $faqItem->question;?></a></p>
<?php
			  if ($faq->id == $faqItem->id) {
?>
		<div class="answer">
		 	<strong>Answer:</strong>  <div class="byEditor"><?php print $faq->answer;?></div>
		 </div>
<?php
			  }
		 }
    }
?>

	</div>
	
<?php
	foreach ($rootCategories as $rootCat) {
		$relCats = filterCategoriesInUse($lgclList->getChildCategories($rootCat->id), FAQS_APPLIED_CATEGORIES_TABLE, true);
		$splitArray = splitArray($relCats);
?>    
		<div class="cate_info">
			<h2><a href="http://<?php print $DOMAIN; ?>/site/scripts/faqs.php?categoryID=<?php print $rootCat->id;?>"><?php print $rootCat->name; ?></a></h2>
<?php
		if (sizeof($splitArray['left']) > 0) {
			print'<ul class="info_left list">';
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
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/faqs.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a></li>
<?php
			}
			print '</ul>';
		}
?>
		</div>
<?php
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
