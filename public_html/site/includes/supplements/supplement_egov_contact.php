<?php
	include_once('egov/JaduEGovJoinedUpServicesContacts.php');
	$contact = getServiceContact($record->contactID);
	
	if ($contact->id > 0) {
?>
<!-- EGov Contact -->
<div class="supplement vcard">
	<h3><?php print encodeHtml($contact->name); ?></h3>
<?php
	if ($contact->department != '') {
?>
	<p class="org"><strong>Department: <span class="organization-name hidden"><?php print encodeHtml(METADATA_GENERIC_NAME); ?></span> <span class="organization-unit"><?php print encodeHtml($contact->department); ?></span></strong></p>
<?php
	}
	
	if ($contact->email != '') {
?>
	<p><strong>Email:</strong> <a href="mailto:<?php print urlencode($contact->email); ?>" class="email"><?php print encodeHtml($contact->email); ?></a></p>
<?php
	}
	
	if ($contact->telephone != '') {
?>
	<p><strong>Telephone:</strong> <span class="tel"><?php print encodeHtml($contact->telephone); ?></span></p>
<?php
	}
	
	if ($contact->fax != '') {
?>
	<p class="tel"><strong class="type">Fax:</strong> <?php print encodeHtml($contact->fax); ?></p>
<?php
	}

	if ($contact->url != '') {
?>
	<p><strong>Visit:</strong> <a href="<?php print encodeHtml($contact->url); ?>" class="url"><?php print encodeHtml($contact->url); ?></a></p>
<?php
	}
	
	if (EGOV_SERVICE_CONTACT_MODE != 'Complex' && $contact->address != '') {
?>
	<p class="adr"><?php print nl2br(encodeHtml($contact->address)); ?></p>
<?php
	}
	else if (EGOV_SERVICE_CONTACT_MODE == 'Complex') {
		$contact->createAddressStringFromBS7666();
		if (trim($contact->address) != '') {
?>
	<p class="adr"><?php print nl2br(encodeHtml(preg_replace('/, /', PHP_EOL, $contact->address))); ?></p>
<?php
		}
	}
?>
</div>
<?php
	}
?>