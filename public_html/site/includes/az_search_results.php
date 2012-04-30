<?php
	include_once("utilities/JaduStatus.php");
	include_once("utilities/JaduReadableURLs.php");
	include_once('egov/JaduEGovJoinedUpServices.php');
	include_once('egov/JaduEGovJoinedUpServicesContacts.php');
	include_once("egov/JaduXFormsForm.php");

	$searchText = trim($_REQUEST['searchText']);
	$serviceFound = false;
	
	if (!empty($searchText)) {
		$services = getAllServicesWithTitleAliases(true, true);
	
		$lgclList = getLiveCategoryList(BESPOKE_CATEGORY_LIST_NAME);
?>
		<ul class="list">
<?php
		foreach ($services as $service) {

			if (mb_strpos(mb_strtolower($service->title), mb_strtolower($searchText)) !== false) {

				$allLGCLCategories = array();
				$allCategories = getAllCategoriesOfType(SERVICES_CATEGORIES_TABLE, $service->id);
				foreach ($allCategories as $category) {
					$allLGCLCategories[] = $lgclList->getCategory($category->categoryID);
				}

				$serviceFound = true;

				$formsWithCategory = array();
				foreach ($allLGCLCategories as $lgclCategory) {
					if ($lgclCategory !== null) {
						$formsWithCategory = array_merge($formsWithCategory, getAllCategoryItemsOfType(XFORMS_FORM_CATEGORIES_TABLE, $lgclCategory->id, BESPOKE_CATEGORY_LIST_NAME));
					}
				}

				$forms = array();
				if (sizeof($formsWithCategory) > 0) {
					foreach ($formsWithCategory as $formWithCategory) {
						$form = getXFormsForm($formWithCategory->itemID, true);
						if ($form != -1) {
							$forms[] = $form;
						}
					}
				}
				
?>
				<li>
					<a href="<?php print 'http://' . DOMAIN . buildAZServiceURL($service->id); ?>"><?php print encodeHtml($service->title); ?></a>
<?php
			$serviceContacts = $service->getContacts();
			if (sizeof($serviceContacts) > 0 || sizeof($forms) > 0) {
?>
					<img id="img_<?php print str_replace(' ', '_', encodeHtml($service->title)); ?>" alt=" + " src="<?php print getStaticContentRootURL(); ?>/site/images/bllt_plus.gif" onclick="showFurtherInfo('<?php print str_replace(' ', '_', encodeHtml($service->title)); ?>');" />
<?php
				if (sizeof($forms) > 0) {
?>
					<ul id="related_forms_<?php print str_replace(' ', '_', encodeHtml($service->title)); ?>" style="display:none;">
						<li><strong>Related online forms</strong></li>
<?php
					foreach ($forms as $form) {
?>
						<li><a href="<?php print getSiteRootURL().buildXFormsURL($form->id); ?>"><?php print encodeHtml($form->title); ?></a></li>
<?php
					}
?>
					</ul>
<?php
				}
				
				if (sizeof($serviceContacts) > 0) {
					foreach ($serviceContacts as $contact) {
?>
						<ul id="service_info_<?php print str_replace(' ', '_', encodeHtml($service->title)); ?>" style="display:none;" >
<?php
						if (!empty($contact->name)) {
?>
							<li>Contact: <strong><?php print encodeHtml($contact->name); ?></strong></li>
<?php
						}
						if (!empty($contact->email)) {
?>
							<li>Email: <a href="mailto:<?php print encodeHtml($contact->email); ?>"><?php print encodeHtml($contact->email); ?></a></li>
<?php
						}

						if (!empty($contact->telephone)) {
?>
							<li>Tel: <?php print encodeHtml($contact->telephone); ?></li>
<?php
						}
?>
						</ul>
<?php
					}

					if (!empty($contact->addressPostcode)) {
?>
						<ul class="map_icon" id="service_map_<?php print str_replace(' ', '_', encodeHtml($service->title)); ?>" style="display:none;">
							<li><strong><a href="-" id="toggle_map_link_<?php print str_replace(' ', '_', encodeHtml($service->title)); ?>" onclick="createMapForPostcode('<?php print encodeHtml($contact->addressPostcode); ?>', '<?php print str_replace(' ', '_', encodeHtml($service->title)); ?>', createMap); return false;">Show Map</a></strong></li>
						</ul>
<?php
					}
?>
					<div class="google_map" id="map_<?php print str_replace(' ', '_', encodeHtml($service->title)); ?>" style="background: #fff; padding: 1px; border: 1px solid #ddd; width: 98%; height: 300px; display:none;">
						<img id="loading" src="<?php print getStaticContentRootURL(); ?>/site/images/loading.gif" /> Loading map
					</div>
<?php
				}
			}
?>
				</li>
<?php
			}
		}
		if (!$serviceFound) {
?>
			<li>No results for <?php print encodeHtml($searchText); ?></li>
<?php
		}
?>
		</ul>
<?php
	}
?>