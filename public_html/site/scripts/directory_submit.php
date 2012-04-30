<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("directoryBuilder/JaduDirectories.php");
	include_once("directoryBuilder/JaduDirectoryFields.php");
	include_once("directoryBuilder/JaduDirectoryEntries.php");
	include_once("directoryBuilder/JaduDirectoryEntryValues.php");
	include_once("directoryBuilder/JaduDirectoryUserEntries.php");
	include_once("directoryBuilder/JaduDirectoryUserEntryValues.php");
	include_once("directoryBuilder/JaduDirectoryCategoryTree.php");
	include_once("directoryBuilder/JaduDirectoryFieldTypes.php");
    include_once('JaduUpload.php');

	if (!isset($_REQUEST['directoryID']) || !is_numeric($_REQUEST['directoryID'])) {
		header('Location: http://' . DOMAIN);
        exit();
	}

    $directory = getDirectory($_REQUEST['directoryID'], true);

	if ($directory->id == -1) {
		header('Location: http://' . DOMAIN);
        exit();
	}

    $directoryFields = getAllDirectoryFields($directory->id);

    $directoryFieldTypes = array();
	$includeGoogleMapsJavascript = false;
    foreach ($directoryFields as $directoryField) {
        $directoryFieldType = getDirectoryFieldType($directoryField->fieldTypeID);
        $directoryFieldTypes[$directoryField->fieldTypeID] = $directoryFieldType;
        if ($directoryFieldType->name == 'Google Map') {
            $includeGoogleMapsJavascript = true;
        }
    }

    if (isset($_REQUEST['submit']) && Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
        $directoryEntry = new DirectoryUserEntry();
        $directoryEntry->directoryID = $_REQUEST['directoryID'];
        $directoryEntry->title = $_REQUEST['title'];
        $directoryEntry->userID = Jadu_Service_User::getInstance()->getSessionUserID();

        $directoryEntryValues = array();
		$filesToUpload = array();

        $errors = validateDirectoryUserEntry($directoryEntry, '', '', $ignoreCategories = true);

        if (isset($_REQUEST['directoryEntries']) && sizeof($_REQUEST['directoryEntries']) > 0) {
            foreach ($_REQUEST['directoryEntries'] as $fieldID => $value) {
                $directoryEntryValue = new DirectoryUserEntryValue();
                $directoryEntryValue->fieldID = $fieldID;
                $directoryEntryValue->value = $value;
                $valueErrors = validateDirectoryUserEntryValue($directoryEntryValue);
                foreach ($valueErrors as $key => $val) {
                    $errors[$key] = $val;
                }
                $directoryEntryValues[] = $directoryEntryValue;
            }

            // deal with image uploads
            foreach ($_FILES as $file) {
                // get filenames
                foreach ($file['name'] as $fieldID => $filename) {

                    $allowedExtensions = array("jpg", "jpeg", "gif", "png");

                    // get the extension of the file - anything after the first .
                    $extension = mb_strtolower(mb_substr($file['name'][$fieldID], mb_strpos($file['name'][$fieldID], '.', 0) + 1));

                    // check that the mime type is image
                    // and that the extension is in the allowed list
                    if (mb_substr($file['type'][$fieldID], 0, 5) != 'image' || !in_array($extension, $allowedExtensions)) {
                        $errors[$fieldID] = true;
                        continue;
                    }

                    // check the filenames don't exist
                    $filename = cleanFilename ($filename);
                    $filename = checkFilenameClash($filename, $HOME . '/images/');

                    // upload the file
                    $filesToUpload[$file['tmp_name'][$fieldID]] = $HOME . '/images/' . $filename;

                    // add the directory entry
                    $directoryEntryValue = new DirectoryUserEntryValue();
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

            $directoryEntry->id = newDirectoryUserEntry($directoryEntry);

            foreach ($directoryEntryValues as $directoryEntryValue) {
                $directoryEntryValue->userEntryID = $directoryEntry->id;
                newDirectoryUserEntryValue($directoryEntryValue); 
            }
			
			$directory = getDirectory ($directoryEntry->directoryID);
			$toAddress = $directory->adminEmail;
			if (isset($toAddress) && $toAddress != '') {
				$headerEmail = DEFAULT_EMAIL_ADDRESS;
				
				$HEADER = "From: $headerEmail\r\nReply-to: $headerEmail\r\nContent-Type: text/plain; charset=UTF-8;\r\nContent-Transfer-Encoding: 8bit\r\n";

				$SUBJECT = $DOMAIN. " New directory entry has been added.";
				$MESSAGE = "A directory entry has been added from the " . DOMAIN . " website.\n\n";

				if (!empty($directory->name)) {
					$MESSAGE .= "Directory: " . $directory->name . "\n";
				}
				if (!empty($directoryEntry->title)) {
					$MESSAGE .= "Entry title: " . $directoryEntry->title . "\n";
				}			
				
				mail($toAddress, $SUBJECT, $MESSAGE, $HEADER);				
			}
        }
    }

	// Breadcrumb, H1 and Title
	$MAST_HEADING = $directory->name .' - Submit a record';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><a href="' . buildDirectoriesURL() . '">Online directories</a></li><li><a href="' . buildDirectoriesURL(-1, $directory->id) . '">'. encodeHtml($directory->name) .'</a></li><li><span>Submit a record</span></li>';
	
	include("directory_submit.html.php");
?>