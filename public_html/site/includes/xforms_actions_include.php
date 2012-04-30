<?php
if (basename($_SERVER['PHP_SELF']) == 'xforms_form.php' || basename($_SERVER['PHP_SELF']) == 'xforms_internal_form.php' || basename($_SERVER['PHP_SELF']) == 'worldpay_order_confirmation.php') {


/*					$autoRegistrationComplete = false;
					$autoRegisterContactAlreadyExists = false;
					
					//	SOCITM CRM Integration
					if (XFORMS_CRM_INTEGRATION_ENABLED && mb_strpos($form->action, 'crm') !== false) {
						$branchingRoute = determineXFormsFormBranchingRouteFromUserPath($form->id, $userForm->pagePath);
						
						//	What service Request Type is assigned within the Schema mappings to a GV Note
						$gvNote = Jadu_GoldVision_DataManager::getDefaultNoteInstance();
						$gvNote->noteType = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'requestType');
						$gvNote->summary = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'requestSummary');
						
						//	Submission of the Xform as a Note against an existing Contact / Account
						if ($gvMan->isCurrentSessionLoggedIn()) {
							$gvContact = $gvMan->getCurrentSessionContact();
							$gvNote->contactID = $gvContact->id;
							if ($gvNote->noteType == 'ACCOUNT') {
								$gvNote->parentID = $gvContact->accountID;	//	Assumes a NOTE_TYPE of ACCOUNT
							}
							else if ($gvNote->noteType == 'CONTACT') {
								$gvNote->parentID = $gvContact->id;	//	Assumes a NOTE_TYPE of CONTACT
							}
						}
						else {
							//	Do a mini registration of a Contact & Account if specified that they want to on this form
							$requestAutoRegisterContact = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'requestAutoRegisterContact');

							if (mb_strtoupper($requestAutoRegisterContact) == 'YES') {
								
								//	Assign contact post values
								$contact = Jadu_GoldVision_DataManager::getDefaultContactInstance();
								$contact->email = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'email', 0);								
								$contact->salutation = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'title', 0);
								$contact->forename = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'forename', 0);
								$contact->surname = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'surname', 0);
								$contact->address1 = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'address1', 0);
								$contact->address2 = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'address2', 0);
								$contact->address3 = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'address3', 0);
								$contact->city = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'city', 0); //
								$contact->county = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'county', 0); //
								$contact->regions[] = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'regions', 0); 
								$contact->postcode = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'postcode', 0);
								$contact->country = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'country', 0);
								$contact->jobtitle = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'jobTitle', 0);
								$contact->telephone = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'telephone', 0);
								$contact->fax = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'fax', 0);
								$contactValidation = $contact->validate();
								
								if (empty($contactValidation)) {
									
									//	check to see if the contact exists
									$contactID = $gvMan->doesContactExist($contact);
									
									if ($contactID == -1) { //	contact doesn't already exist
								
										//	Assign acount post values
										$account = Jadu_GoldVision_DataManager::getDefaultAccountInstance();
										$account->name = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'accountName', 0);
										$account->address1 = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'address1', 1);
										$account->address2 = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'address2', 1);
										$account->address3 = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'address3', 1);
										$account->city = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'city', 1);
										$account->county = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'county', 1);
										$account->postcode = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'postcode', 1);
										$account->country = getCRMSchemaElementMappingValue($userForm, $branchingRoute, 'country', 1);
										$accountValidation = $account->validate();
																		
										if (empty($accountValidation)) {
	
											//check if the account already exists
											$accountID = $gvMan->doesAccountExist($account);
											if ($accountID == -1) { //if it doesnt, create a new one
												$gvMan->addNewAccount($account);
											}
											else { //	if it does, get the existing account
												$account = $gvMan->getAccountByID($accountID);
											}
											
											//	set the accountID of the contact to the account from above
											$contact->accountID = $account->id;
											
											//	set contact as not authorised
											$contact->authorised = 0;
											
											//	add the contact
											$gvMan->addNewContact($contact);
											
											//	set the password (randomly generated as silent registering)
											//	What do we do about a contact password though? Generate a random one so as not to have to store it as part of the XForm submission.
											include_once("library/JaduStringFunctions.php");
											$gvMan->updateContactPasswordNonVerified($contact->id, generateRandomString(6, 10, true, false, true));
											
											//	send out an authorisation token
											include_once('GoldVision/ContactAuthToken.php');
											$token = new Jadu_GoldVision_ContactAuthToken();
											$token->contactID = $contact->id;
											$token->emailAddress = $contact->email;
											$token->initToken();
											$token->save();
											$token->mailContactAuthorisationToken();
											
											$autoRegistrationComplete = true;
											
											//	should we then update the UserForm->userID?
											
										}
									}
									else {
										$contact = $gvMan->getContactByID($contactID);
										$autoRegisterContactAlreadyExists = true;
									}
								}
							}
						}
						
						//	Sort them to ensure in correct order in terms of 'NN' in all 'attributeNN' fields
						$sortedCRMQuestionSchemaElements = array();
						$crmQuestionSchemaElements = getXFormsCRMSchemaElementBySchemaElementNameLike($form->id, $branchingRoute->id, 'attribute%');
						foreach($crmQuestionSchemaElements as $crmQuestionSchemaElement) {
							$crmQuestionSchemaElementNumber = mb_substr($crmQuestionSchemaElement->schemaElementName, 9);  //	9 = length of 'attribute'
							$sortedCRMQuestionSchemaElements[$crmQuestionSchemaElementNumber-1] = $crmQuestionSchemaElement;
						}
						ksort($sortedCRMQuestionSchemaElements, SORT_NUMERIC);
						
						//	Build up the 'details' attribute
						foreach ($sortedCRMQuestionSchemaElements as $questionSchemaElementIndex => $questionSchemaElement) {
							$questionSchemaElementMappings = getAllXFormsQuestionToCRMSchemaElementMappingForSchemaElement($questionSchemaElement->id);
							$questionSchemaElementMapping = $questionSchemaElementMappings[0];
							if ($questionSchemaElementMapping->questionID > 0) {
								$xformsServiceRequestQuestion = getXFormsFormQuestion($questionSchemaElementMapping->questionID);
								if ($xformsServiceRequestQuestion->id > 0) {
									$gvNote->details .= " \n";
									$gvNote->details .= $xformsServiceRequestQuestion->question . ': ';
									$questionAnswer = getQuestionAnswerIfExists($xformsServiceRequestQuestion->formPageID, $userForm->id, $questionSchemaElementMapping->questionID, $questionSchemaElementMapping->elementID, $questionSchemaElementMapping->elementNumber);
									$gvNote->details .= $questionAnswer->answer;
								}
							}
							else if (mb_strlen($questionSchemaElementMapping->staticText) > 0) {
								$gvNote->details .= " \n";
								$gvNote->details .= $questionSchemaElementIndex . ': '; // Just the number
								$gvNote->details .= $questionSchemaElementMapping->staticText;
							}
						}
						
						//	Need to check what this is doing, and if not approrpriate, create new adapter with stripped down featureset
//						$crmResult = generateXMLForCRM ($form, $user, getXFormsUserForm($userForm->id), getAllXFormsQuestionAnswersForForm($userForm->id), false, false);
						
						$addNoteResult = $gvMan->addNewNote($gvNote);
						
						if ($gvNote->id == -1) {
							$crmRequestResult = XFORMS_CRM_EXCEPTION_FAILED_DIRECT_SUBMISSION;
							mail(
								GENERIC_FORMS_ERROR_EMAIL_ADDRESS, 
								"CRM Service request failed", 
								"There was an error raising a service request. Debugging details below:\r\n" . print_r($gvNote, true), 
								"From: support@jadu.co.uk\nMIME-Version: 1.0\nX-Priority: 1\n"
							);
						}
						else {
							$crmRequestResult = XFORMS_CRM_EXCEPTION_SUCCESSFUL_DIRECT_SUBMISSION;
						}
						
						newXFormsCRMSubmissionLog ($userForm->id, TO_CRM_FORM_SUBMISSION, $crmRequestResult, print_r($gvNote, true), $gvNote->id);
					}

*/
/*
					//	Kettering Borough Council style LG45 interation
					//	Create XML and send through to CRM system
					if (XFORMS_CRM_INTEGRATION_ENABLED && mb_strpos($form->action, "crm") !== false) {
						$allUserFormAnswers = getAllXFormsQuestionAnswersForForm($userForm->id);
						$crmResult = generateXMLForCRM ($form, $user, getXFormsUserForm($userForm->id), $allUserFormAnswers, false, false);
						newXFormsCRMSubmissionLog ($userForm->id, TO_CRM_FORM_SUBMISSION, $crmResult, '', '');
						$userForm = extendXFormsUserFormWithCRMSubmissionDetailsAvailable($userForm);
					}
*/

/*					
					//	Newport City Council style LG45 interation
					if (XFORMS_CRM_INTEGRATION_ENABLED && mb_strpos($form->action, 'crm') !== false && $crmConnection !== false) {
						$numberOfCRMQuestions = 20;
						$crmServiceRequestUPRN = '';
						
						$branchingRoute = determineXFormsFormBranchingRouteFromUserPath($form->id, $pagePath);
						
						//	What service Request Type is assigned within the Schema mappings
						$crmSchemaElement = getXFormsCRMSchemaElementBySchemaDetails($form->id, $branchingRoute->id, 'requestType');
						$crmSchemaElementMappings = getAllXFormsQuestionToCRMSchemaElementMappingForSchemaElement($crmSchemaElement->id);
						$crmSchemaElementMapping = $crmSchemaElementMappings[0];
						
						if ($crmSchemaElementMapping->serviceRequestTypeID > 0) {
							$crmServiceRequestType = getXFormsCRMServiceRequestType($crmSchemaElementMapping->serviceRequestTypeID);
							
							//	Get hold of the UPRN from within the form

//	There are multiple in the schema though - this needs looking at to make more robust
							$crmSchemaElement = getXFormsCRMSchemaElementBySchemaDetails($form->id, $branchingRoute->id, 'uprn');
							$crmSchemaElementMappings = getAllXFormsQuestionToCRMSchemaElementMappingForSchemaElement($crmSchemaElement->id);
							$crmSchemaElementMapping = $crmSchemaElementMappings[0];
//  ===							
							if ($crmSchemaElementMapping->questionID > 0) {
								$crmSchemaElementMappingQuestion = getXFormsFormQuestion($crmSchemaElementMapping->questionID);
								if ($crmSchemaElementMappingQuestion->id > 0) {
									$crmSchemaElementMappingQuestionAnswer = getQuestionAnswerIfExists($crmSchemaElementMappingQuestion->formPageID, $userForm->id, $crmSchemaElementMappingQuestion->id, $crmSchemaElementMapping->elementID, $crmSchemaElementMapping->elementNumber);

									//	hack for now due to multiple mentioned above
									$crmServiceRequestUPRN = $crmSchemaElementMappingQuestionAnswer->answer;
								}
							}
							else if (mb_strlen($crmSchemaElementMapping->staticText) > 0) {
								$crmServiceRequestUPRN = $crmSchemaElementMapping->staticText;							
							}
							
							$crmServiceRequestType = getServiceRequestTypeFromCRM($crmConnection, $crmServiceRequestType->requestType);
							$crmServiceRequestTypeSeverity = getServiceRequestsSeverityDetails($crmConnection, $crmServiceRequestType->severity);
							
//							$crmServiceRequestPartyID = getPartyWithPropertyFromPartiesCRM($crmConnection, $crmServiceRequestUPRN);
							$crmServiceRequestLocationID = getLocationWithPropertyFromCRM($crmConnection, $crmServiceRequestUPRN);
														
//	Need some else error reporting / email / logging
//							if ($crmServiceRequestPartyID != '') {
							if ($crmServiceRequestLocationID != '') {
								
								//	Now for all the question stuff 
								$crmServiceRequestionQuestionAttributes1 = getServiceRequestsDefaultQuestionsFromCRMForServiceRequestType($crmConnection, $crmServiceRequestType->id);
								$crmServiceRequestionQuestionAttributes2 = getServiceRequestsQuestionsFromCRMForServiceRequestType($crmConnection, $crmServiceRequestType->id);
								$crmServiceRequestionQuestionAttributes = array_merge($crmServiceRequestionQuestionAttributes1, $crmServiceRequestionQuestionAttributes2);
								$crmServiceRequestionQuestionAttributes = array_pad($crmServiceRequestionQuestionAttributes, $numberOfCRMQuestions, new CRMServiceRequestQuestion());
								
								//	Get all the question mappings
								$crmServiceRequestQuestions = array();
								$crmServiceRequestQuestionAnswers = array();
								
								$crmQuestionSchemaElements = getXFormsCRMSchemaElementBySchemaElementNameLike($form->id, $branchingRoute->id, 'atr_%');
								
								//	Sort them to ensure in correct order in terms of 'N' in atr_N
								$sortedCRMQuestionSchemaElements = array();
								foreach($crmQuestionSchemaElements as $crmQuestionSchemaElement) {
									$crmQuestionSchemaElementNumber = mb_substr($crmQuestionSchemaElement->schemaElementName, 4);  //	4 = length of 'atr_'
									$sortedCRMQuestionSchemaElements[$crmQuestionSchemaElementNumber-1] = $crmQuestionSchemaElement;
								}
								ksort($sortedCRMQuestionSchemaElements, SORT_NUMERIC);
								$sortedCRMQuestionSchemaElements = array_pad($sortedCRMQuestionSchemaElements, $numberOfCRMQuestions, '');
								
								foreach ($sortedCRMQuestionSchemaElements as $questionSchemaElementIndex => $questionSchemaElement) {
									$questionSchemaElementMappings = getAllXFormsQuestionToCRMSchemaElementMappingForSchemaElement($questionSchemaElement->id);
									$questionSchemaElementMapping = $questionSchemaElementMappings[0];
									
									if ($questionSchemaElementMapping->questionID > 0) {
										$xformsServiceRequestQuestion = getXFormsFormQuestion($questionSchemaElementMapping->questionID);
										if ($xformsServiceRequestQuestion->id > 0) {
											$crmServiceRequestQuestion = getServiceRequestQuestionDescription($crmConnection, $crmServiceRequestionQuestionAttributes[$questionSchemaElementIndex]);
											$crmServiceRequestQuestions[$questionSchemaElementIndex] = $crmServiceRequestQuestion->description;
											
											$questionAnswer = getQuestionAnswerIfExists($xformsServiceRequestQuestion->formPageID, $userForm->id, $questionSchemaElementMapping->questionID, $questionSchemaElementMapping->elementID, $questionSchemaElementMapping->elementNumber);
											$crmServiceRequestQuestionAnswers[] = $questionAnswer->answer;
										}
									}
									else if (mb_strlen($questionSchemaElementMapping->staticText) > 0) {
										$crmServiceRequestQuestion = getServiceRequestQuestionDescription($crmConnection, $crmServiceRequestionQuestionAttributes[$questionSchemaElementIndex]);
										$crmServiceRequestQuestions[$questionSchemaElementIndex] = $crmServiceRequestQuestion->description;
										$crmServiceRequestQuestionAnswers[$questionSchemaElementIndex] = $questionSchemaElementMapping->staticText;
									}
									else {
										$crmServiceRequestQuestions[$questionSchemaElementIndex] = '';
										$crmServiceRequestQuestionAnswers[$questionSchemaElementIndex] = '';
									}
								}
																
								//	Now raise the service request and log the relevant details
								$crmSubmissionLogNotes = array(
									'uprn'			=> $crmServiceRequestUPRN,
//									'partyID'		=> $crmServiceRequestPartyID, 
//									'contactID'		=> $crmServiceRequestContactID,
									'locationID'	=> $crmServiceRequestLocationID,
									'requestType'	=> $crmServiceRequestType, 
									'questions'		=> $crmServiceRequestQuestions, 
									'answers'		=> $crmServiceRequestQuestionAnswers, 
									'attributes'	=> $crmServiceRequestionQuestionAttributes
								);
								
								$crmServiceRequestReferenceNumber = newServiceRequest($crmConnection, $crmServiceRequestLocationID, $crmServiceRequestType->name, $crmServiceRequestQuestions, $crmServiceRequestQuestionAnswers, $crmServiceRequestionQuestionAttributes);
								if ($crmServiceRequestReferenceNumber == XFORMS_CRM_EXCEPTION_FAILED_DIRECT_SUBMISSION_CODE || $crmServiceRequestReferenceNumber == '') {
									$crmServiceRequestResult = XFORMS_CRM_EXCEPTION_FAILED_DIRECT_SUBMISSION;
									mail(
										GENERIC_FORMS_ERROR_EMAIL_ADDRESS, 
										"CRM Service request failed", 
										"There was an error raising a service request. Debugging details below:\r\n" . print_r($crmSubmissionLogNotes, true), 
										"From: support@jadu.co.uk\nMIME-Version: 1.0\nX-Priority: 1\n"
									);
								}
								else {
									$crmServiceRequestResult = XFORMS_CRM_EXCEPTION_SUCCESSFUL_DIRECT_SUBMISSION;
									$webReferenceNumber = $crmServiceRequestReferenceNumber;
								}
								
								newXFormsCRMSubmissionLog($userForm->id, TO_CRM_FORM_SUBMISSION, $crmServiceRequestResult, print_r($crmSubmissionLogNotes, true), $crmServiceRequestReferenceNumber);
							}
						}
						$userForm = extendXFormsUserFormWithCRMSubmissionDetailsAvailable($userForm);
					}
*/


					//	Deal with any ePayment mappings that have become redundant since changes to the users answers / route with a form that is now being completed as a non-payment integrated form due to a different branch
					if (XFORMS_EPAYMENTS_INTEGRATION_ENABLED && mb_strpos($form->action, 'epayments') !== false) {
						$epaymentMapping = null;
						$xformMapping = getXFormsUserFormToEpaymentsOrderItem('userFormID', $userForm->id);
						if ($xformMapping != -1) {
							$orderItem = getOrderItem($xformMapping->orderItemID);
							if ($orderItem->id != -1) {
								$branchingRoute = determineXFormsFormBranchingRouteFromUserPath($form->id, $userForm->pagePath);
								if ($branchingRoute != -1) {
									$epaymentMapping = getXFormsFormEpaymentsIntegration('routeID', $branchingRoute->id, true);
									if ($epaymentMapping->id != -1) {
										//	if the route dont match then delete the mapping?
										if ($epaymentMapping->productID != $orderItem->productID) {
											$xformMapping->deleteXFormsUserFormToEpaymentsOrderItem();
											deleteOrderItem($orderItem->id);
											unset($xformMapping);
										}
									}
								}
							}
							
							//	should we update the userId details if they joined as a part of this?
							if ($autoRegistrationComplete === true && $user->id != -1) {
								updateOrderUserID($orderItem->orderID, $user->id);
							}
						}
					}
					
					//	Create any (X)FDF files as required.
					if (XFORMS_PDF_GENERATION_ENABLED && mb_strpos($form->action, 'pdf') !== false) {
						$branchingRoute = determineXFormsFormBranchingRouteFromUserPath($form->id, $userForm->pagePath);
						if ($branchingRoute != -1) {
							$allPDFFormMappings = getAllXFormsPDFFormToBranchingRouteMapping('routeID', $branchingRoute->id, true); // only those that are set to live
							if (sizeof($allPDFFormMappings) > 0) {
								
								//	Should only ever be one, but loop to make sure.
								foreach ($allPDFFormMappings as $pdfFormMapping) {
									//	Generate the FDF file
									$pdfForm = getXFormsPDFForm($pdfFormMapping->pdfFormID, false); // false is for only show on site - i.e. download
									$pdfFormException = parseAllPDFFormFieldsForFile(XFORMS_PDF_FORMS_DIRECTORY . $pdfForm->pdfFilename . '.xml');
									$pdfForm->formFields = $allFormFields;
									
									$allFieldMappings = getAllXFormsToPDFFormFieldMappings($pdfFormMapping->id);
									if (sizeof($allFieldMappings) > 0 && sizeof($pdfForm->formFields) > 0) {
										$fdfFieldValuePairs = calculateAllFieldValuePairs ($form, $userForm, $user, $allFieldMappings, $pdfForm);
										$fdfFilename = XFORMS_RECEIVED_PDF_FORMS_DIRECTORY . 'JADU_' . date('Y-m-d') . '_' . $userForm->id;
										$fdfFilenameExtension = '.' . mb_strtolower(XFORMS_PDF_FORM_OUTPUT_FDF);
										createXFormsPDFUserFormFile(XFORMS_PDF_FORMS_DIRECTORY.$pdfForm->pdfFilename, $fdfFilename.$fdfFilenameExtension, $fdfFieldValuePairs, XFORMS_PDF_FORM_OUTPUT_FDF, false);
										
										//	Merge the original with the FDF File also
										//	java merge_fdf -f test.pdf test_out.pdf test.fdf
										$java = 'java -classpath ' . JAVA_CLASSPATH . ' JaduXFormsPDFFormFDFMerge -f ' . XFORMS_PDF_FORMS_DIRECTORY.$pdfForm->pdfFilename . ' ' . $fdfFilename.'.pdf' . ' ' . $fdfFilename.$fdfFilenameExtension;
										
										exec($java, $javaOutput);
										if ($javaOutput != 'Done.') {
											$message = 'Form: '.$form->title."\r\n";
											$message .= 'Form ID: '.$form->id."\r\n\r\n";
											$message .= "Stack Trace:\r\n";
											$message .= print_r($javaOutput, true);
											$headers = 'From: '.DEFAULT_EMAIL_ADDRESS . "\r\n";
											mail(GENERIC_FORMS_ERROR_EMAIL_ADDRESS, 'Failed to create user form pdf on '.DOMAIN, $message);
										}
										else if (class_exists('Jadu_ClusterSync')) {
											$clusterSync->addPath('xfp-form-pdf-generation', new Jadu_ClusterSync_Path('#^'. XFORMS_PDF_FORMS_DIRECTORY . '.*$#i', true, true));
											Jadu_ClusterSync::write($fdfFilename.'.pdf');
											Jadu_ClusterSync::write($fdfFilename.'.fdf');
										}
									}
								}
							}
						}
					}
					
					if (mb_strpos($form->action, 'fileOutput') !== false) {
						include_once('xforms2/JaduXFormsConnectors.php');
						include_once('xforms2/JaduXFormsFormFileOutput.php');
						include_once('xforms2/JaduXFormsFormFileOutputOptions.php');
						include_once('xforms2/JaduXFormsFormFileOutputGenerator.php');

						$formToConnector = getXFormsFormToConnectorForForm($form->id);
						$connector = getXFormsConnector($formToConnector->connectorID);
						$connectorSettings = getAllXFormsConnectorSettingsForConnector($formToConnector->connectorID);
						foreach($connectorSettings as $setting) {
							$field = getXFormsConnectorField($setting->fieldID);
							$settings[$field->title] = $setting->value;
						}
						$connectorType = getXFormsConnectorType($connector->typeID);
						$fileOutput = getXFormsFormFileOutputByFormID($form->id, true);
						
						if ($fileOutput->id != -1) { // Check if file output settings are live
							$fileOutputGenerator = new XFormsFormFileOutputGenerator($form, $userForm, $user);
							
							// Add any user uploaded files to the data array
							$data = array();
							foreach ($fileOutputGenerator->userFormAnswers as $userAnswer) {
								$answerArray = unserialize($userAnswer->answer);
								if ($answerArray != false && is_array($answerArray) && isset($answerArray['system_name'])) {
									$data[] = XFORMS_USER_FORM_FILE_UPLOAD_DESTINATION_DIRECTORY.'/'.$answerArray['system_name'];
								}
							}
							
							// Set formatOptions array depending on output type
							if ($fileOutput->exportMode == 'XML') {
								$extension = '.xml';
								$formatOptions['xmlTemplate'] = $fileOutput->xmlTemplate;
								$formatOptions['xmlField'] = $fileOutput->xmlField;					
							}
							else if ($fileOutput->exportMode == 'CSV') {
								$extension = '.csv';
								$formatOptions['csvFieldBoundary'] = $fileOutput->csvFieldBoundary;
								$formatOptions['csvLineBoundary'] = $fileOutput->csvLineBoundary;
								$formatOptions['csvHeader'] = $fileOutput->csvHeader;
							}
							
							// Generate Output, save output contents
							$output = $fileOutputGenerator->generateOutput($fileOutput->exportMode, $formatOptions);
							$filename = $form->id.$webReferenceNumber.$userForm->sequenceID.$extension;
							file_put_contents(XFORMS_FORM_FILE_OUTPUT_DIRECTORY.$filename, $output);
							$data[] = XFORMS_FORM_FILE_OUTPUT_DIRECTORY.$filename;

							// Include the connector adapotor and call the send method
							include_once($connectorType->classFile);
							$xformsConnector = new $connectorType->class($form->id, $webReferenceNumber, $userForm->sequenceID);						
							$xformsConnector->send($data, $settings);
						}
					}
					
					//	Send any email alerts that have been configured
					$allEmailAlerts	= getAllXFormsFormEmailAlertForFormID($form->id);
					foreach ($allEmailAlerts as $emailAlert) {
                        $emailAlertFile = null;
						$userFormOutput = new XFormsUserFormOutputGenerator($form, $userForm, $user, $emailAlert);

						$emailAlertTo = $userFormOutput->toEmailAddress;
						if ($emailAlertTo != '') {
	                        //  Deal with the main Email Alert configured attachment
							if ($emailAlert->attachmentFormat != XFORMS_USER_FORM_OUTPUT_FORMAT_NONE) {
								$userFormAttachmentOutput = new XFormsUserFormOutputGenerator($form, $userForm, $user, '',  $emailAlert->attachmentFormat);

								if ($emailAlert->attachmentSameMessage == XFORMS_FORM_RECEIPT_ATTACHMENT_MESSAGE_GENERATED_PDF_ENABLED) {
							        if (file_exists($fdfFilename . '.pdf')) {
										$fdfFileHandle = fopen($fdfFilename . '.pdf', "rb");
										$emailAlertAttachmentContent = fread($fdfFileHandle, filesize($fdfFilename . '.pdf'));
										fclose($fdfFileHandle);
									}
								}
								else {
									$emailAlertAttachmentBody = $emailAlert->attachmentMessage;
									if ($emailAlert->attachmentSameMessage == XFORMS_FORM_RECEIPT_ATTACHMENT_SAME_MESSAGE) {
										$emailAlertAttachmentBody = $emailAlert->emailMessage;
									}
									$emailAlertAttachmentContent = $userFormAttachmentOutput->generateOutput($emailAlertAttachmentBody, $emailAlert->attachmentFormat);
								}

								if ($emailAlertAttachmentContent != '') {
									$emailAlertFile = array(
										'mimeType'	=> $userFormAttachmentOutput->getMimeTypeForOutputFormat($emailAlert->attachmentFormat), 
										'filename'	=> 'jadu_form_' . $userForm->id . '.' . mb_strtolower($emailAlert->attachmentFormat), 
										'content'	=> $emailAlertAttachmentContent
									);
	                                unset($emailAlertAttachmentContent);
								}
								else {
	                                mail(
										GENERIC_FORMS_ERROR_EMAIL_ADDRESS, 
										DOMAIN . " Email Alert Attachment empty error", 
										"There was an error with attaching a file to an email alert in relation to Web Reference: " . $userForm->id . ".", 
										"From: support@jadu.co.uk\nMIME-Version: 1.0\nX-Priority: 1\n"
									);
								}
	                        }

	                        $userFormOutput->sendMultipartEmail($emailAlertTo, $emailAlertFile);							
						}

					}
}
else {
	include_once('../../404.php');
}					
?>