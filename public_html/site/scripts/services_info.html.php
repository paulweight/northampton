<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($service->title); ?> | <?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print encodeHtml($service->title);?>, services, a-z, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml($service->title);?> - <?php print encodeHtml(METADATA_GENERIC_NAME); ?> A to Z of services" />
<?php
	list($metadata, $gclString, $lgclString, $gclNonPreferred) = getAllMetadata(SERVICES_METADATA_TABLE, SERVICES_CATEGORIES_TABLE, $_GET['serviceID']);
	$meta = new JaduMetadata();

	if ($gclString != '' && $lgclString != '' && $meta != $metadata) {
		printMetadata(SERVICES_METADATA_TABLE, SERVICES_CATEGORIES_TABLE, $_GET['serviceID'], $service->title, "http://".$DOMAIN.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
	}
	else {
?>
	<meta name="DC.title" lang="en" content="<?php print encodeHtml($service->title);?> - <?php print encodeHtml(METADATA_GENERIC_NAME); ?> A to Z of services" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml($service->title);?> - <?php print encodeHtml(METADATA_GENERIC_NAME); ?> A to Z of services" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
<?php
	}
	if ($service->PID_ID > 0) {
?>
	<meta name="eGMS.subject.service" lang="en" scheme="LGSL" content="<?php print encodeHtml($PID->PIDName); ?>" />
<?php
	}
?>

</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if ($service->id == -1) {
?>
		<h2>Sorry - this entry is not available</h2>
<?php
	}
	else {
?>

		<!-- DESCRIPTION -->
		<div class="byEditor article">
			<h2><?php print encodeHtml($service->title); ?>: Outline</h2>
			<?php print processEditorContent($service->content); ?>
		</div>

		<!-- Forms -->
<?php
		$formsWithCategory = array();
		foreach ($allLGCLCategories as $lgclCategory) {
			if ($lgclCategory !== null) {
				$formsWithCategory = array_merge($formsWithCategory, getAllCategoryItemsOfType(XFORMS_FORM_CATEGORIES_TABLE, $lgclCategory->id, BESPOKE_CATEGORY_LIST_NAME));
			}
		}

		$liveForms = array();
		$liveFormIDs = array(); // Store IDs used.
		foreach($formsWithCategory as $formWithCategory) {
			$form = getXFormsForm($formWithCategory->itemID, true);
			if ($form != -1) {
				if (!in_array($form->id, $liveFormIDs)) { // Prevent Duplicates
					$liveForms[] = $form;
					$liveFormIDs[] = $form->id;
				} 
			}
		}		
		
		if (sizeof($liveForms) > 0) {
?>
			<div class="listed_item">
				<h2>Online Services</h2>
				<ul class="list icons services">
<?php
			foreach ($liveForms as $form) {
				print '<li><a href="' . getSiteRootURL() . buildXFormsURL($form->id) . '">' . encodeHtml($form->title) . '</a></li>';
			}
?>
				</ul>
			</div>
<?php
		}
?>


<?php
		if (mb_strlen(trim($service->eligibility)) > 0) {
?>
		<div class="section">
			<h2>Eligibility</h2>
			<p><?php print encodeHtml($service->eligibility); ?></p>
		</div>
<?php
		}

		if (mb_strlen(trim($service->accessibility)) > 0) {
?>
		<div class="section">
			<h2>Accessibility</h2>
			<p><?php print encodeHtml($service->accessibility); ?></p>
		</div>
<?php
		}

		if (mb_strlen(trim($service->availability)) > 0) {
?>
		<div class="section">
			<h2>Availability</h2>
			<p><?php print encodeHtml($service->availability); ?></p>
		</div>
<?php
		}

		$faqsWithCategory = array();
		foreach ($allLGCLCategories as $lgclCategory) {
			if ($lgclCategory !== null) {
				$faqsWithCategory = array_merge($faqsWithCategory, getAllCategoryItemsOfType(FAQS_CATEGORIES_TABLE, $lgclCategory->id, BESPOKE_CATEGORY_LIST_NAME));
			}
		}
		if (sizeof($faqsWithCategory) > 0) {
			$count = 0; 
			foreach ($faqsWithCategory as $faqWithCategory) {
				$faq = getFAQ($faqWithCategory->itemID);
				if (isset($faq) && $faq->id != -1) {					
					if ($count++ == 0) {
?>
			<div class="section">
				<h2>Common Questions</h2>
				<ul class="list icons faqs">
<?php 
					}
?>
					<li><a href="<?php print getSiteRootURL() . buildIndividualFAQURL($faq->id); ?>"><?php print encodeHtml($faq->question); ?></a></li>
<?php 			}
			}

			if ($count > 0) {
?>
				</ul>
<?php
			}
?>
			</div>
<?php
		}

		$docsWithCategory = array();
		$docsWithCategoryAndId = array();
		foreach ($allLGCLCategories as $lgclCategory) {
			if ($lgclCategory !== null) {
				$docsWithCategory = array_merge($docsWithCategory, getAllCategoryItemsOfType(DOCUMENTS_CATEGORIES_TABLE, $lgclCategory->id, BESPOKE_CATEGORY_LIST_NAME));
			}
		}
		foreach ($docsWithCategory as $docWithCategory) {
			$doc = getDocument($docWithCategory->itemID, true, false);
			if ($doc->id != -1 && !in_array($doc, $docsWithCategoryAndId)) {
				$docsWithCategoryAndId[] = $doc;
			}
		}
		if (sizeof($docsWithCategoryAndId) > 0) {
?>
			<div class="section">
				<h2>Further Information</h2>
				<ul class="list icons documents">
<?php
			foreach ($docsWithCategoryAndId as $doc) {
					$docHeader = getDocumentHeader($doc->headerOriginalID);
?>
					<li><a href="<?php print getSiteRootURL() . buildDocumentsURL($doc->id); ?>"><?php print encodeHtml($docHeader->title); ?></a></li>
<?php
			}
?>
				</ul>
			</div>
<?php
		}
?>


		<!-- CONTACTS -->
<?php
		$serviceContacts = $service->getContacts();
		if (count($serviceContacts) > 0) {
			foreach ($serviceContacts as $serviceContact) {
				if ($serviceContact->name != "") 		print "<h3>Contact: " . encodeHtml($serviceContact->name) . " " . encodeHtml($serviceContact->jobTitle) . "</h3>";
			print '<ul>';
				if ($serviceContact->department != "")	print "<li><strong>Department: " . encodeHtml($serviceContact->department) . "</strong></li>";
				if ($serviceContact->email != "")		print "<li><span class=\"email\">Email: <a href=\"mailto:" . encodeHtml($serviceContact->email) . "\">" . encodeHtml($serviceContact->email) . "</a></span></li>";
				if ($serviceContact->telephone != "")	print "<li><span class=\"tel\">Telephone: " . encodeHtml($serviceContact->telephone) . "</span></li>";
				if ($serviceContact->fax != "")			print "<li><span class=\"fax\">Fax: " . encodeHtml($serviceContact->fax) . "</span></li>";
				if ($serviceContact->url != "")			print "<li><span class=\"adr\">Visit: <a href=\"" . encodeHtml($serviceContact->url) . "\">" . encodeHtml($serviceContact->url) . "</a></span></li>";
				if (EGOV_SERVICE_CONTACT_MODE != "Complex" && $serviceContact->address != "") {
					print "<li><span class=\"adr\">" . nl2br(encodeHtml($serviceContact->address)) . "</span></li>";
				}
				if (EGOV_SERVICE_CONTACT_MODE == "Complex") {
					$serviceContact->createAddressStringFromBS7666();
					if (trim($serviceContact->address) != "") {
						print "<li><span class=\"adr\"> " . nl2br(encodeHtml($serviceContact->address)) . "</span></li>";
					}
				}
			print '</ul>';
			}
		}
	}
?>

<?php include("../includes/services_live_search.php") ?>
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
