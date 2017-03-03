<script src="<?php echo getStaticContentRootURL(); ?>/site/javascript/almond.min.js"></script>
<script src="<?php echo getStaticContentRootURL(); ?>/site/javascript/util.min.js"></script>
<?php
$script = isset($script) ? $script : basename($_SERVER['SCRIPT_NAME']);
$includeMaps = isset($includeMaps) ? $includeMaps : null;

if (isset($homepage) && file_exists(MAIN_HOME_DIR . 'var/widgets/js/widget.js') && filesize(MAIN_HOME_DIR . 'var/widgets/js/widget.js') != 0) {
?>
<script src="<?php echo getURLToWidgetJavascriptFile(); ?>"></script>
<?php

}
if ($script == 'az_home.php' || $script == 'az_index.php' || $script == 'services_info.php' || $script == 'xforms_index.php') {
?>
<script src="<?php echo getStaticContentRootURL(); ?>/site/javascript/livesearch.js"></script>
<?php

}

$mapScripts = [
'az_home.php',
'az_index.php',
'services_info.php',
'directory_search.php',
'directory_record_edit.php',
'directory_submit.php',
];

if (in_array($script, $mapScripts) || $includeMaps) {
require_once 'maps_javascript.php';

$mapDirectory = 'maps_osm';
if (defined('FRONTEND_USE_GOOGLE') && FRONTEND_USE_GOOGLE && (GOOGLE_MAPS_API_KEY !== '')) {
		$mapDirectory = 'maps_google';
}

switch ($script) {
		case 'directory_record.php':
				print '<script src="' . getStaticContentRootURL() . '/site/javascript/' . $mapDirectory . '/directory_record.js"></script>';
				break;
		case 'directory_search.php':
				print '<script src="' . getStaticContentRootURL() . '/site/javascript/' . $mapDirectory . '/directory_search.js"></script>';
				break;
		case 'directory_record_edit.php':
		case 'directory_submit.php':
				print '<script src="' . getStaticContentRootURL() . '/site/javascript/' . $mapDirectory . '/directory_submit.js"></script>';
				break;
		case 'az_home.php':
		case 'az_index.php':
		case 'services_info.php':
				print '<script src="' . getStaticContentRootURL() . '/site/javascript/' . $mapDirectory . '/services.js"></script>';
				break;
}
}
if ($script == 'event_new.php') {
?>
<script>
<!--
		function preSubmit() {
				document.getElementById('auth').value = '<?php echo md5(DOMAIN . date('Y')); ?>';
		}
-->
</script>
<?php

}
if ($script == 'event_new.php') {
?>
<script>
<!--
		function toggleUntilInput(value) {
				if (value == "1day") {
						document.getElementById('untilInput').style.display = 'none';
				}
				else {
						document.getElementById('untilInput').style.display = 'block';
						document.getElementById('endDate').focus();
				}
		}
-->
</script>
<?php

}
if ($script == 'xforms_index.php') {
?>
<script src="<?php echo getStaticContentRootURL(); ?>/site/javascript/xforms_live_search.js"></script>
<?php

}
if ($script == 'change_details.php' || $script == 'register.php') {
?>
<script>
		// <!--
		function checkAllCheckBoxes(checkbox, question_num) {
				var form = checkbox.form;
				var numChecks = 20;
				for (var i = 0; i < numChecks; i++) {
						form['checks_' + question_num + '_' + i].checked = checkbox.checked;
						if (!form['checks_'  +question_num + '_' + (i + 1)]) {
								i = numChecks;
						}
				}
		}
		function uncheckEverythingBox(question_num) {
				var everything = document.getElementById('selectAll_' + question_num);
				everything.checked = false;
		}
		// -->
</script>
<?php

}
popJavascript();
if ($script == 'documents_info.php' && isset($previewAllowed) && $previewAllowed) {
?>
<script>
		PreviewEdit.init(<?php echo '$(\'editable\'), "documents", "' . $page->id . '", "' . Jadu_Service_Container::getInstance()->getInput()->get('preview') . '", "' . Jadu_Service_Container::getInstance()->getInput()->get('expire') . '"'; ?>);
</script>
<?php

}
?>
<script src="<?php echo getStaticContentRootURL(); ?>/site/javascript/site.js"></script>