<?php
	include_once('egov/JaduEGovJoinedUpServices.php');
	include_once('egov/JaduEGovJoinedUpServicesContacts.php');
	include_once("egov/JaduXFormsForm.php");

	$searchText = trim($_REQUEST['searchText']);
	$serviceFound = false;
	
	if (!empty($searchText)) {
		$services = getAllServicesWithTitleAliases();
	
		$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
?>
		<ul class="list">
<?php
		foreach ($services as $service) {
			if (strpos(strtolower($service->title), strtolower($searchText)) !== false) {

				$allLGCLCategories = array();				
				$allCategories = getAllCategoriesOfType(SERVICES_CATEGORIES_TABLE, $service->id);
				foreach ($allCategories as $category) {
					$allLGCLCategories[] = $lgclList->getCategory($category->categoryID);
				}

				$serviceFound = true;

				$formsWithCategory = array();
				foreach ($allLGCLCategories as $lgclCategory) {
					$formsWithCategory = array_merge($formsWithCategory, getAllCategoryItemsOfType(XFORMS_FORM_CATEGORIES_TABLE, $lgclCategory->id, BESPOKE_CATEGORY_LIST_NAME));
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

				$serviceToContacts = getAllServicesToContactsForService($service->id);
				
?>
				<li>
					<a href="http://<?php print $DOMAIN;?>/site/scripts/services_info.php?serviceID=<?php print $service->id;?>" title="<?php print $service->title;?>"><?php print stripslashes($service->title);?></a> 
<?php
				$service->title = str_replace("'", '', stripslashes($service->title));
				if (sizeof($serviceToContacts) > 0) {
?>
					<img id="img_<?php print str_replace(' ', '_', $service->title); ?>" alt=" + " src="http://<?php print $DOMAIN;?>/site/images/bllt_plus.gif" onclick="showFurtherInfo('<?php print str_replace(' ', '_', addslashes($service->title)); ?>');" />
<?php
					foreach ($serviceToContacts as $sToC) {
						$contact = getServiceContact($sToC->contactID);
?>
						<ul id="service_info_<?php print str_replace(' ', '_', $service->title); ?>" style="display:none;" >
<?php
						if (!empty($contact->name)) {
?>
							<li>Contact: <strong><?php print stripslashes($contact->name); ?></strong></li>
<?php
						}
						if (!empty($contact->email)) {
?>
							<li>Email: <a href="mailto:<?php print $contact->email; ?>"><?php print $contact->email; ?></a></li>
<?php
						}

						if (!empty($contact->telephone)) {
?>
							<li>Tel: <?php print $contact->telephone; ?></li>
<?php
						}
?>
						</ul>
<?php
					}
					
					if (sizeof($forms) > 0) {
?>
						<ul id="related_forms_<?php print str_replace(' ', '_', $service->title); ?>" style="display:none;">
							<li><strong>Related online forms</strong></li>
<?php
						foreach ($forms as $form) {
?>
							<li><a href="http://<?php print DOMAIN; ?>/site/scripts/xforms_form.php?formID=<?php print $form->id; ?>"><?php print stripslashes($form->title); ?></a></li>
<?php
						}
?>
						</ul>
<?php
					}

					if (!empty($contact->addressPostcode)) {
?>
						<ul class="map_icon" id="service_map_<?php print str_replace(' ', '_', $service->title); ?>" style="display:none;">
							<li><strong><a href="#" id="toggle_map_link_<?php print str_replace(' ', '_', $service->title); ?>" onclick="createMapForPostcode('<?php print $contact->addressPostcode; ?>', '<?php print str_replace(' ', '_', $service->title); ?>', createMap); return false;">Show Map</a></strong></li>
						</ul>
<?php
					}
				}
?>
					<div class="google_map" id="map_<?php print str_replace(' ', '_', $service->title); ?>" style="background: #fff; padding: 1px; border: 1px solid #e5e5e5; width: 320px; height: 300px; display:none;">
						<img id="loading" src="http://<?php print $DOMAIN;?>/site/images/loading.gif" /> Loading map
					</div>
				</li>
<?php
			}
		}
		if (!$serviceFound) {
?>
			<li>No results for <?php print $searchText; ?></li>
<?php
		}
?>
		</ul>
<?php
	}
	
	print '|' . $_REQUEST['seq'];
?>