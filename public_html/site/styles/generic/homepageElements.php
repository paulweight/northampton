<?php
	include_once('websections/JaduHomepages.php');
	include_once('websections/JaduHomepageWidgetStyles.php');
	include_once('utilities/JaduSeasoning.php');

	$homepage = getHomepage($_GET['homepageID']);

	$widgetStyles = getAllHomepageWidgetStylesForSeason($homepage->stylesheet);

	header('Content-Type:text/css');

	foreach ($widgetStyles as $widgetStyle) {
		print str_replace('%WIDGET%', 'div.styles'.$widgetStyle->id, $widgetStyle->description);
	}
			
?>
