<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("egov/JaduCL.php");

	//	old homepages
	include_once("websections/JaduDocuments.php");
	include_once("websections/JaduHomepageCategoryDefaults.php");
	
	// for homepages
	include_once("websections/JaduHomepages.php");	
	include_once("websections/JaduHomepageWidgetsToHomepages.php");		
	include_once("websections/JaduHomepageWidgets.php");		
	include_once("websections/JaduHomepageWidgetSettings.php");		
	
	// supplements
	include_once("websections/JaduPageSupplements.php");
	include_once("websections/JaduPageSupplementWidgets.php");
	include_once("websections/JaduPageSupplementWidgetPublicCode.php");

	include("../includes/lib.php");

	$showHomepageContent = false;
	$dirTree = array();
	
	if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
		
		$defaultCategoryHomepage = getHomepageCategoryDefaultForCategory($_GET['categoryID']);
		if ($defaultCategoryHomepage->homepageID != -1) {
			$homepage = getHomepage($defaultCategoryHomepage->homepageID, true);
			if ($homepage != -1) {
				$showHomepageContent = true;			

				$allHomepageContent = getAllWidgetToHomepagesForHomepage($homepage->id, true);
				$homepageSections = array();
				foreach($allHomepageContent as $content) {
					if (!isset($homepageSections[$content->positionY])) {
						$homepageSections[$content->positionY] = array();
					}
					if ($content->stackPosition > 0) {
						if (!isset($homepageSections[$content->positionY][$content->positionX])) {
							$homepageSections[$content->positionY][$content->positionX] = array();
						}
						$homepageSections[$content->positionY][$content->positionX][] = $content;
					}
					else {
						$homepageSections[$content->positionY][] = $content;
					}
				}
			}
		}
		
		//	Document Links
		$allDocuments = getAllDocumentsWithCategory($_GET['categoryID'], true, true, 'title');
		$liveDocs = array();
		
		foreach ($allDocuments as $document) {
			//$document->header = getDocumentHeader($document->headerID);
			$header = new Versions(DOCUMENT_HEADERS_TABLE, $document->headerOriginalID, VERSIONED_DOCUMENTS_TABLE);
			if ($header->liveVersion != -1) {
				$liveDocs[] = $header->getLiveVersion();
			}
			
		}
		$splitDocs = splitArray($liveDocs);
		
		//	Category Links
		$bespokeCategoryList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
		$allCategories = $bespokeCategoryList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, DOCUMENTS_APPLIED_CATEGORIES_TABLE, true);
		$splitArray = splitArray($categories);

		//	Category Links
		$currentCategory = $bespokeCategoryList->getCategory($_GET['categoryID']);
		$dirTree = $bespokeCategoryList->getFullPath($_GET['categoryID']);
		$leftCategoryID = $_GET['categoryID'];
	}
	else {
		header("Location: documents_index.php");
		exit;
	}
	
	$breadcrumb = 'documentsCat';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<title><?php print $currentCategory->name; ?> | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	
	<?php include_once( $HOME . "site/includes/stylesheets.php"); ?>
	<?php include_once( $HOME . "site/includes/metadata.php"); ?>

	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY;?>generic/homepages.css" media="screen" />	
	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY;?>generic/homepageElements.php?homepageID=<?php print $homepage->id; ?>" media="screen" />

	<meta name="Keywords" content="<?php foreach ($dirTree as $parent) { print $parent->name . ', '; } ?><?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of documents and pages organised within the following categories<?php foreach ($dirTree as $parent) { print ', ' . $parent->name; } ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> online information<?php foreach ($dirTree as $parent) { ?> | <?php print $parent->name; ?><? } ?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of documents and pages organised within the following categories<?php foreach ($dirTree as $parent) { print ', ' . $parent->name; } ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy;<?php foreach ($dirTree as $parent) { print $parent->name . ';'; } ?>" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include( $HOME . "site/includes/opening.php"); ?>
<!-- ########################## -->
		
<?php
	if ($showHomepageContent) {
	foreach ($homepageSections as $row) {
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
					<div class="new_widget <?php if(!empty($widgetStyle)) { print $widgetStyle; } else { print styleLess; } ?> width<?php print $widget->widthPercentage; ?>">
					<div class="widgetPadding">
<?php 
				eval('?>' . $widgetContent . '<?php '); 
			} 
			else {
?>
					<div class="new_widget width<?php print $widget[0]->widthPercentage; ?>">
					<div class="widgetPadding">
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
						<div class="stacking <?php if(!empty($widgetStyle)) { print $widgetStyle; } else { print styleLess; } ?>">
<?php
					eval('?>' . $widgetContent . '<?php ');
?>
						</div>						
<?php
				}			
			}
?>
					</div>
					</div>
<?php
		}
?>
					<div class="clear"></div>
				</div>
<?php
	}
	}
?>
		<!-- END category homepage content -->
		
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


		<!-- Information on.. -->
<?php
	if (sizeof($splitDocs['left']) > 0 || sizeof($splitDocs['right']) > 0) {
?>
		<div class="doc_info">
			<h2>Information on <?php print $parent->name; ?></h2>

<?php
		if (sizeof($splitDocs['left']) > 0 ) {
			print '<ul class="info_left list">';
			foreach ($splitDocs['left'] as $document) {					
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/documents_info.php?categoryID=<?php print $_GET['categoryID']; ?>&amp;documentID=<?php print $document->id; ?>"><?php print $document->title; ?></a></li>
<?php
			}
			print '</ul>';
		}
		
		if (sizeof($splitDocs['right']) > 0) {
			print '<ul class="info_right list">';
			foreach ($splitDocs['right'] as $document) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/documents_info.php?categoryID=<?php print $_GET['categoryID']; ?>&amp;documentID=<?php print $document->id; ?>"><?php print $document->title; ?></a></li>
<?php
			}
			print '</ul>';
		}
?>
			<div class="clear"></div>
		</div>
<?php
	}
?>

		
		<!-- Categories -->
<?php
	if (sizeof($splitArray['left']) > 0 || sizeof ($splitArray['right']) > 0) {
?>
		<div class="cate_info">
			<h2>Find out more about <?php print $parent->name; ?></h2>
<?php
		if (sizeof($splitArray['left']) > 0) {
			print '<ul class="info_left list">';
			foreach ($splitArray['left'] as $subCat) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/documents.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a></li>
<?php
			}
			print '</ul>';
		}
	
		if (sizeof($splitArray['right']) > 0) {
			print '<ul class="info_right list">';
			foreach ($splitArray['right'] as $subCat) {
?>
				<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/documents.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a></li>
<?php
			}
			print '</ul>';
		}
?>		
			<div class="clear"></div>
		</div>
<?php
	}
?>
		
	<!-- The Contact box -->
	<?php include( $HOME . "site/includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
	
<?php
	if (isset($homepage)) {
?>
	<script type="text/javascript" src="/site/javascript/homepage_javascript.php?homepageID=<?php print $homepage->id; ?>"></script>
<?php
	}
?>
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include( $HOME . "site/includes/closing.php"); ?>
