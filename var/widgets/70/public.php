<?php
	require_once('JaduConstants.php');
	require_once('library/JaduNetworkFunctions.php');
	
	$contentWidgetTitle = '%TITLE%';
	$contentWidgetLinHref = '%LINK_HREF%';
	$contentWidgetContent = '%CONTENT%';
	$contentWidgetContent = str_replace('\"','"', $contentWidgetContent); 
	$contentWidgetContent = str_replace('\\\'', '\'', $contentWidgetContent);
	$contentWidgetTitle = str_replace('\"', '"', $contentWidgetTitle);

	if (isset($_POST['action']) && isset($_POST['preview'])) {
		$rootUrl = getCurrentProtocolSiteRootURL();
		$contentWidgetContent = str_replace('src="./images/', 'src="'.$rootUrl.'/images/', $contentWidgetContent);
	}
	if ($contentWidgetContent != '' && $contentWidgetContent != '%CONTENT'.'%') {
?>
<div class="contentWidgetWithLink">
<?php
		if ($contentWidgetLinHref != '' && $contentWidgetLinHref != '%LINK_HREF'.'%' && $contentWidgetTitle != '' && $contentWidgetTitle != '%TITLE'.'%') {
?>
	<h4><a href="<?php print encodeHtml($contentWidgetLinHref); ?>"><?php print encodeHtml($contentWidgetTitle); ?></a></h4>
<?php
		}
		elseif ($contentWidgetTitle != '' && $contentWidgetTitle != '%TITLE'.'%') {
?>
	<h4><?php print encodeHtml($contentWidgetTitle); ?></h4>
<?php
		}
?>
	<div class="widget_content byEditor"><?php print processEditorContent($contentWidgetContent); ?></div>
<?php
		if ($contentWidgetLinHref != '' && $contentWidgetLinHref != '%LINK_HREF'.'%') {
?>
	<a class="arrow" href="<?php print encodeHtml($contentWidgetLinHref); ?>"><?php print ($contentWidgetTitle != '' && $contentWidgetTitle != '%TITLE'.'%') ? encodeHtml($contentWidgetTitle) : 'More&hellip;'; ?></a>
<?php
		}
?>
</div>
<?php
	}
?>