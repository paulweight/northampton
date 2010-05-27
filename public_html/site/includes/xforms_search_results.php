<?php
	session_start();
	include_once("egov/JaduXFormsForm.php");

	$searchText = trim($_REQUEST['searchText']);
	$found = false;
	
	if (!empty($searchText)) {
		$xforms = getAllXFormsForms(true, true);
?>
	<ul class="list">
<?php

		foreach ($xforms as $form) {
			if (strpos(strtolower($form->title), strtolower($searchText)) !== false) {
				$found = true;
?>
		<li>
			<a href="http://<?php print $DOMAIN;?>/site/scripts/xforms_form.php?formID=<?php print $form->id;?>" title="<?php print $form->title;?>"><?php print $form->title;?></a> 
<?php
				if (!isset($_SESSION['userID']) && $form->allowUnregistered == 0) {
?>
			<img src="/site/images/icon_lock.gif" alt="Padlock graphic" title="You must sign-in to use this form" />
<?php
				}
?>
		</li>
<?php
			}
		}
		
		if (!$found) {
?>
		<li>No forms for <?php print $searchText; ?> found</li>
<?php
		}
?>
	</ul>
<?php
	}
	
	print '|' . $_REQUEST['seq'];
?>
