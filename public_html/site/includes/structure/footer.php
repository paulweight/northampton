	<!-- googleoff: index -->
	<div id="footer">
	<ul>
		<li><a href="<?php print getSiteRootURL(). encodeHtml($_SERVER['REQUEST_URI']); ?>#header">Back to the top</a></li>
		<li><a href="<?php print getSiteRootURL() . buildTermsURL();?>">Terms &amp; disclaimer</a> </li>
		<li><a href="<?php print getSiteRootURL() . buildFeedbackURL(); ?>">Feedback</a></li>
		<li><a href="<?php print getSiteRootURL() . buildStatisticsURL(); ?>">Statistics</a></li>
<?php
	if ($_SERVER['PHP_SELF'] !== '/site/scripts/pageComments.php') {
?>
		<li><a href="<?php print getSiteRootURL() . buildPageCommentsURL();?>">Comment on this page</a></li>
<?php
}
?>
	</ul>

	<p>All content &copy; <?php print date("Y") . ' ' . encodeHtml(METADATA_GENERIC_NAME); ?>. All Rights Reserved. 
<?php 
	if (basename($_SERVER['SCRIPT_FILENAME']) == 'xforms_form.php' && defined('XFORMS_PROFESSIONAL_VERSION') === true) {
?>
		Powered by Jadu <a href="http://www.jadu.net/xfp">XFP Online Forms</a>.
<?php
	}
	else {
?>
		Powered by Jadu <a href="http://www.jadu.net">Content Management</a>.
<?php
	}
?>
	</p>

	<ul class="hidden">
		<li><a accesskey="1" href="<?php print getSiteRootURL(); ?>">Homepage</a></li>
		<li><a accesskey="2" href="<?php print getSiteRootURL() . buildWhatsNewURL();?>" rel="nofollow">What&#39;s new</a></li>
		<li><a accesskey="3" href="<?php print getSiteRootURL() . buildSiteMapURL();?>" rel="nofollow">Site map</a></li>
		<li><a accesskey="4" href="<?php print getSiteRootURL() . buildSearchURL();?>" rel="nofollow">Search facility </a></li>
		<li><a accesskey="5" href="<?php print getSiteRootURL() . buildFAQURL();?>" rel="nofollow">Frequently asked questions</a></li>
		<li><a accesskey="6" href="<?php print getSiteRootURL() . buildAToZURL();?>" rel="nofollow">Help</a></li>
		<li><a accesskey="7" href="<?php print getSiteRootURL() . buildContactURL();?>" rel="nofollow">Complaints procedure (Contacting the Council page)</a></li>
		<li><a accesskey="8" href="<?php print getSiteRootURL() . buildTermsURL();?>" rel="nofollow">Terms &amp; Privacy</a></li>
		<li><a accesskey="9" href="<?php print getSiteRootURL() . buildFeedbackURL();?>" rel="nofollow">Feedback</a></li>
		<li><a accesskey="0" href="<?php print getSiteRootURL() . buildAccessibilityURL();?>" rel="nofollow">Access key details</a></li>
	</ul>
</div><!-- googleon: index -->
