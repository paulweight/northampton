<?php

if (isset($_GET['id']) && $_GET['id'] > 0 && 
	isset($_GET['width']) && $_GET['width'] > 0 && 
	isset($_GET['height']) && $_GET['height'] > 0) {
	require_once('JaduConstants.php');
	require_once('JaduImages.php');
	require_once('multimedia/JaduMultimediaItems.php');

	$item = getMultimediaItem($_GET['id']);
	if ($item && ($item->isImage() || $item->isVideo())) {
		$thumbnailFile = generateMultimediaItemThumbnail($item, $_GET['width'], $_GET['height']);
		if (file_exists($thumbnailFile)) {
			header('Content-Type: image/jpeg');
			readfile($thumbnailFile);
			die;
		}
	}
}

header('HTTP/1.0 404 Not Found');
