<?php
	include_once('JaduConstants.php');
	include_once('utilities/JaduNavWidgets.php');
	
	$allWidgets = getAllNavWidgets();
	$apply = array_slice($allWidgets, 2, 1);
	$pay = array_slice($allWidgets, 3, 1);
	$report = array_slice($allWidgets, 4, 1);
	$feedback = array_slice($allWidgets, 5, 1);
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
		<p><label for="SearchSite">Search for it...</label></p>
		<div>
			<input type="hidden" name="pckid" value="1610317951" autocomplete="off">
			<input type="hidden" name="aid" value="471434" autocomplete="off">
		</div>
		<div id="search-site">
			<input id="search-site-query" type="text" size="18" maxlength="255" name="sw" class="field" value="<?php print isset($htmlSafeQuery) ? encodeHtml($htmlSafeQuery) : ''; ?>" autocomplete="off" />
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
	if (!empty($apply)) {
		foreach ($apply as &$widget) {
			$allLinks = getAllNavWidgetLinksInNavWidget($widget->id);
			foreach ($allLinks as &$widgetLink) {
?>
						<li><a href="<?php print encodeHtml($widgetLink->link); ?>"><?php print encodeHtml($widgetLink->title); ?></a></li>
<?php
			}
		}
	}
?>
					</ul>
				</li>
				<li>
					<ul id="pay">
<?php
	if (!empty($pay)) {
		foreach ($pay as &$widget) {
			$allLinks = getAllNavWidgetLinksInNavWidget($widget->id);
			foreach ($allLinks as &$widgetLink) {
?>
						<li><a href="<?php print encodeHtml($widgetLink->link); ?>"><?php print encodeHtml($widgetLink->title); ?></a></li>
<?php
			}
		}
	}
?>
					</ul>
				</li>
				<li>
					<ul id="report">
<?php
	if (!empty($report)) {
		foreach ($report as &$widget) {
			$allLinks = getAllNavWidgetLinksInNavWidget($widget->id);
			foreach ($allLinks as &$widgetLink) {
?>
						<li><a href="<?php print encodeHtml($widgetLink->link); ?>"><?php print encodeHtml($widgetLink->title); ?></a></li>
<?php
			}
		}
	}
?>
					</ul>
				</li>
				<li>
					<ul id="feedback">
<?php
	if (!empty($feedback)) {
		foreach ($feedback as &$widget) {
			$allLinks = getAllNavWidgetLinksInNavWidget($widget->id);
			foreach ($allLinks as &$widgetLink) {
?>
						<li><a href="<?php print encodeHtml($widgetLink->link); ?>"><?php print encodeHtml($widgetLink->title); ?></a></li>
<?php
			}
		}
	}
?>
					</ul>
				</li>
			</ul>
			<div class="clear"></div>
<!-- googleon: index -->