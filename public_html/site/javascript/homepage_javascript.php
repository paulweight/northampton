<?php
	header('Content-Type:text/javascript');

	include_once('websections/JaduHomepages.php');
	include_once('websections/JaduHomepageWidgetsToHomepages.php');
	include_once('websections/JaduHomepageWidgets.php');
	
	$homepage = getHomepage($_GET['homepageID']);
	
	$widgets = getAllWidgetToHomepagesForHomepage($homepage->id, true);
	
	foreach ($widgets as $widget) {
		$widgetCode = getHomepageWidget($widget->widgetID);
		if (!empty($widgetCode->contentJs)) {
			echo $widgetCode->contentJs,"\n\n// ====================\n\n\n";
		}
	}
?>