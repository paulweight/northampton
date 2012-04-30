<?php
	if (basename($_SERVER['PHP_SELF']) == 'xforms_form.php' || basename($_SERVER['PHP_SELF']) == 'xforms_internal_form.php' || basename($_SERVER['PHP_SELF']) == 'user_form_info.php' || basename($_SERVER['PHP_SELF']) == 'payments_basket.php') {
		
		include_once("xforms2/JaduXFormsForm.php");
		include_once("xforms2/JaduXFormsFormPage.php");
		include_once("xforms2/JaduXFormsFormInterfaceFunctions.php");
		include_once("xforms2/JaduXFormsUserForms.php");
		include_once("xforms2/JaduXFormsUserQuestionAnswers.php");
		include_once("xforms2/JaduXFormsFormBranchingRules.php");
		include_once("xforms2/JaduXFormsFormBranchingRoutes.php");
		
		//	Clear out any answers that should not be kept because the user altered their path mid-form.
		if (sizeof($allFormBranchingRules) > 0) {
			$pagePathIDs = array();
			$pagePathArray = explode(',', $userForm->pagePath);
			
			foreach ($pagePathArray as $pagePathNumber) {
				$formPage = getXFormsFormPageFromPageNumber($form->id, $pagePathNumber);
				$pagePathIDs[] = $formPage->id;
			}
			
			$allUserFormAnswers = getAllXFormsQuestionAnswersForForm($userForm->id);
			$removedUserFormAnswers = false;
			foreach ($allUserFormAnswers as $suppliedAnswer) {
				if (!in_array($suppliedAnswer->formPageID, $pagePathIDs)) {
					deleteXFormsQuestionAnswer($suppliedAnswer->id);
					$removedUserFormAnswers = true;
				}
			}
			
			if ($removedUserFormAnswers === true) {
				$allUserFormAnswers = getAllXFormsQuestionAnswersForForm($userForm->id);
			}
		}
		else {
			$allUserFormAnswers = getAllXFormsQuestionAnswersForForm($userForm->id);
		}
?>
	<table id="xform_confirmation_table" class="confirmation" summary="Confirmation of your form details">
		<thead>
			<tr>
				<th><?php print LANG_XFORMS_CONFIRMATION_ANSWER_QUESTION_TABLE_HEADING_QUESTION; ?></th>
				<th><?php print LANG_XFORMS_CONFIRMATION_ANSWER_QUESTION_TABLE_HEADING_ANSWER; ?></th>
			</tr>
		</thead>
		<tbody>
<?php 
		//	Display all the correct users inputs back to them.
		foreach ($allUserFormAnswers as $index => $answer) {
			$displayAnswer = true;
			
			$question = getXFormsFormQuestion($answer->questionID);
			if ($question != null) {
				$component = getXFormsFormComponent($question->componentID);
				if ($component->isCalculation == 1 && $question->calculationVisible == XFORMS_FORM_QUESTIONS_CALCULATION_NOT_VISIBLE) {
					$displayAnswer = false;
				}
				else if ($component->integratedComponentID > 0) {
					$integratedComponent = getXFormsFormIntegratedComponent($component->integratedComponentID);
					if ($integratedComponent->dataRetraceFunction != '') {
						if ($answer->elementNumber != XFORMS_FORM_INTEGRATED_COMPONENT_ELEMENT_RETRACED) {
							$displayAnswer = false;
						}
					}
					else {
						if ($answer->elementNumber == XFORMS_FORM_INTEGRATED_COMPONENT_ELEMENT_RETRACED) {
							$displayAnswer = false;
						}
					}
				}
				else if ($component->isConglomerate) {
					$conglomerateElement = getXFormsFormConglomerateElement($answer->elementID);
					$conglomerateComponent = getXFormsFormComponent($conglomerateElement->componentID);
					if ($conglomerateComponent->isCalculation == 1 && $question->calculationVisible == XFORMS_FORM_QUESTIONS_CALCULATION_NOT_VISIBLE) {
						$displayAnswer = false;
					}
				}
				else if ($component->htmlVersion == 'hidden') {
					$displayAnswer = false;
				}
			}
			else {
				$displayAnswer = false;
			}
			
			if ($displayAnswer === true) {
				$previousAnswer = $allUserFormAnswers[$index-1];
				
				if ($component->isConglomerate == 1) {
					//	Print a conglomerates question on its own row
					if ($previousAnswer->questionID != $answer->questionID) {
?>
			<tr>
				<td colspan="2"><?php print encodeHTML($question->question);?></td>
			</tr>
<?php				
					}
				}
?>
			<tr>
				<td>
<?php
				if ($answer->question != $previousAnswer->question || $lastDisplayedAnswer->question != $answer->question) {
					if ($question->id > 0) {
						if ($component->isConglomerate == 1 && $answer->elementNumber != -1) {
							$conglomerateElement = getXFormsFormConglomerateElement($answer->elementID);
							$conglomerate = getXFormsFormConglomerateForQuestion($question->id);
							if ($conglomerate->rows > 1) {
								print '<em>('.($answer->elementNumber+1).')</em> ';
							}
							print encodeHTML($conglomerateElement->label);
							
							$conglomerateElementComponent = getXFormsFormComponent($conglomerateElement->componentID);
							if ($conglomerateElementComponent->htmlVersion == 'password') {
								$answer->answer = nl2br(encodeHtml(str_repeat("*",strlen($answer->answer))));
							}
						}
						else {
							print encodeHTML($question->question);
						}
					}
					else {
						print ucfirst(str_replace('_', ' ', encodeHTML($answer->question)));
					}
				}
?>
				</td>
				<td class="coltwo">
<?php
				if ($answer->answer == '') {
					print '&nbsp;';
				}
				else {
					if ($component->htmlVersion == 'file') {
						$answerArray = unserialize($answer->answer);
						if (is_array($answerArray)) {
							print nl2br(encodeHTML($answerArray['name']));
						}
					}
					else if ($component->htmlVersion == 'password') {
						print nl2br(encodeHtml(str_repeat("*",strlen($answer->answer))));
					}
					
					//	Pretty print integrated component retrace functions if possible
					else if ($component->integratedComponentID > 0) {
						$integratedComponent = getXFormsFormIntegratedComponent($component->integratedComponentID);
						if ($integratedComponent->dataRetraceFunction != '') {
							if ($answer->elementNumber == XFORMS_FORM_INTEGRATED_COMPONENT_ELEMENT_RETRACED) {
								print nl2br(encodeHTML($answer->answer));
							}
						}
						else {
							print nl2br(encodeHTML($answer->answer));
						}
					}
					else if ($component->isConglomerate) {
						$element = getXFormsFormConglomerateElement($answer->elementID);
						$component = getXFormsFormComponent($element->componentID);
						if ($component->htmlVersion == 'file') {
							$answerArray = unserialize($answer->answer);
							if (is_array($answerArray)) {
								print nl2br(encodeHTML($answerArray['name']));
							}
						}
						else if ($component->multipleOptions) {
							$questionOption = getXFormsFormConglomerateElementOptionForQuestionAndAnswer($question->id, $answer->answer);
							print nl2br(encodeHTML($questionOption->label));
						}
						else {
							print nl2br(encodeHTML($answer->answer));
						}
					}
					else if ($component->multipleOptions) {
						$questionOption = getXFormsFormQuestionOptionForQuestionAndAnswer($question->id, $answer->answer);
						print nl2br(encodeHTML($questionOption->label));
					}
					else {
						print nl2br(encodeHTML($answer->answer));
					}
				}
				
				$lastDisplayedAnswer = $answer;
?>
				</td>
			</tr>
<?php
			}
		}
?>
		</tbody>
	</table>
<?php
	}
	else {
		include_once('../../404.php');
	}
?>