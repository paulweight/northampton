<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | Form archive | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="forms, form, application, archive, archives, <?php print encodeHtml($form->title);?>, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> online user forms archive" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> form archive - <?php print encodeHtml($form->title);?>" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> online user forms archive" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
						
	<table summary="Name of the form with its reference number and status">
		<tr>
			<td><strong>Form:</strong></td>
			<td><?php print encodeHtml($form->title); ?></td>
		</tr>
		<tr>
			<td><strong>Completed:</strong></td>
			<td><?php print $userForm->getDateFormatted();?></td>
		</tr>
		<tr>
			<td><strong>Status:</strong></td>
			<td>
<?php
				if ($userForm->status == XFORMS_USER_FORM_PENDING_STATE) print "Pending";
				else if ($userForm->status == XFORMS_USER_FORM_PROGRESSING_STATE) print "In Progress";
				else if ($userForm->status == XFORMS_USER_FORM_COMPLETED_APPROVED_STATE) print "Completed - Approved";
				else if ($userForm->status == XFORMS_USER_FORM_COMPLETED_DECLINED_STATE) print "Completed - Declined";
				else if ($userForm->status == XFORMS_USER_FORM_TERMINATED_STATE) print "Terminated";
?>
			</td>
		</tr>
	</table>

	<table>
<?php
	foreach ($allAnswers as $answer) {
?>
		<tr>
			<td>
<?php
				print encodeHtml($answer->question);
				if ($answer->elementNumber != -1) {
					print " <em>(".($answer->elementNumber+1).")</em>";
				}
?>
				
			</td>
			<td>
<?php
				if ($answer->answer == "")
					print "&nbsp;";
				else
					print nl2br(encodeHtml($answer->answer));
?>
			</td>
		</tr>
<?php
	}
?>
	</table>
						
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>