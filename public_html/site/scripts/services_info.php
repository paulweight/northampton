<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");

	include_once("egov/JaduEGovJoinedUpServices.php");
	include_once("egov/JaduEGovJoinedUpServicesContacts.php");
	include_once("egov/JaduPIDList.php");

	include_once("JaduCategories.php");

	include_once("websections/JaduFAQ.php");
	include_once("websections/JaduDocuments.php");
	include_once("egov/JaduXFormsForm.php");

	include_once("utilities/JaduMostPopular.php");

	$PID = null;
	$allLGCLCategories = array();

	if (isset($_GET['serviceID']) && is_numeric($_GET['serviceID'])) {
		$service = getService($_GET['serviceID']);
		if ($service->id != -1 && $service->id != "") {
			$lgclList = new CategoryList(BESPOKE_CATEGORY_LIST_NAME, BESPOKE_CATEGORY_LIST_FILE);
			$allCategories = getAllCategoriesOfType(SERVICES_CATEGORIES_TABLE, $service->id, BESPOKE_CATEGORY_LIST_NAME);
			foreach ($allCategories as $category) {
				$allLGCLCategories[] = $lgclList->getCategory($category->categoryID);
			}

			$serviceToContacts = getAllServicesToContactsForService($_GET['serviceID']);
			if ($service->PID_ID > 0) {
				$PID = getPIDListElement($service->PID_ID);
			}
		}
		else {
			header("HTTP/1.0 404 Not Found"); 
		}
	}
	else {
		header("Location: az_home.php");
		exit;
	}

	$allServices = getAllServicesWithTitleAliases();
	$validLetters = getAllValidAlphabetLetters($allServices);

	$title = $service->title;
	if (strlen($title) > 64) {
		$title = substr($title, 0, 61) . "...";
	}

	// most popular
	if (strpos($_SERVER['HTTP_REFERER'], 'google_results.php') !== false && isset($_GET['serviceID'])) {

		$url = '/site/scripts/services_info.php?serviceID=' . $_GET['serviceID'];

		$mostPopularItem = getMostPopularItem ('url', $url);

		if ($mostPopularItem->id != -1) {
			$mostPopularItem->hits++;
			updateMostPopularItem($mostPopularItem);
		}
		else {
			$mostPopularItem->hits = 1;
			$mostPopularItem->url = $url;
			$mostPopularItem->title = $service->title;

			newMostPopularItem($mostPopularItem);
		}
	}

	$breadcrumb = 'servicesInfo';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $title; ?> | Council services | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print $service->title;?>, services, a-z, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print $service->title;?> - <?php print METADATA_GENERIC_COUNCIL_NAME;?> A to Z of services" />
<?php
	list($metadata, $gclString, $lgclString, $gclNonPreferred) = getAllMetadata(SERVICES_METADATA_TABLE, SERVICES_CATEGORIES_TABLE, $_GET['serviceID']);
	$meta = new JaduMetadata();

	if ($gclString != '' && $lgclString != '' && $meta != $metadata) {
		printMetadata(SERVICES_METADATA_TABLE, SERVICES_CATEGORIES_TABLE, $_GET['serviceID'], $service->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
	}
	else {
?>
	<meta name="DC.title" lang="en" content="<?php print $service->title;?> - <?php print METADATA_GENERIC_COUNCIL_NAME;?> A to Z of services" />
	<meta name="DC.description" lang="en" content="<?php print $service->title;?> - <?php print METADATA_GENERIC_COUNCIL_NAME;?> A to Z of services" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
<?php
	}
	if ($service->PID_ID > 0) {
?>
	<meta name="eGMS.subject.service" lang="en" scheme="LGSL" content="<?php print $PID->PIDName;?>" />
<?php
	}
?>
	<script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/prototype.js"></script>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php print GOOGLE_MAPS_API_KEY; ?>" type="text/javascript"></script>
	<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=<?php print GOOGLE_MAPS_API_KEY; ?>" type="text/javascript"></script>
	<script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/services.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($service->id == -1 || $service->id == "") {
?>
		<h2>Sorry - this entry is not available</h2>
<?php
	}
	else {
?>

		<!-- DESCRIPTION -->
		<div class="displayBox">
			<div class="displayBoxIn">
				<h2><?php print $service->title; ?> - outline</h2>
				<div class="byEditor">
					<?php print str_replace("&", "&amp;", html_entity_decode($service->content));?>
				</div>
			</div>
		</div>

		<!-- Forms -->
<?php
		$formsWithCategory = array();
		foreach ($allLGCLCategories as $lgclCategory) {
			$formsWithCategory = array_merge($formsWithCategory, getAllCategoryItemsOfType(XFORMS_FORM_CATEGORIES_TABLE, $lgclCategory->id, BESPOKE_CATEGORY_LIST_NAME));
		}
		if (sizeof($formsWithCategory) > 0) {
?>
		<div class="displayBox">
			<div class="displayBoxIn">
				<h2>Online Services</h2>
				<ul class="list">
<?php
			foreach ($formsWithCategory as $formWithCategory) {
				$form = getXFormsForm($formWithCategory->itemID, true);
				if ($form != -1) {
					print "<li><a href=\"http://$DOMAIN/site/scripts/xforms_form.php?formID=$form->id\">$form->title</a></li>\n";
				}
			}
?>
				</ul>
			</div>
		</div>
<?php
		}
?>


<?php
		if (strlen(trim($service->eligibility)) > 0) {
?>
		<div class="displayBox">
			<div class="displayBoxIn">
				<h2>Eligibility</h2>
				<?php print str_replace("&", "&amp;", $service->eligibility);?>
			</div>
		</div>
<?php
		}

		if (strlen(trim($service->accessibility)) > 0) {
?>
		<div class="displayBox">
			<div class="displayBoxIn">
				<h2>Accessibility</h2>
				<?php print str_replace("&", "&amp;", $service->accessibility);?>
			</div>
		</div>
<?php
		}

		if (strlen(trim($service->availability)) > 0) {
?>
		<div class="displayBox">
			<div class="displayBoxIn">
				<h2>Availability</h2>
				<?php print str_replace("&", "&amp;", $service->availability);?>
			</div>
		</div>
<?php
		}

		$faqsWithCategory = array();
		foreach ($allLGCLCategories as $lgclCategory) {
			$faqsWithCategory = array_merge($faqsWithCategory, getAllCategoryItemsOfType(FAQS_CATEGORIES_TABLE, $lgclCategory->id, BESPOKE_CATEGORY_LIST_NAME));
		}
		if (sizeof($faqsWithCategory) > 0) {
?>
		<div class="displayBox">
			<div class="displayBoxIn">
				<h2>Common Questions</h2>
				<ul class="list">
<?php
			$tidyFAQs = array();
			foreach ($faqsWithCategory as $faqWithCategory) {
				$faq = getFAQ($faqWithCategory->itemID);
				if ($faq != -1 && $faq != '') {
					$tidyFAQs[$faq->id] = $faq;
				}
			}
			foreach ($tidyFAQs as $faq) {
				print "<li><a href=\"http://$DOMAIN/site/scripts/faq_info.php?faqID=$faq->id\">$faq->question</a></li>\n";
			}
?>
				</ul>
			</div>
		</div>
<?php
		}

		$docsWithCategory = array();
		$docsWithCategoryAndId = array();
		foreach ($allLGCLCategories as $lgclCategory) {
			$docsWithCategory = array_merge($docsWithCategory, getAllCategoryItemsOfType(DOCUMENTS_CATEGORIES_TABLE, $lgclCategory->id, BESPOKE_CATEGORY_LIST_NAME));
		}
		foreach ($docsWithCategory as $docWithCategory) {
			$doc = getDocument($docWithCategory->itemID, true, false);
			if ($doc->id != -1) {// && !in_array($doc, $docsWithCategoryAndId)) {
				$docsWithCategoryAndId[] = $doc;
			}
		}
		if (sizeof($docsWithCategoryAndId) > 0) {
?>
		<div class="displayBox">
			<div class="displayBoxIn">
				<h2>Further Information</h2>
				<ul class="list">
<?php
			foreach ($docsWithCategoryAndId as $doc) {
					$docHeader = getDocumentHeader($doc->headerOriginalID, true);
					print "<li><a href=\"http://$DOMAIN/site/scripts/documents_info.php?documentID=$doc->id\">$docHeader->title</a></li>\n";
			}
?>
				</ul>
			</div>
		</div>
<?php
		}
?>


		<!-- CONTACTS -->
<?php
		if (sizeof($serviceToContacts) > 0) {
?>
		<div class="displayBox">
		<div class="displayBoxIn">
		<div class="contactPage">
			<ul>
<?php
			foreach ($serviceToContacts as $sToC) {
				$serviceContact = getServiceContact($sToC->contactID);

				if ($serviceContact->name != "") 		print "<li><h2>Contact: $serviceContact->name $serviceContact->jobTitle</h2></li>";
				if ($serviceContact->department != "")	print "<li class=\"first\"><strong>Department: $serviceContact->department</strong></li>";
				if ($serviceContact->email != "")		print "<li class=\"icoEmail\">Email: <a href=\"mailto:$serviceContact->email\">$serviceContact->email</a></li>";
				if ($serviceContact->telephone != "")	print "<li class=\"icoPhone\">Telephone: $serviceContact->telephone</li>";
				if ($serviceContact->fax != "")			print "<li class=\"icoFax\">Fax: $serviceContact->fax</li>";
				if ($serviceContact->url != "")			print "<li class=\"icoGlass\">Visit: <a href=\"$serviceContact->url\">$serviceContact->url</a></li>";
				if (EGOV_SERVICE_CONTACT_MODE != "Complex" && $serviceContact->address != "") {
					print "<li class=\"icoAddress\">" . nl2br($serviceContact->address) . "</li>";
				}
				if (EGOV_SERVICE_CONTACT_MODE == "Complex") {
					$serviceContact->createAddressStringFromBS7666();
					if (trim($serviceContact->address) != "") {
						print "<li class=\"icoAddress\">" . nl2br($serviceContact->address) . "</li>";
					}
				}
			}
?>
			</ul>
			</div>
			</div>
		</div>

<?php
		}
	}
?>


	<div class="altRelated">
		<?php include("../includes/related_info.php"); ?>
	</div>


	<!-- Live Search -->
	<div class="search_az longSearch">
		<div>
			<h3>Not the service you were looking for? </h3>
			<p id="az_live_find">
				<label for="searchText"><strong>Search again.</strong> Begin to type and select from the appearing choices below.</label>
				<input type="text" name="searchText" id="searchText" class="field" value="" />
				<img id="loading" style="display:none;" alt="-" src="http://<?php print $DOMAIN;?>/site/images/loading.gif" />
			</p>
			<div id="search_results"></div>
		</div>
	</div>
	<!-- End live search -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
