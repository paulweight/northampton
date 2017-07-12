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
        <script type="text/javascript" src="https://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php print encodeHtml(GOOGLE_MAPS_API_KEY); ?>"></script>
        <script type="text/javascript" src="<?php print getStaticContentRootURL(); ?>/site/javascript/directory_submit.js"></script>
<?php
    }
?>
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
	<h2 class="warning">Please check details highlighted with ! are entered correctly</h2>
<?php
	}

	if (isset($_POST['submit']) && sizeof($errors) < 1) {
?>
	<p>Thank you for your submission.  We will now review the record before making it live.</p>
	<p>You can modify your record details from your <a href="<?php print buildUserHomeURL(); ?>">user account page</a>.</p>

<?php
	}
	elseif (!Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
?>
	<p>You must be <a href="<?php print getSecureSiteRootURL() . buildSignInURL($_SERVER['REQUEST_URI']); ?>">signed in</a> to submit a record to this directory.</p>

<?php
	}
	else {
?>
	<p>Please note that we will review the record before making it live.</p>

	<form method="post" enctype="multipart/form-data" action="<?php print getSiteRootURL() . buildNonReadableDirectoriesURL(-1, $directory->id, true); ?>">
		<input type="hidden" name="directoryID" value="<?php print (int) $directory->id; ?>" />
        <p>
            <label><?php if (isset($errors['title'])) { ?><span class="warning"><strong>!</strong> <?php } ?>Record title (required)<?php if (isset($errors['title'])) { ?></span><?php } ?></label>
            <input type="text" size="30" name="title" value="<?php print encodeHtml($_REQUEST['title']); ?>" />
        </p>
<?php

    foreach ($directoryFields as $directoryField) {
        $directoryFieldType = $directoryFieldTypes[$directoryField->fieldTypeID];

        if (isset($_REQUEST['submit']) && sizeof($errors) > 0) {
            $fieldElementValue = $_REQUEST['directoryEntries'][$directoryField->id];
        }
        else {
            $directoryEntryValue = getDirectoryEntryValue($_REQUEST['directoryEntryID'], $directoryField->id);
            $fieldElementValue = $directoryEntryValue->value;
        }
        $fieldElementName = 'directoryEntries[' . $directoryField->id . ']';

        $defaultValues = getAllDirectoryFieldDefaultValues ($directoryField->id);
?>
        <p>
            <label><?php if (isset($errors[$directoryField->id])) { ?><span class="warning"><strong>!</strong> <?php } ?><?php print encodeHtml($directoryField->title); if ($directoryField->mandatory == '1') print '(required)'; ?><?php if (isset($errors[$directoryField->id])) { ?></span><?php } ?></label>
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
                <select name="<?php print encodeHtml($fieldElementName); ?>">
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
<?php
                }
?>
				</span>
				<span class="clear"></span>
			</p>
<?php
                break;
            case 'Image':
?>
				<input type="file" name="<?php print encodeHtml($fieldElementName); ?>" id="<?php print encodeHtml($fieldElementName); ?>" />
        </p>
<?php
                break;
            case 'Google Map':
                if (!empty($fieldElementValue)) {
                    list ($longitude, $latitude) = explode(',', $fieldElementValue);
                }
?>
        </p>
                <input type="hidden" id="latlong_<?php print (int) $directoryField->id; ?>" name="<?php print encodeHtml($fieldElementName); ?>" value="<?php print encodeHtml($fieldElementValue); ?>" />
                <div id="map_<?php print (int) $directoryField->id; ?>" class="googleMap" style="height:300px; width:750px;"></div>
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