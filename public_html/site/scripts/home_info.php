<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("egov/JaduCL.php");
	
	// for homepages
	include_once("websections/JaduHomepages.php");	
	include_once("websections/JaduHomepageWidgetsToHomepages.php");		
	include_once("websections/JaduHomepageWidgets.php");		
	include_once("websections/JaduHomepageWidgetSettings.php");		

	// supplements
	include_once("websections/JaduPageSupplements.php");
	include_once("websections/JaduPageSupplementWidgets.php");
	include_once("websections/JaduPageSupplementWidgetPublicCode.php");

	if (!isset($_GET['homepageID']) && !is_numeric($_GET['homepageID'])) {
		header('Location:../index.php');
	}

	$homepage = getHomepage($_GET['homepageID'], true);
	
	// get first category for homepage
	$categories = getAllCategories(HOMEPAGE_CATEGORIES_TABLE, $homepage->id);
	foreach($categories as $category) {

		if ($category->categoryType == BESPOKE_CATEGORY_LIST_NAME) {
			$_GET['categoryID'] = $category->categoryID;
			break;
		}
	}

	if ($homepage->stylesheet != '' && $STYLESHEET != 'snippets/handheld.css') {
		$STYLESHEET = $homepage->stylesheet;
	}
	
	$allHomepageContent = getAllWidgetToHomepagesForHomepage($homepage->id, true);
	
	$sections = array();
	foreach($allHomepageContent as $content) {
		if (!isset($sections[$content->positionY])) {
			$sections[$content->positionY] = array();
		}
		if ($content->stackPosition > 0) {
			if (!isset($sections[$content->positionY][$content->positionX])) {
				$sections[$content->positionY][$content->positionX] = array();
			}
			$sections[$content->positionY][$content->positionX][] = $content;
		}
		else {
			$sections[$content->positionY][] = $content;
		}
	}

	$breadcrumb = 'homeInfo';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
	<title><?php print $homepage->title; ?> | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY;?>generic/homepages.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY;?>generic/homepageElements.php?homepageID=<?php print $_GET['homepageID']; ?>" media="screen" />

	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - <?php print $homepage->title; ?> - <?php print $homepage->description; ?>" />

	<?php printMetadata(HOMEPAGES_METADATA_TABLE, HOMEPAGE_CATEGORIES_TABLE, $homepage->id, $homepage->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']); ?>
	

	<script type="text/javascript" src="http://<?php print $DOMAIN?>/site/javascript/prototype.js"></script>
	<script type="text/javascript" src="http://<?php print $DOMAIN?>/site/javascript/scriptaculous.js"></script>
	<script type="text/javascript" src="http://<?php print $DOMAIN?>/site/javascript/effects.js"></script>
	<script type="text/javascript" src="http://<?php print $DOMAIN?>/site/javascript/carousel.js"></script>
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body <?php if ($homepage->hideTaxonomy == 1) { ?>class="full"<?php } ?>>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
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
				$settings = getAllSettingsForHomepageWidget($widget->id, true);

				$widgetStyle = '';
				if (!empty($settings)) {
					foreach ($settings as $setting) {
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
					$settings = getAllSettingsForHomepageWidget($stack->id, true);

					$widgetStyle = '';
					if (!empty($settings)) {
						foreach ($settings as $setting) {
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
					<div class="clear"></div>	
				</div>
<?php
	}
	
	if (isset($_GET['categoryID']) && $_GET['categoryID'] != '') {
?>

	<!-- Related information -->
	<?php 
	//include( $HOME . "site/includes/related_info.php"); 
	?>
	<!-- The Contact box -->
	<?php 
	//include( $HOME . "site/includes/contactbox.php"); 
	?>
	<!-- END of the Contact box -->
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

		<script type="text/javascript" src="/site/javascript/homepage_javascript.php?homepageID=<?php print intval($_GET['homepageID']); ?>"></script>	


<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
