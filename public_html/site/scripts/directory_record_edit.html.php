<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="<?php print encodeHtml($directory->title);?> directory submit service, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> <?php print encodeHtml($directory->title);?> directory submit service" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> <?php print encodeHtml($directory->title);?> directory submit service" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> <?php print encodeHtml($directory->title);?> directory submit service" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />

<?php
    if ($includeGoogleMapsJavascript) {
?>
        <script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php print encodeHtml(GOOGLE_MAPS_API_KEY); ?>"></script>  
<?php
    }
?>
	<script type="text/javascript" src="<?php print getStaticContentRootURL(); ?>/site/javascript/directory_submit.js"></script>
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (is_numeric($zone->id)) {
?>
	<img id="brand" src="<?php print encodeHtml($zone->headerImageURL); ?>" alt="<?php print encodeHtml(getImageProperty($zone->headerImageURL, 'altText')); ?> " />

<?php
	}
	if (sizeof($errors) > 0) {
?>
	<h2 class="warning">Please check details highlighted ! are entered correctly</h2>
<?php
	}

	if (isset($_POST['submit']) && sizeof($errors) < 1) {
?>
	<p>Thank you for your submission.  We will now review the record before making it live.</p>
	<p>You can modify your record details from your <a href="<?php print buildUserHomeURL(); ?>">user account page</a>.</p>

<?php
	}
	else if (!Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
?>
	<p>You must be signed in to edit this record.</p>

<?php
	}
	else {
?>
	<p>Please note, that we will review the record before making it live.</p>

	<form enctype="multipart/form-data" method="post" action="<?php print getSiteRootURL() . buildNonReadableDirectoryRecordURL($directoryEntry->id, -1, -1, true); ?>">
		<input type="hidden" name="<?php print $idType; ?>" value="<?php print (int) $directoryEntry->id; ?>" />        	
		<input type="hidden" name="approved" value="<?php print (int) $approved; ?>" />
		
		<p><label><?php if (isset($errors['title'])) { ?><span class="warning"><strong>!</strong> <?php } ?>Record title <em>required</em><?php if (isset($errors['title'])) { ?></span><?php } ?></label><input type="text" size="30" name="title" value="<?php print encodeHtml($directoryEntry->title); ?>" /></p>
<?php

foreach ($directoryFields as $directoryField) {
	$directoryFieldType = $directoryFieldTypes[$directoryField->fieldTypeID];

	if (isset($_REQUEST['save']) && sizeof($errors) > 0) {
		$fieldElementValue = $_REQUEST['directoryEntries'][$directoryField->id];
	}
	else {
		// if it hasn't yet been approved, then get from user entries table
		if (!$approved) {
			$directoryEntryValue = getDirectoryUserEntryValue($directoryEntry->id, $directoryField->id);
		}
		else {
			$directoryEntryValue = getDirectoryEntryValue($directoryEntry->id, $directoryField->id);
		}
		
		$fieldElementValue = $directoryEntryValue->value;
	}
	$fieldElementName = 'directoryEntries[' . $directoryField->id . ']';

	$defaultValues = getAllDirectoryFieldDefaultValues ($directoryField->id);
?>
	<p>
		<label><?php if (isset($errors[$directoryField->id])) { ?><span class="warning"><strong>!</strong> <?php } ?><?php print encodeHtml($directoryField->title); if ($directoryField->mandatory == '1') print ' <em>required</em>'; ?><?php if (isset($errors[$directoryField->id])) { ?></span><?php } ?></label>
<?php
	switch ($directoryFieldType->name) {
		case 'Text Box':
?>
			<input type="text" size="30" name="<?php print encodeHtml($fieldElementName); ?>" value="<?php print encodeHtml($fieldElementValue); ?>" />
	</p>
<?php
			break;
		case 'Link':
?>
			<input type="text" size="30" name="<?php print encodeHtml($fieldElementName); ?>" value="<?php print encodeHtml($fieldElementValue); ?>" />
	</p>
<?php
			break;
		case 'Email':
?>
			<input type="text" size="30" name="<?php print encodeHtml($fieldElementName); ?>" value="<?php print encodeHtml($fieldElementValue); ?>" />
	</p>
<?php
			break;                
		case 'Text Area':
?>
			<textarea name="<?php print encodeHtml($fieldElementName); ?>" rows="3" cols="40"><?php print encodeHtml($fieldElementValue); ?></textarea>
	</p>
<?php
			break;
		case 'Select Box':
?>
			<select name="<?php print encodeHtml($fieldElementName); ?>" class="field">
				<option value="">Select...</option>
<?php
			foreach ($defaultValues as $defaultValue) {
?>
				<option value="<?php print encodeHtml($defaultValue->value); ?>" <?php if ($defaultValue->value == $fieldElementValue) print 'selected="selected"'; ?>>
					<?php print encodeHtml($defaultValue->value); ?>
				</option>
<?php
			}
?>
			</select>
	</p>
<?php
			break;
		case 'Radio Buttons':
?>
			<span class="radioButtons">
				<label for="<?php print encodeHtml($fieldElementName); ?>0"><input type="radio" id="<?php print encodeHtml($fieldElementName); ?>0" name="<?php print encodeHtml($fieldElementName); ?>" <?php if (empty($fieldElementValue)) print 'checked="checked"'; ?> value="" /> None</label>
<?php
			$count = 1;
			foreach ($defaultValues as $defaultValue) {
?>
				<label for="<?php print encodeHtml($fieldElementName) . $count; ?>"><input type="radio" id="<?php print encodeHtml($fieldElementName) . $count++; ?>" name="<?php print encodeHtml($fieldElementName); ?>" value="<?php print encodeHtml($defaultValue->value); ?>" <?php if ($defaultValue->value == $fieldElementValue) print 'checked="checked"'; ?> /> <?php print encodeHtml($defaultValue->value); ?></label>
			</span>
		</p>
<?php
			}

			break;
		case 'Image':
?>
			<label for="change_image_<?php print (int) $directoryField->id; ?>">
				<input type="checkbox" id="change_image_<?php print (int) $directoryField->id; ?>" name="replace_image_<?php print (int) $directoryField->id; ?>" value="1" onclick="toggleImageUpload('<?php print encodeHtml($fieldElementName); ?>')"  />
				Replace current image
			</label>
			<input type="file" name="<?php print encodeHtml($fieldElementName); ?>" class="field upload" id="<?php print encodeHtml($fieldElementName); ?>" style="display:none;" />
	</p>
<?php
			break;
		case 'Google Map':
?>
	</p>
			<input type="hidden" id="latlong_<?php print (int) $directoryField->id; ?>" name="<?php print encodeHtml($fieldElementName); ?>" value="<?php print encodeHtml($fieldElementValue); ?>" />
			<div id="map_<?php print (int) $directoryField->id; ?>" class="googleMap"  style="height:300px; width:700px;"></div>
<?php
	}
}
?>
		<p>
			<input type="submit" name="submit" value="Submit" />
		</p>
	</form>

<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php");?>