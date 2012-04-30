<?php
    include_once("egov/JaduEGovJoinedUpServices.php");
    include_once('JaduConstants.php'); 
?>
<!-- googleoff: index -->
<div id="mobile_name"><?php print encodeHtml(METADATA_GENERIC_NAME); ?></div>

<div id="header">
	<ul id="skip">
		<li><a href="<?php print getSiteRootURL() . encodeHtml($_SERVER['REQUEST_URI']); ?>#content" rel="nofollow">Skip to content</a></li>
		<li><a href="<?php print getSiteRootURL() . encodeHtml($_SERVER['REQUEST_URI']); ?>#column_nav" rel="nofollow">Skip to main navigation</a></li>
	</ul>
	
	<div class="logo">
		<a href="<?php print getSiteRootURL(); ?>"><span><?php print encodeHtml(METADATA_GENERIC_NAME); ?></span></a>
	</div>
	
	<ul>
		<li><a href="<?php print getSiteRootURL() . buildSiteMapURL();?>">Site map</a> </li>
		<li><a href="<?php print getSiteRootURL() . buildUserSettingsURL();?>">Accessibility</a></li>
<?php 
		if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) { 
?> 
		<li><a  href="<?php print buildUserHomeURL(); ?>">Account</a> </li>
<?php 
		} 
		else if (Jadu_Service_User::getInstance()->canRegisterUser()) { 
?> 
		<li><a href="<?php print getSecureSiteRootURL() . buildRegisterURL();?>">Register</a></li>
<?php 
		}
		
		if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) { 
?> 
		<li><a href="<?php print getSecureSiteRootURL() . buildSignOutURL();?>">Sign out</a></li>
<?php 
		} 
		else { 
?> 
		<li><a href="<?php print getSecureSiteRootURL() . buildSignInURL();?>">Sign in</a></li>
<?php 
		} 
?>
		<li><a href="<?php print getSiteRootURL() . buildContactURL();?>">Contact</a></li>
	</ul>
	
	<form action="<?php print getSiteRootURL() . buildSearchResultsURL(); ?>" method="get" id="search">
		<div>
			<label for="SearchSite">Search this site</label>
			<input type="text" size="18" maxlength="40" name="q" id="SearchSite" value="<?php if(isset($htmlSafeQuery)) { print encodeHtml($htmlSafeQuery); } ?>" />
			<input type="submit" value="Search" />
		</div>
		<p><a href="<?php print getSiteRootURL() . buildSearchURL();?>">Advanced search</a></p>
	</form>
	
</div>
<!-- googleon: index -->