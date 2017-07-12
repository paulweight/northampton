<?php
    require_once 'JaduConstants.php';

    $contentWidgetTitle = <<<JADU_WIDGET_TITLE
%TITLE%
JADU_WIDGET_TITLE;

    $contentWidgetContent = <<<JADU_WIDGET_END_OF_CONTENT
%CONTENT%
JADU_WIDGET_END_OF_CONTENT;

    $contentWidgetContent = str_replace('\"', '"', $contentWidgetContent);
    $contentWidgetContent = str_replace('\\\'', '\'', $contentWidgetContent);
    $contentWidgetTitle = str_replace('\"', '"', $contentWidgetTitle);

    $input = Jadu_Service_Container::getInstance()->getInput(false);

    if ($input->post('action') && $input->post('preview')) {
        $contentWidgetContent = str_replace('src="./images/', 'src="' . getCurrentProtocolSiteRootURL() . '/images/', $contentWidgetContent);
        // Editor class for control centre preview
        $editorClass = 'byEditor';
    } else {
        // Editor class for front end
        $editorClass = 'editor';
    }
?>
<?php
    if ($contentWidgetTitle) {
        ?>
    <h2><?php echo encodeHtml($contentWidgetTitle); ?></h2>
<?php

    }
?>

<?php if (processEditorContent($contentWidgetContent)) {
    ?>
    <div class="widget_content byEditor by_editor <?php echo $editorClass; ?>"><?php echo processEditorContent($contentWidgetContent); ?></div>
<?php 
} ?>

