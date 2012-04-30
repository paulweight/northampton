<?php
    include_once("egov/JaduEGovJoinedUpServices.php");
    include_once('JaduConstants.php'); 
?>
<!-- googleoff: index -->
<div id="mobile_name"><?php print encodeHtml(METADATA_GENERIC_NAME); ?></div>
<div id="mast">
	<ul id="skip">
		<li><a href="<?php print getSiteRootURL() . encodeHtml($_SERVER['REQUEST_URI']); ?>#content" rel="nofollow">Skip to content</a></li>
		<li><a href="<?php print getSiteRootURL() . encodeHtml($_SERVER['REQUEST_URI']); ?>#column_nav" rel="nofollow">Skip to main navigation</a></li>
	</ul>
	<p class="mast_links">
			<a href="<?php print getSiteRootURL() . buildSiteMapURL();?>">Site map</a> 
			<a href="<?php print getSiteRootURL() . buildUserSettingsURL();?>">Accessibility</a> 
<?php 
		if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) { 
?> 
			<a  href="<?php print buildUserHomeURL(); ?>">Account</a> 
<?php 
		} 
		else if (Jadu_Service_User::getInstance()->canRegisterUser()) { 
?> 
			<a href="<?php print getSecureSiteRootURL() . buildRegisterURL();?>">Register</a>
<?php 
		}
		
		if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) { 
?> 
			<a href="<?php print getSecureSiteRootURL() . buildSignOutURL();?>">Sign out</a> 
<?php 
		} 
		else { 
?> 
			<a href="<?php print getSecureSiteRootURL() . buildSignInURL();?>">Sign in</a>
<?php 
		} 
?>
			<a href="<?php print getSiteRootURL() . buildContactURL();?>">Contact</a> 
	</p>
	
	<form action="<?php print getSiteRootURL() . buildSearchResultsURL(); ?>" method="get" name="search" id="search">
		<a href="<?php print getSiteRootURL() . buildSearchURL();?>">Advanced search</a>
		<label for="SearchSite">Search this site</label>
		<input type="text" size="18" maxlength="40" class="field" name="q" id="SearchSite" value="<?php if(isset($htmlSafeQuery)) { print encodeHtml($htmlSafeQuery); } ?>" /><input type="submit" value="Search" class="button" />
	</form>

	<div class="pseudoH1">
		<a href="<?php print getSiteRootURL(); ?>"><span><?php print encodeHtml(METADATA_GENERIC_NAME); ?></span> <img src="<?php print getStaticContentRootURL();?>/site/images/blank.gif" alt="<?php print encodeHtml(METADATA_GENERIC_NAME);?> logo" /></a>
	</div>
	
	<div class="clear"></div>
</div>
<!-- googleon: index -->