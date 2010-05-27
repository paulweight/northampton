<?php
	include_once("xforms2/JaduXFormsForm.php");
	include_once("xforms2/JaduXFormsFormPage.php");
	include_once("xforms2/JaduXFormsFormInterfaceFunctions.php");
	include_once("xforms2/JaduXFormsUserForms.php");
	include_once("xforms2/JaduXFormsUserQuestionAnswers.php");
	include_once("xforms2/JaduXFormsFormBranchingRules.php");
	include_once("xforms2/JaduXFormsFormBranchingRoutes.php");
?>
	
	<table id="xform_confirmation_table" class="confirmation" summary="Confirmation of your form details">
		<thead>
			<tr>
				<th>Question</th>
				<th>Your reply</th>
			</tr>
		</thead>
		<tbody>
<?php 
		//	Clear out any answers that should not be kept because the user altered their path mid-form.
		if (sizeof($allFormBranchingRules) > 0) {
			$allRequiredAnswersForPath = array();
			$pagePathArray = explode(',', $userForm->pagePath);

			foreach ($pagePathArray as $pagePathNumber) {
				$previouslyVisitedPage = getXFormsFormPageFromPageNumber($form->id, $pagePathNumber);
				$pageSuppliedAnswers = getAllXFormsQuestionAnswersForFormPage($previouslyVisitedPage->id, $userForm->id);
				foreach ($pageSuppliedAnswers as $suppliedAnswer) {
					$allRequiredAnswersForPath[] = $suppliedAnswer->id;
				}
			}
			
			$allSuppliedAnswers = getAllXFormsQuestionAnswersForForm($userForm->id);
			foreach ($allSuppliedAnswers as $suppliedAnswer) {
				if (!in_array($suppliedAnswer->id, $allRequiredAnswersForPath)) { 
					deleteXFormsQuestionAnswer($suppliedAnswer->id);
				}
			}
		}
		
		//	Display all the correct users inputs back to them
		$sameQuestionCounter = false;
		
		$allUserFormAnswers = getAllXFormsQuestionAnswersForForm($userForm->id);
		$lastIntegratedComponentQuestion = -1;
		foreach ($allUserFormAnswers as $index => $answer) {
			$displayAnswer = true;
			
			$question = getXFormsFormQuestion($answer->questionID);
			if ($question != null) {
				$component = getXFormsFormComponent($question->componentID);
				if ($component->isCalculation == 1 && $question->calculationVisible == XFORMS_FORM_QUESTIONS_CALCULATION_NOT_VISIBLE) {
					$displayAnswer = false;
				}
			}
			
			if ($displayAnswer === true && $lastIntegratedComponentQuestion != $question->id) {
				$previousAnswer = $allUserFormAnswers[$index-1];

				if ($question != null && $component->isConglomerate == 1) {
					//	Print a conglomerates question on its own row
					if ($previousAnswer->questionID != $answer->questionID) {
?>
			<tr>
				<td colspan="2"><?php print $question->question;?></td>
			</tr>
<?php				
					}
				}
?>
			<tr>
				<td>
<?php
				if ($answer->question != $previousAnswer->question) {
					if ($question->id > 0) {
						if ($component->isConglomerate == 1 && $answer->elementNumber != -1) {
							$conglomerateElement = getXFormsFormConglomerateElement($answer->elementID);
							print '<em>('.($answer->elementNumber+1).')</em> ' . $conglomerateElement->label;
						}
						else {
							print $question->question;
						}
					}
					else {
						print ucfirst(str_replace('_', ' ', $answer->question));
					}
				}
?>
				</td>
				<td class="coltwo">
<?php
				if ($answer->answer == '') {
					print "&nbsp;";
				}
				else {
					if ($question != null && $component->htmlVersion == 'file') {
						$answerArray = unserialize($answer->answer);
						if (is_array($answerArray)) {
							$answer->answer = $answerArray['name'];
						}
					}
					print nl2br($answer->answer);
				}
?>
				</td>
			</tr>
<?php
			}
			if ($question != null && $component->integratedComponentID > 0) {
				$lastIntegratedComponentQuestion = $question->id;
			}
		}
?>
		</tbody>
	</table>
	