<?php
	include_once("utilities/JaduStatus.php");
	include_once("egov/JaduXFormsForm.php");
	include_once("utilities/JaduReadableURLs.php");

	$searchText = trim($_REQUEST['searchText']);
	$found = false;
	
	if (!empty($searchText)) {
		$xforms = getAllXFormsForms(true, true);
?>
	<ul class="list icons forms">
<?php

		foreach ($xforms as $form) {
			if (mb_strpos(mb_strtolower($form->title), mb_strtolower($searchText)) !== false) {
				$found = true;
?>
		<li>
			<a href="<?php print ((defined('SSL_ENABLED') && SSL_ENABLED) ? getSecureSiteRootURL() : getSiteRootURL()) . buildXFormsURL($form->id); ?>"><?php print encodeHtml($form->title); ?></a> 
<?php
		if (!Jadu_Service_User::getInstance()->isSessionLoggedIn() && $form->allowUnregistered == 0) {
?>
			<img src="/site/images/icon_lock.gif" alt="Padlock graphic"  />
<?php
		}
?>
		</li>
<?php
			}
		}
		
		if (!$found) {
?>
		<li>No forms for <?php print encodeHtml($searchText); ?> found</li>
<?php
		}
?>
	</ul>
<?php
	}
?>