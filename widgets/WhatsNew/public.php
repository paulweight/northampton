<?php
	include_once("websections/JaduNews.php");
	include_once("websections/JaduEvents.php");
	include_once("websections/JaduDownloads.php");
	include_once("websections/JaduDocuments.php");
	
	if (INSTALLATION_TYPE != GALAXY) {
		if (defined('XFORMS_PROFESSIONAL_VERSION')) {
			include_once('xforms2/JaduXFormsProReadableURLs.php');
			include_once("xforms2/JaduXFormsForm.php");
		}
		else {
			include_once("egov/JaduXFormsForm.php");
		}
	}
	
	define(MAX_NEW_PER_SECTION, 5);
	define(MAX_NEW_ON_SITE, 5);

	$sections = array();
	$news = getAllNewsByDate(true, true);
	if (is_array($news)) {
		$sections['News'] = $news;
	}
	$event = getNumEvents(MAX_NEW_PER_SECTION);
	if (is_array($event)) {
		$sections['Event'] = $event;
	}
	$download = getXMostRecentlyCreatedDownloadFiles(MAX_NEW_PER_SECTION);
	if (is_array($downlaod)) {
		$sections['Download'] = $download;
	}
	$document = getXMostRecentlyCreatedDocuments(MAX_NEW_PER_SECTION, true, true);
	if (is_array($document)) {
		$sections['Document'] = $document;
	}
	
	if (INSTALLATION_TYPE != GALAXY) {
		$form = getXMostRecentlyCreatedXFormsForms(MAX_NEW_PER_SECTION);
		if (is_array($form)) {
			$sections['Form'] = $form;
		}
	}

	$new_on_site = array();

     $count = 0;
     $max_count = MAX_NEW_PER_SECTION * 5;
           
    while (sizeof($new_on_site) < MAX_NEW_ON_SITE && $count < $max_count) {
		$latest_date = 0;
		$latest_section = '';
		foreach ($sections as $title => $section_array) {
			if ($title == 'News') {
				if ($section_array[0]->newsDate > $latest_date) {
					$latest_date = $section_array[0]->newsDate;
					$latest_section = 'News';
				}
			}
			elseif ($title == 'Event') {
				if ($section_array[0]->dateCreated > $latest_date) {
					$latest_date = $section_array[0]->dateCreated;
					$latest_section = 'Event';
				}
			}
			elseif ($title == 'Download') {
				if ($section_array[0]->creationDate > $latest_date) {
					$latest_date = $section_array[0]->creationDate;
					$latest_section = 'Download';
				}
			}
			elseif ($title == 'Document') {
				if ($section_array[0]->enterDate > $latest_date) {
					$latest_date = $section_array[0]->enterDate;
					$latest_section = 'Document';
				}
			}
			elseif (INSTALLATION_TYPE != GALAXY && $title == 'Form') {
				if ($section_array[0]->enterDate > $latest_date) {
					$latest_date = $section_array[0]->enterDate;
					$latest_section = 'Form';
				}
			}
		}

		if (!empty($latest_section)) {
			$new_on_site[$count] = array($latest_section => $sections[$latest_section][0]);
			$sections[$latest_section] = array_slice($sections[$latest_section], 1);
			$max_count--;
		}

		$count++;
	}

?>
<div class="NewOnSiteWidget">
	<h2>New on site</h2>
	<ul class="list icons generic">
<?php
	if (!empty($new_on_site)) {
		foreach ($new_on_site as $item) {
?>
		<li class="long"><a title="<?php print encodeHtml($item[key($item)]->title); ?>" href="<?php
			switch (key($item)) {
				case 'News':
					print getSiteRootURL() . buildNewsArticleURL($item[key($item)]->id);
					break;
				case 'Event':
					print getSiteRootURL() . buildEventsURL(-1, '', $item[key($item)]->id);
					break;
				case 'Document':
					print getSiteRootURL() . buildDocumentsURL($item[key($item)]->id);
					break;
				case 'Form':
					if (defined('XFORMS_PROFESSIONAL_VERSION')) {
						print getSecureSiteRootURL() . buildXFormsProFormURL($item[key($item)]->id);
					}
					else {
						print getSecureSiteRootURL() . buildXFormsURL($item[key($item)]->id);
					}
					break;
				case 'Download':
					print getSiteRootURL() . buildDownloadsURL(-1, $item[key($item)]->id);
					break;
				}
?>">
<?php
			if (key($item) == 'Document') {
				$tmp_header = getDocumentHeader($item[key($item)]->headerOriginalID, true);
				print encodeHtml($tmp_header->title);
			}
			else {
				print encodeHtml($item[key($item)]->title);
			}
?></a>
		</li>
<?php
		}
	}
?>
	</ul>
</div>