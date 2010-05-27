<?php
	include_once('JaduConstants.php');	

	// supplements
	include_once("websections/JaduPageSupplements.php");
	include_once("websections/JaduPageSupplementWidgets.php");
	include_once("websections/JaduPageSupplementWidgetPublicCode.php");

	if (!isset($preview_mode) || !$preview_mode) {
		header("Location: http://".$DOMAIN."/site/index.php");
		exit;
        }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
	<title><?php print $homepage->title; ?> | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once($HOME . "site/includes/stylesheets.php"); ?>
	<?php include_once($HOME . "site/includes/metadata.php"); ?>

	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY;?>generic/homepages.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY;?>generic/homepageElements.php?homepageID=<?php print $_GET['homepageID']; ?>" media="screen" />

	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - <?php print $homepage->title; ?> - <?php print $homepage->description; ?>" />

	<?php printMetadata(HOMEPAGES_METADATA_TABLE, HOMEPAGE_CATEGORIES_TABLE, $homepage->id, $homepage->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body <?php if ($homepage->hideTaxonomy == 1) { ?>class="full"<?php } ?>>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include($HOME . "site/includes/opening.php"); ?>
<!-- ########################## -->
<?php
	foreach ($sections as $row) {
?>
				<div class="row_divider">
<?php
		foreach ($row as $widget) {
			if (!is_array($widget)) {
				$widgetContent = getHomepageWidget($widget->widgetID);
				$widgetContent = $widgetContent->contentCode;

				$widgetStyle = '';
				if (!empty($widgetSettings) && isset($widgetSettings[$widget->id])) {
					foreach ($widgetSettings[$widget->id] as $setting) {
						$setting->value = str_replace("'", '&#39;', $setting->value);
						if ($setting->name == 'stylesheet') {
							$widgetStyle = $setting->value;
						}
						if (strtolower($setting->name) == 'img_src' && strtolower(substr($setting->value,0,4)) != 'http') {
							$setting->value = 'http://' . $DOMAIN . '/images/' . $setting->value;
						}
						$widgetContent = str_replace('%'.strtoupper($setting->name).'%', $setting->value, $widgetContent);
					}
				}
?>
					<div class="new_widget <?php if(!empty($widgetStyle)) { print $widgetStyle; } else { print 'styleLess'; } ?> width<?php print $widget->widthPercentage; ?>">
<?php 
				eval('?>' . $widgetContent . '<?php '); 
			} 
			else {
?>
					<div class="new_widget width<?php print $widget[0]->widthPercentage; ?>">
<?php
				foreach ($widget as $stack) {
					$widgetContent = getHomepageWidget($stack->widgetID);
					$widgetContent = $widgetContent->contentCode; 

					$widgetStyle = '';
					if (!empty($widgetSettings) && isset($widgetSettings[$stack->id])) {
						foreach ($widgetSettings[$stack->id] as $setting) {
							$setting->value = str_replace("'", '&#39;', $setting->value);
							if ($setting->name == 'stylesheet') {
								$widgetStyle = $setting->value;
							}
							if (strtolower($setting->name) == 'img_src' && strtolower(substr($setting->value,0,4)) != 'http') {
								$setting->value = 'http://' . $DOMAIN . '/images/' . $setting->value;
							}
							$widgetContent = str_replace('%'.strtoupper($setting->name).'%', $setting->value, $widgetContent);
							$settings[] = $setting;
						}
					}
?>
						<div class="stacking <?php if(!empty($widgetStyle)) { print $widgetStyle; } else { print 'styleLess'; } ?>">
<?php
					eval('?>' . $widgetContent . '<?php ');
?>
						</div>						
<?php
				}			
			}
?>
					</div>
<?php
		}
?>
					<br class="clear" />	
				</div>
<?php
	}
?>
		<!-- Bottom Supplements -->
<?php
		// get bottom supplements 
		if (isset($page) || isset($homepage)) {
			if (isset($page)) {
				$bottomSupplements = getAllPageSupplements('', $page->id, '', 'Bottom');
			}
			elseif (isset($homepage)) {
				$bottomSupplements = getAllPageSupplements('', '', $homepage->id, 'Bottom');
			}
			// loop through each supplement
			foreach ($bottomSupplements as $supplement) {
				// include supplement front-end code
				$publicCode = getSupplementPublicCode($supplement->supplementWidgetID, $supplement->locationOnPage);
				$supplementWidget = getPageSupplementWidget($supplement->supplementWidgetID);

				include_once($supplementWidget->classFile);

				$record = new $supplementWidget->className;
				$record->id = $supplement->supplementRecordID;
				$record->get();
				include($HOME . '/site/includes/supplements/' . $publicCode->code);
			}
		}
?>
		<!-- End bottom supplements -->

	<script type="text/javascript" src="/site/javascript/homepage_javascript.php?homepageID=<?php print $homepage->id; ?>"></script>
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include($HOME . "site/includes/closing.php"); ?>
