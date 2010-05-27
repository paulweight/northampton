<?php
	session_start();
	
	if (isset($_GET['userFormID'])) {
		
		include_once('utilities/JaduAdministrators.php');
		include_once("utilities/JaduAdminCurrentLogins.php");
		
		include_once("xforms2/JaduXFormsForm.php");
		include_once("xforms2/JaduXFormsUserForms.php");
		include_once("xforms2/JaduXFormsPDFForms.php");
		
		$userForm = getXFormsUserForm($_GET['userFormID']);
		
		if ($userForm != -1) {
			$form = getXFormsForm($userForm->formID);

			$pdfHash = md5($_GET['userFormID'] . $PROTOCOL . $DOMAIN . 
				METADATA_GENERIC_COUNCIL_NAME);
		
			$registeredUserValid = (isset($_SESSION['userID']) && $userForm->userID == 
				$_SESSION['userID']);
				
			$unregisteredUserValid = !isset($_SESSION['userID']) && 
				$form->allowUnregistered == 1 && $_GET['hash'] == $pdfHash;
			
			$adminAllowedToProgress = false;
			
			//	Maybe lloading via admin / Control Centre
			if (!$registeredUserValid && !$unregisteredUserValid) {
				
				if (isset($_GET['adminID']) && isset($_GET['c'])) {
					$lastLoginAttempts = getXMostRecentLoginAttemptForAdmin($_GET['adminID'], 10);
				
					if (!empty($lastLoginAttempts) && is_array($lastLoginAttempts)) {
						foreach ($lastLoginAttempts as $lastLoginAttempt) {
							if ($_SERVER['REMOTE_ADDR'] == $lastLoginAttempt->ipAddress) {
								$admin = getAdministrator($_GET['adminID']);					
								$assumedHash = md5($_GET['adminID'].$admin->name.$DOMAIN.$admin->password.$userForm->id);
								if ($assumedHash == $_GET['c']) {
									$adminAllowedToProgress = true;
									break;
								}
							}
						}
					}
				}
			}
			
			//	userID may not be set due to unregistered user filling in form - but if it is set then test it.
			if (!$registeredUserValid && !$unregisteredUserValid && !$adminAllowedToProgress) {
				header("Location: $ERROR_REDIRECT_PAGE");
				exit();
			}
			else {

				// stream a file from the directory in which user form atachments are stored.
				$pdfFilename = XFORMS_RECEIVED_PDF_FORMS_DIRECTORY . 'JADU_' . $userForm->getDateFormatted('completedTimestamp', 'Y-m-d') . '_' . $userForm->id . '.pdf';
				$userPDFFormExists = checkXFormsUserPDFFormExists($pdfFilename);
				
				if ($userPDFFormExists === XFORMS_PDF_FORM_EXCEPTION_SUCCESSFUL) {
					$fp = fopen($pdfFilename, "rb");
					$data = fread($fp, filesize($pdfFilename));
					fclose($fp);
					
					$att = " attachment;";
					if (strstr($_SERVER["HTTP_USER_AGENT"],"MSIE 5.5")) {
						$att = "";
					}
					
					header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
					header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
					header('Content-Length: '.filesize($pdfFilename)); 
					header('Content-Type: application/force-download');
					header('Content-Disposition:'.$att.' filename="'.basename($pdfFilename).'"');
					header('Content-Transfer-Encoding: binary');
					
					print $data;
				}
			}
		}
	}
	
	exit();
?>