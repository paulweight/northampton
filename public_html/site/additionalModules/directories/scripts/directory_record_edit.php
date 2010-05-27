<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("directoryBuilder/JaduDirectories.php");
	include_once("directoryBuilder/JaduDirectoryFields.php");
	include_once("directoryBuilder/JaduDirectoryEntries.php");
	include_once("directoryBuilder/JaduDirectoryEntryValues.php");
	include_once("directoryBuilder/JaduDirectoryCategoryTree.php");
	include_once("directoryBuilder/JaduDirectoryFieldTypes.php");
    include_once('JaduUpload.php');

	if (isset($_REQUEST['recordID']) &&
	    is_numeric($_REQUEST['recordID']) &&
	    isset($_SESSION['userID'])) {

	    $directoryEntry = getDirectoryEntry($_REQUEST['recordID']);

	    if ($directoryEntry->userID != $_SESSION['userID']) {
	        header("Location: http://$DOMAIN/site/index.php");
            exit();
	    }

        $directory = getDirectory($directoryEntry->directoryID);
        $directoryFields = getAllDirectoryFields($directory->id);
    }
    else {
        header("Location: http://$DOMAIN/site/index.php");
        exit();
    }

    $directoryFieldTypes = array();
	$includeGoogleMapsJavascript = false;
    foreach ($directoryFields as $directoryField) {
        $directoryFieldType = getDirectoryFieldType($directoryField->fieldTypeID);
        $directoryFieldTypes[$directoryField->fieldTypeID] = $directoryFieldType;
        if ($directoryFieldType->name == 'Google Map') {
            $includeGoogleMapsJavascript = true;
        }
    }

    if (isset($_REQUEST['submit'])) {
        $directoryEntry->title = $_REQUEST['title'];

        $directoryEntryValues = array();

        $errors = validateDirectoryEntry($directoryEntry, '', '', $ignoreCategories = true);

        if (isset($_REQUEST['directoryEntries']) && sizeof($_REQUEST['directoryEntries']) > 0) {
            foreach ($_REQUEST['directoryEntries'] as $fieldID => $value) {
                $directoryEntryValue = new DirectoryEntryValue();
                $directoryEntryValue->fieldID = $fieldID;
                $directoryEntryValue->value = $value;
                $valueErrors = validateDirectoryEntryValue($directoryEntryValue);
                foreach ($valueErrors as $key => $val) {
                    $errors[$key] = $val;
                }
                $directoryEntryValues[] = $directoryEntryValue;
            }

            // deal with image uploads
            $filesToUpload = array();
            foreach ($_FILES as $file) {
                // get filenames
                foreach ($file['name'] as $fieldID => $filename) {

                    if (!isset($_REQUEST['replace_image_' . $fieldID])) {
                        continue;
                    }

                    if (substr($file['type'][$fieldID], 0, 5) != 'image') {
                        $errors[$fieldID] = true;
                        continue;
                    }

                    // check the filenames don't exist
                    $filename = cleanFilename ($filename);
                    $filename = checkFilenameClash($filename, $HOME . '/images/');

                    // upload the file
                    $filesToUpload[$file['tmp_name'][$fieldID]] = $HOME . '/images/' . $filename;

                    // add the directory entry
                    $directoryEntryValue = new DirectoryEntryValue();
                    $directoryEntryValue->fieldID = $fieldID;
                    $directoryEntryValue->value = $filename;
                    $directoryEntryValues[] = $directoryEntryValue;
                }
            }
        }

        if (sizeof($errors) < 1) {

            foreach ($filesToUpload as $from => $to) {
                uploadFile ($from, $to);
            }

            updateDirectoryEntry($directoryEntry);

            deleteAllValuesForEntry($directoryEntry->id);
            foreach ($directoryEntryValues as $directoryEntryValue) {
                $directoryEntryValue->entryID = $directoryEntry->id;
                newDirectoryEntryValue($directoryEntryValue); 
            }
        }
    }

	$breadcrumb = "directorySubmit";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - edit your record</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="<?php print $directory->title;?> directory submit service, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> <?php print $directory->title;?> directory submit service" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> <?php print $directory->title;?> directory submit service" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> <?php print $directory->title;?> directory submit service" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
<?php
    if ($includeGoogleMapsJavascript) {
?>
        <script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/prototype.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php print GOOGLE_MAPS_API_KEY; ?>"></script>
        <script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/directory_submit.js"></script>
<?php
    }
?>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (is_numeric($zone->id)) {
?>
	<img id="brand" src="<?php print $zone->headerImageURL	;?>" alt="<?php print getImageProperty($zone->headerImageURL, 'altText'); ?> " />

<?php
	}
	if (sizeof($errors) > 0) {
?>
		<h2 class="warning">Please check details highlighted <strong>!</strong> are entered correctly.</h2>
<?php
	}

	if (isset($_POST['submit']) && sizeof($errors) < 1) {
?>
	<p class="first">Thank you. Your record has been updated.</p>

<?php
	}
	else if (!isset($_SESSION['userID'])) {
?>
	<p class="first">You must be signed in to edit this record.</p>

<?php
	}
	else {
?>
		<p class="first">Please note, that we will review the record before making it live.</p>
		
        <form method="post" action="http://<?php print $DOMAIN;?>/site/scripts/directory_record_edit.php" enctype="multipart/form-data" class="basic_form">
            <input type="hidden" name="recordID" value="<?php print $directoryEntry->id; ?>" />
        <p>
            <label><?php if (isset($errors['title'])) { ?><span class="warning"><strong>!</strong> <?php } ?>Record title (required)<?php if (isset($errors['title'])) { ?></span><?php } ?></label>
            <input type="text" size="30" class="field" name="title" value="<?php print $directoryEntry->title; ?>" />
        </p>
<?php

    foreach ($directoryFields as $directoryField) {
        $directoryFieldType = $directoryFieldTypes[$directoryField->fieldTypeID];

        if (isset($_REQUEST['save']) && sizeof($errors) > 0) {
            $fieldElementValue = $_REQUEST['directoryEntries'][$directoryField->id];
        }
        else {
            $directoryEntryValue = getDirectoryEntryValue($directoryEntry->id, $directoryField->id);
            $fieldElementValue = $directoryEntryValue->value;
        }
        $fieldElementName = 'directoryEntries[' . $directoryField->id . ']';

        $defaultValues = getAllDirectoryFieldDefaultValues ($directoryField->id);
?>
        <p>
            <label><?php if (isset($errors[$directoryField->id])) { ?><span class="warning"><strong>!</strong> <?php } ?><?php print $directoryField->title; if ($directoryField->mandatory == '1') print ' (required)'; ?><?php if (isset($errors[$directoryField->id])) { ?></span><?php } ?></label>
<?php
        switch ($directoryFieldType->name) {
            case 'Text Box':
?>
                <input type="text" size="30" class="field" name="<?php print $fieldElementName; ?>" value="<?php print $fieldElementValue; ?>" />
        </p>
<?php
                break;
            case 'Text Area':
?>
                <textarea name="<?php print $fieldElementName; ?>" class="field" rows="3" cols="40"><?php print $fieldElementValue; ?></textarea>
        </p>
<?php
                break;
            case 'Select Box':
?>
                <select name="<?php print $fieldElementName; ?>" class="field">
                    <option value="">Select...</option>
<?php
                foreach ($defaultValues as $defaultValue) {
?>
                    <option value="<?php print $defaultValue->value; ?>" <?php if ($defaultValue->value == $fieldElementValue) print 'selected="selected"'; ?>>
                        <?php print $defaultValue->value; ?>
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
                <label for="<?php print $fieldElementName; ?>0"><input type="radio" id="<?php print $fieldElementName; ?>0" name="<?php print $fieldElementName; ?>" <?php if (empty($fieldElementValue)) print 'checked="checked"'; ?> value="" /> None</label><br />
        </p>
<?php
                $count = 1;
                foreach ($defaultValues as $defaultValue) {
?>
                    <label for="<?php print $fieldElementName . $count; ?>"><input type="radio" class="field" id="<?php print $fieldElementName . $count++; ?>" name="<?php print $fieldElementName; ?>" value="<?php print $defaultValue->value; ?>" <?php if ($defaultValue->value == $fieldElementValue) print 'checked="checked"'; ?> /> <?php print $defaultValue->value; ?></label><br />
        </p>
<?php
                }

                break;
            case 'Image':
?>
                <label for="change_image_<?php print $directoryField->id; ?>">
                    <input type="checkbox" id="change_image_<?php print $directoryField->id; ?>" name="replace_image_<?php print $directoryField->id; ?>" value="1" onchange="toggleImageUpload('<?php print $fieldElementName; ?>')"  /> 
                    Replace current image
                </label>
				<input type="file" name="<?php print $fieldElementName; ?>" class="field upload" id="<?php print $fieldElementName; ?>" value="<?php print $fieldElementValue; ?>" style="display:none;" />
				<span class="clear"></span>
        </p>
<?php
                break;
            case 'Google Map':
                if (!empty($fieldElementValue)) {
                    list ($longitude, $latitude) = explode(',', $fieldElementValue);
                }
?>
        </p>
                <input type="hidden" class="field" id="latlong_<?php print $directoryField->id; ?>" name="<?php print $fieldElementName; ?>" value="<?php print $fieldElementValue; ?>" />
                <div id="map_<?php print $directoryField->id; ?>" class="googleMap" style="height:300px; width:500px;"></div>
<?php
        }
    }
?>

        <p class="center">
            <input type="submit" class="button" name="submit" value="Submit" />
        </p>
        </form>

<?php
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php");?>