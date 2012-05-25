<?php
	include_once("websections/JaduFAQ.php");
	$commonFAQs = getTopXFAQs(5, FAQ_PROCESSED);
	
?>
	<div class="topFaqWidget">
		<h2>Frequently Asked Questions</h2>
<?php
	if (count($commonFAQs) > 0) {
?>
		<ul class="list icons faqs">
<?php
		foreach ($commonFAQs as $faqItem) {
	$catID = getFirstCategoryIDForItemOfType(FAQS_CATEGORIES_TABLE, $faqItem->id);		
?>
			<li class="long"><a href="<?php print getSiteRootURL() . buildFAQURL(false, $catID, $faqItem->id); ?>#a<?php print $faqItem->id;?>"><?php print encodeHtml($faqItem->question);?></a></li>
<?php
		}
?>
		</ul>
<?php
	}
?>
	</div>