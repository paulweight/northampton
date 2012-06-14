<?php
	include_once('JaduConstants.php');
	include_once('utilities/JaduNavWidgets.php');
	
	$allWidgets = getAllNavWidgets();
?>
<!-- googleoff: index -->
<div id="mobile_name"><?php print encodeHtml(METADATA_GENERIC_NAME); ?></div>

<div id="mast" class="red box-shadow">

	<span class="h1"><a href="<?php print getSiteRootURL(); ?>"><span><?php print encodeHtml(METADATA_GENERIC_NAME); ?></span></a></span>
	
	<ul id="mast-nav">
		<li class="font-size"><a href="<?php print getSiteRootURL() . buildUserSettingsURL();?>"><span>Accessibility</span></a></li>
		<li><a href="<?php print getSiteRootURL()?>/languages">Other languages</a></li>
		<li><a href="<?php print getSiteRootURL()?>/disclaimer">Disclaimer</a></li>
		<li class="last"><a href="<?php print getSiteRootURL() . buildContactURL();?>">Contact us</a></li>
	</ul>
	
	<ul id="skip" class="hidden">
		<li><a href="<?php print getSiteRootURL() . encodeHtml($_SERVER['REQUEST_URI']); ?>#content" rel="nofollow">Skip to content</a></li>
		<li><a href="<?php print getSiteRootURL() . encodeHtml($_SERVER['REQUEST_URI']); ?>#column_nav" rel="nofollow">Skip to main navigation</a></li>
	</ul>
	
	<form action="<?php print getSiteRootURL() . '/improve_search'; ?>" method="get" id="search">
		<p><label for="search-site-query">Search for it...</label></p>
		<div>
			<input type="hidden" name="pckid" value="1610317951">
			<input type="hidden" name="aid" value="471434">
		</div>
		<div id="search-site">
			<input id="search-site-query" type="text" size="18" maxlength="255" name="sw" class="field" value="<?php print isset($htmlSafeQuery) ? encodeHtml($htmlSafeQuery) : ''; ?>" />
			<input id="search-site-submit" type="submit" class="button" value=" " />
		</div>
	</form>
	
	<div class="clear"></div>
	
</div>
			<ul id="main-nav-top" class="grey box-shadow">
				<li class="home" ><a href="<?php print getSiteRootURL(); ?>" ><span>Home</span></a></li>
				<li class="apply"><a class="show_hide" href="<?php print getSiteRootURL(); ?>/apply"><span>Apply for it</span></a></li>
				<li class="pay"><a class="show_hide" href="<?php print getSiteRootURL(); ?>/pay"><span>Pay for it</span></a></li>
				<li class="report"><a class="show_hide" href="<?php print getSiteRootURL(); ?>/report"><span>Report it</span></a></li>
				<li class="feedback"><a class="show_hide" href="<?php print getSiteRootURL(); ?>/feedback"><span>Feedback</span></a></li>
			</ul>
			<ul id="main-nav-dropdown">
				<li>
					<ul id="apply">
<?php
	if (isset($allWidgets[2])) {
		$allLinks = getAllNavWidgetLinksInNavWidget($allWidgets[2]->id);
		foreach ($allLinks as &$widgetLink) {
?>
						<li><a href="<?php print encodeHtml($widgetLink->link); ?>"><?php print encodeHtml($widgetLink->title); ?></a></li>
<?php
		}
	}
?>
					</ul>
				</li>
				<li>
					<ul id="pay">
<?php
	if (isset($allWidgets[3])) {
		$allLinks = getAllNavWidgetLinksInNavWidget($allWidgets[3]->id);
		foreach ($allLinks as &$widgetLink) {
?>
						<li><a href="<?php print encodeHtml($widgetLink->link); ?>"><?php print encodeHtml($widgetLink->title); ?></a></li>
<?php
		}
	}
?>
					</ul>
				</li>
				<li>
					<ul id="report">
<?php
	if (isset($allWidgets[4])) {
		$allLinks = getAllNavWidgetLinksInNavWidget($allWidgets[4]->id);
		foreach ($allLinks as &$widgetLink) {
?>
						<li><a href="<?php print encodeHtml($widgetLink->link); ?>"><?php print encodeHtml($widgetLink->title); ?></a></li>
<?php
		}
	}
?>
					</ul>
				</li>
				<li>
					<ul id="feedback">
<?php
	if (isset($allWidgets[5])) {
		$allLinks = getAllNavWidgetLinksInNavWidget($allWidgets[5]->id);
		foreach ($allLinks as &$widgetLink) {
?>
						<li><a href="<?php print encodeHtml($widgetLink->link); ?>"><?php print encodeHtml($widgetLink->title); ?></a></li>
<?php
		}
	}
?>
					</ul>
				</li>
			</ul>
			<div class="clear"></div>
<!-- googleon: index -->