	<!-- googleoff: index -->
	<div class="clear"></div>
	<div id="footer" class="box-shadow">
		<ul id="smallprint">
			<li>&copy; <?php print date("Y") . ' ' . encodeHtml(METADATA_GENERIC_NAME); ?> </li>
			<li>Powered by Jadu <a href="https://www.jadu.net" title="Jadu Content Management">Content Management.</a></li>
		</ul>
		<div id="social">
			<h4>Follow us on&hellip;</h4>
			<ul id="networks">
				<li><a href="<?php print getSiteRootURL(); ?>/app"><img src="<?php print getSiteRootURL(); ?>/site/images/app_store.png" alt="Available on the App Store" title="iPhone App" /> </a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/facebook"><img height="32" width="32" src="<?php print getStaticContentRootURL(); ?>/site/styles/css_img/social-facebook.png" alt="Facebook" title="Facebook" /> <span>Facebook</span></a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/twitter"><img height="32" width="32" src="<?php print getStaticContentRootURL(); ?>/site/styles/css_img/social-twitter.png" alt="Twitter" title="Twitter" /> <span>Twitter</span></a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/youtube"><img height="32" width="32" src="<?php print getStaticContentRootURL(); ?>/site/styles/css_img/social-youtube.png" alt="YouTube" title="YouTube" /> <span>YouTube</span></a></li>
				<li><a href="<?php print getSiteRootURL(); ?>/rss"><img height="32" width="32" src="<?php print getStaticContentRootURL(); ?>/site/styles/css_img/social-rss.png" alt="RSS" title="RSS" /> <span>RSS</span></a></li>
			</ul>
		</div>
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
		<div class="clear"></div>
	</div>
	<!-- googleon: index -->
