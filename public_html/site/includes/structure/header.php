<?php
    include_once("egov/JaduEGovJoinedUpServices.php");
    include_once('JaduConstants.php'); 
    include_once("utilities/JaduNavWidgets.php");
    
    $allWidgets = getAllNavWidgets();
	
	$fullWidgets = getAllNavWidgets();
	$apply = array_slice($fullWidgets, 2, 1);
	$pay = array_slice($fullWidgets, 3, 1);
	$report = array_slice($fullWidgets, 4, 1);
	$feedback = array_slice($fullWidgets, 5, 1);
	
	$counter = 0;
	
	$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
	$allRootCategories = $lgclList->getTopLevelCategories();

	$columnRootCategories = filterCategoriesInUseFromMultipleTables($allRootCategories, array(DOCUMENTS_APPLIED_CATEGORIES_TABLE, HOMEPAGE_APPLIED_CATEGORIES_TABLE), true);
    
?>
<!-- googleoff: index -->
<div id="mobile_name"><?php print encodeHtml(METADATA_GENERIC_NAME); ?></div>

<div id="mast" class="red box-shadow">

	<span class="h1"><a href="<?php print getSiteRootURL(); ?>"><span><?php print encodeHtml(METADATA_GENERIC_NAME); ?></span></a></span>
	
	<ul id="mast-nav">
		<li class="font-size"><a href="<?php print getSiteRootURL() . buildUserSettingsURL();?>"><span>Accessibility</span></a></li>
		<li><a href="#">Other languages</a></li>
		<li class="last"><a href="<?php print getSiteRootURL() . buildContactURL();?>">Contact us</a></li>
	</ul>
	
	<ul id="skip" class="hidden">
		<li><a href="<?php print getSiteRootURL() . encodeHtml($_SERVER['REQUEST_URI']); ?>#content" rel="nofollow">Skip to content</a></li>
		<li><a href="<?php print getSiteRootURL() . encodeHtml($_SERVER['REQUEST_URI']); ?>#column_nav" rel="nofollow">Skip to main navigation</a></li>
	</ul>
		
	<form action="<?php print getSiteRootURL() . buildSearchResultsURL(); ?>" method="get" id="search">
		<p><label for="SearchSite">Search for it...</label></p>
		<div id="search-site">
			<input type="text" size="18" maxlength="40" name="q" class="field" value="<?php if(isset($htmlSafeQuery)) { print encodeHtml($htmlSafeQuery); } ?>" />
			<input type="submit" class="button" value=" " />
		</div>
	</form>
	
	<div class="clear"></div>
	
</div>
<ul id="main-nav-top" class="grey box-shadow">
				<li class="home" ><a href="<?php print getSiteRootURL(); ?>" ><span>Home</span></a></li>
				<li class="apply"><a class="show_hide" onclick="return false;" href="javascript:void(0);"><span>Apply for it</span></a></li>
				<li class="pay"><a class="show_hide" onclick="return false;" href="javascript:void(0);"><span>Pay for it</span></a></li>
				<li class="report"><a class="show_hide" onclick="return false;" href="javascript:void(0);"><span>Report it</span></a></li>
				<li class="feedback"><a class="show_hide" onclick="return false;" href="javascript:void(0);"><span>Feedback</span></a></li>
			</ul>
			<ul id="main-nav-dropdown">
			<li>
				<ul id="apply">
					<?php
							if (sizeof($apply) > 0) {
								foreach ($apply as $widget) {
									$allLinks = getAllNavWidgetLinksInNavWidget ($widget->id);
					?>
						
							<?php
										foreach ($allLinks as $widgetLink) {
											print '<li><a href="' . encodeHtml($widgetLink->link) . '">' . encodeHtml($widgetLink->title) . '</a></li>';
										}
							?>
						
					<?php
								}
							}
					?>
				</ul>
				</li>
				<li>
				<ul id="pay">
					<?php
							if (sizeof($pay) > 0) {
								foreach ($pay as $widget) {
									$allLinks = getAllNavWidgetLinksInNavWidget ($widget->id);
					?>
						
							<?php
										foreach ($allLinks as $widgetLink) {
											print '<li><a href="' . encodeHtml($widgetLink->link) . '">' . encodeHtml($widgetLink->title) . '</a></li>';
										}
							?>
						
					<?php
								}
							}
					?>
				</ul>
				</li>
				<li>
				<ul id="report">
					<?php
							if (sizeof($report) > 0) {
								foreach ($report as $widget) {
									$allLinks = getAllNavWidgetLinksInNavWidget ($widget->id);
					?>
						
							<?php
										foreach ($allLinks as $widgetLink) {
											print '<li><a href="' . encodeHtml($widgetLink->link) . '">' . encodeHtml($widgetLink->title) . '</a></li>';
										}
							?>
						
					<?php
								}
							}
					?>

				</ul>
				</li>
				<li>
				<ul id="feedback">
					<?php
							if (sizeof($feedback) > 0) {
								foreach ($feedback as $widget) {
									$allLinks = getAllNavWidgetLinksInNavWidget ($widget->id);
					?>
						
							<?php
										foreach ($allLinks as $widgetLink) {
											print '<li><a href="' . encodeHtml($widgetLink->link) . '">' . encodeHtml($widgetLink->title) . '</a></li>';
										}
							?>
						
					<?php
								}
							}
					?>
				</ul>
				</li>
			</ul>
			<div class="clear"></div>
<!-- googleon: index -->