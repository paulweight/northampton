<?php
	session_start();
	include_once("xforms2/JaduXFormsForm.php");

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
			<a href="<?php print $SECURE_SERVER;?>/site/scripts/xforms_form.php?formID=<?php print $form->id;?>" title="<?php print $form->title;?>"><?php print $form->title;?></a> 
<?php
				if (!isset($_SESSION['userID']) && $form->allowUnregistered == 0) {
?>
			<img src="http://<?php print $DOMAIN;?>/site/images/icon_lock.gif"  alt="Padlock graphic" title="You must sign-in to use this form" /> <em>Requires sign-in</em>
<?php
				}
?>
		</li>
<?php
			}
		}
?>
	</ul>
<?php
		if (!$found) {
?>
	<ul>
		<li>No forms for <?php print $searchText; ?> found</li>
	</ul>
<?php
		}
	}
	
	print '|' . $_REQUEST['seq'];
?>
