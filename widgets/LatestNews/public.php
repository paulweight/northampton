<?php
	include_once("websections/JaduNews.php");

	$allNews = array();
	$newsItemsToShow = 4;
	
	$topNews = getTopNews(true, true);

	if ($topNews !== null) {
		$allNews[] = $topNews;
	}

	$newsItems = getAllNewsByDate(true, true);
	
	$loops = 0;
	foreach ($newsItems as $item) {

		$isItemTheTopNewsItem = ($topNews !== null && isset($topNews->id) && $topNews->id == $item->id);
		if ($isItemTheTopNewsItem) {
			continue;
		}
		$allNews[] = $item;

		if (++$loops >= $newsItemsToShow) {
			break;
		}
	}
?>
<div class="latestNewsWidget">
	<h2>Latest News</h2>
<?php
	$loops = 0;
	foreach ($allNews as $news) {
?>
	<h3><a href="<?php print getSiteRootURL() . buildNewsArticleURL($news->id); ?>"><?php print encodeHtml($news->title); ?></a></h3>
	<p><?php print encodeHtml($news->summary); ?></p>
<?php	
		if (++$loops >= $newsItemsToShow) {
			break;
		}
	}
?>
	<p><a href="<?php print getSiteRootURL() . buildRSSURL(); ?>" >Subscribe to NBC News</a></p>
</div>