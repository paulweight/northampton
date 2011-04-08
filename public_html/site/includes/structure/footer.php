<?php

	include_once("egov/JaduCL.php");
	include_once("JaduAppliedCategories.php");

	$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
	$allRootCategories = $lgclList->getTopLevelCategories();
	$rootDocumentCategories = filterCategoriesInUse($allRootCategories, DOCUMENTS_APPLIED_CATEGORIES_TABLE, true);
	$rootHomepageCategories = filterCategoriesInUse($allRootCategories, HOMEPAGE_APPLIED_CATEGORIES_TABLE, true);

	$categoriesUsed = array();
	$rootCategories = array();

	foreach ($rootDocumentCategories as $index => $item) {
			$categoriesUsed[] = $item->id;
			$rootCategories[] = $item;
	}
	foreach ($rootHomepageCategories as $index => $item) {
			if (!in_array($item->id, $categoriesUsed)) {
					$categoriesUsed[] = $item->id;
					$rootCategories[] = $item;
			}
	}
	ksort($rootCategories);


    $pageUrl = $DOMAIN.$_SERVER['PHP_SELF'];
    if ($_SERVER['QUERY_STRING'] != '') {
    	$pageUrl .= '?' . htmlspecialchars($_SERVER['QUERY_STRING']);
    }
?>
	</div><!-- end of page_wrap -->
	
<!-- The LGNL taxonomy -->

<?php if (sizeof($rootCategories) > 0) { ?>
	<div id="footerLgnl">
		<h2>Information by category</h2>
		<ul>
			<?php foreach ($rootCategories as $category) { ?><li><a href="http://<?php print $DOMAIN;?>/site/scripts/documents.php?categoryID=<?php print $category->id;?>"><?php print $category->name; ?></a></li><?php } ?>
		</ul>
	</div>
<?php } ?>

<!-- End of the taxonomy -->

	<div id="footer">	
<!-- START Socitm Code -->
<script type="text/javascript">
   /* <![CDATA[ */
    var socitm_my_domains = "www.northampton.gov.uk";
    var socitm_custcode = "263";
    var socitm_intro_file = "http://<?php print $DOMAIN; ?>/site/includes/socitm_intro.php";
  /* ]]> */
</script>
<script type="text/javascript" src="http://socitm.govmetric.com/js/socitm_wrapper.aspx"></script>
<!-- END Socitm Code -->

		<p class="top"><a href="http://<?php print $DOMAIN.$_SERVER['PHP_SELF']."?".htmlspecialchars($_SERVER['QUERY_STRING']);?>#wrapper">Jump to the top</a></p>
		<p><span><?php print METADATA_GENERIC_COUNCIL_NAME;?> &copy; <?php print date ("Y");?></span></p>
			
		<ul>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/terms.php">Terms &amp; disclaimer</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/accessibility.php">Accessibility statement</a></li>
			<li><a href="http://<?php print $DOMAIN;?>/site/scripts/website_statistics.php">Website statistics</a></li>
			<li><a href="http://<?php print $DOMAIN ?>/site/scripts/site_map.php">Site map</a></li>
		</ul>
		<noscript>
     		<a href="http://socitm.govmetric.com/survey.aspx?code=263">Tell us what you think about our site...</a>
     		<img src="http://socitm.govmetric.com/imagecounter.aspx?code=263" height="1" width="1" alt="" />
		</noscript>		
			
		<p>Made with <a href="http://validator.w3.org/check?uri=referer">XHTML</a> and <a href="http://jigsaw.w3.org/css-validator/">CSS</a> to <a href="http://www.w3.org/WAI/WCAG1AA-Conformance"><abbr title="Web Accessibility Initiative - Double-A conformance">WAI-AA</abbr></a>. <a href="http://www.fosi.org/icra/"><abbr title="Internet Content Rating Association">ICRA</abbr> rated</a>. Powered by Jadu <a title="External website" href="http://www.jadu.net" >Content Management</a>.</p>
	</div>
