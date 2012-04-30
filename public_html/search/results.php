<?php
	include_once('includes/login_header.php');
	require_once('rupa/JaduRupaSearchLog.php');
	require_once('rupa/JaduRupaSearch.php');
	require_once('rupa/JaduRupaAppliance.php');
	require_once('rupa/JaduRupaCollection.php');
	require_once('rupa/JaduRupaCollectionUrl.php');
	require_once('rupa/JaduRupaRenameResultURL.php');
	require_once('JaduMimeTypeList.php');
	require_once('JaduFileTypeMappings.php');
	require_once('rupa/JaduRupaAppliance.php');
	require_once('websections/JaduDownloadFiles.php');
	
	define('PAGE_SEARCH_RESULT_COUNT', 10);
	define('MAXIMUM_NAV_PAGE_COUNT', 10);

	$liveAppliances = getRupaAppliances('live', true, '=');
	if (empty($liveAppliances)) {
		header('Location: http://'.DOMAIN.'/search/offline.php');
	}
	
	$allRupaRenameResultURLs = getAllRupaRenameResultURLs();
	$allCollections = getAllRupaCollections();
	
	$rupaSearch = new RupaSearch();
	$rupaSearch->setDefaultFilter(FILTER_SNIPPET_ONLY);
	$rupaSearch->configureFromGet($_GET);
	
	$allowedCollections = array();
	
	foreach ($allCollections as $collection) {
		$allowedCollections[] = $collection->collectionName;
	}
		
	if (isset($_GET['sites']) && is_array($_GET['sites']) && sizeof($_GET['sites']) > 0) {
		foreach($_GET['sites'] as $collection) {
			if (in_array($collection, $allowedCollections)) {
				$rupaSearch->addSite($collection);
			}
		}
	}
		
	if (sizeof($rupaSearch->sites) < 1) {
		// use all allowed collections if no sites chosen
		foreach ($allowedCollections as $collection) {
			$rupaSearch->addSite($collection);
		}
	}

	if (sizeof($rupaSearch->sites) == sizeof($allowedCollections)) {
		$rupaSearch->allSites = true;
	}

	if (trim($rupaSearch->getFullQuery()) == '') {
		header('Location: '.RUPA_HOME_URL.'advanced.php');
		exit;	
	}
	
	$rupaSearch->addMetaDataFieldsName('JaduTopNavCategory');
	
	if (defined('RUPA_SECURE_RESULT_METADATA_TAG') && RUPA_SECURE_RESULT_METADATA_TAG  != '') {
		$rupaSearch->addMetaDataFieldsName('RUPA_SECURE_RESULT_METADATA_TAG');
	}
	
	if (defined('RUPA_RESULT_TITLE_METADATA_FIELD') && RUPA_RESULT_TITLE_METADATA_FIELD  != '') {
		$rupaSearch->addMetaDataFieldsName('RUPA_RESULT_TITLE_METADATA_FIELD');
	}
	
	if (defined('RUPA_RESULT_TITLE_METADATA_FIELD') && RUPA_RESULT_TITLE_METADATA_FIELD  != '') {
		$rupaSearch->addMetaDataFieldsName('RUPA_RESULT_TITLE_METADATA_FIELD');
	}
	
	$results = $rupaSearch->search();
	$htmlSafeQuery = $rupaSearch->getFullQueryForXHTML();
	$resultsCount = $results->documentsFound;
	$displayedResultsCount = $results->startNumber + sizeof($results->resultItems) - 1;	 		
	
	//only log the first page request for non-blank searches
	if ($rupaSearch->getFullQuery() != '' && $rupaSearch->startNum == -1) {
		
		$logDate = date('Y-m-d');
		$logHour = date('H');
	
		$searchLog = getRupaSearchLog (mb_strtolower($rupaSearch->getFullQuery()), mb_strtolower($rupaSearch->getSitesAsString()), $logDate, $logHour);
		
		if ($searchLog->id > -1) {
			$searchLog->addHit($resultsCount);
			$searchLog->update();
		}
		else {
			$searchLog->meanDocumentsFound = $resultsCount;
			$searchLog->frequency = 1;
			$searchLog->insert();
		}
	}
  	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<title><?php print $htmlSafeQuery.' - '. encodeHtml(RUPA_INSTALLATION_NAME); ?> - Results</title>
	<link rel="search" type="application/opensearchdescription+xml" href="<?php print RUPA_HOME_URL; ?>opensearch.php" title="<?php print RUPA_INSTALLATION_NAME; ?>">
	<link rel="stylesheet" type="text/css" href="<?php print RUPA_HOME_URL; ?>styles/<?php print encodeHtml(RUPA_STYLESHEET); ?>" media="screen">
	<link rel="stylesheet" type="text/css" href="<?php print RUPA_HOME_URL; ?>styles/google_results.css" media="screen">
	<link rel="Shortcut Icon" type="image/x-icon" href="<?php print getStaticContentRootURL(); ?>/favicon.ico">
	<!-- general metadata -->
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="content-language" content="en">
	<meta name="generator" content="http://www.jadu.co.uk">
	<meta name="robots" content="noindex,nofollow">
	<meta name="revisit-after" content="2 days">
	
	<!--[if gte IE 5.5]><![if lt IE 7]>
		<style type="text/css">
			div#content_browse {
				right: auto; bottom: auto;
				left: expression( ( 0 - content_browse.offsetWidth + ( document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.clientWidth ) + ( ignoreMe2 = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft ) ) + 'px' );
				top: expression( ( -35 - content_browse.offsetHeight + ( document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight ) + ( ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop ) ) + 'px' );
			}
		</style>
	<![endif]><![endif]-->

</head>
<body id="rupa_admin">		
	<div id="container">	
	<?php include_once('includes/search_mast.php'); ?>
            
	<div id="googlerupa_results">

		<p class="googstrip">
<?php

		if ($resultsCount > 0) {
?>
			Results <strong><?php print $results->startNumber; ?> - <?php print $results->endNumber; ?></strong> of about <strong><?php print $results->documentsFound; ?></strong>.
<?php
		}
		else{
?>
			No results were found.
<?php
		}
?>
		</p>

		<p class="first">You searched for <strong><?php print $rupaSearch->getFullQueryForXHTML(); ?></strong>.</p>
<?php
		if ($resultsCount > 0) {
?>
		
		<!-- Search Key -->
		<div id="searchKey">
			<h3>Refine by:</h3>
			<ul id="keyList">
<?php
			$collectionImageLinks = array();
		
			foreach ($allCollections as $coll) {
				$tempRupaSearch = $rupaSearch->getClone();
				$tempRupaSearch->clearSites();
				$tempRupaSearch->addSite($coll->collectionName);
				
				$queryString = $tempRupaSearch->getRupaQueryStringForXHTML();

				$collectionImageLinks[$coll->friendlyName] = RUPA_HOME_URL.'results.php?'.$queryString;
?>
				<li><a href="<?php print RUPA_HOME_URL.'results.php?'.$queryString; ?>"><img alt="" src="<?php print getStaticContentRootURL().'/images/'.encodeHtml($coll->imageName); ?>" /><?php print encodeHtml($coll->friendlyName); ?></a></li>

<?php
			}
?>
			</ul>
		</div>
		<!-- END search Key -->
<?php
		}
?>
		<div id="searchResults" class="full_screen">
<?php
			if (sizeof($results->resultSynonyms) > 0) {
?>
				<p class="first">You could also try: <?php
		
				$count = 1;
				foreach ($results->resultSynonyms as $synonym) {
					$tempRupaSearch = $rupaSearch->getClone();
					$tempRupaSearch->clearFullQuery();
					$tempRupaSearch->setQuery(urldecode($synonym->query));
					$synonymQuery = $tempRupaSearch->getRupaQueryStringForURL();
					
					print '<a href="'.RUPA_HOME_URL.'results.php?'.$synonymQuery.'">'.encodeHtml($synonym->suggestion).'</a>';
					
					if ($count < sizeof($results->resultSynonyms)) {
						print ', ';
					}
					
					$count++;
				}
?>.</p>
<?php
			}
		
    		if (sizeof($results->spellingSuggestions) > 0) {
?>
				<p class="first">
        				Did you mean: <?php
        				
    					$count = 1;
						foreach ($results->spellingSuggestions as $spelling) {
							$tempRupaSearch = $rupaSearch->getClone();
							$tempRupaSearch->clearFullQuery();
							$tempRupaSearch->setQuery($spelling->query);
							
							print '<strong><a href="'.RUPA_HOME_URL.'results.php?'.$tempRupaSearch->getRupaQueryStringForURL().'" class="copy">'.encodeHtml($spelling->query).'</a></strong>';
							if ($count < sizeof($rupaSearch->spellingSuggestions)) {
								print ', ';	
							}
							$count++;
						}	
?>.</p>
<?php
    		}
    		
			if (($results->documentsFound <= 0) && (sizeof($results->resultKeyMatches) < 1)) {
?>
        			<p class="first"><strong>Sorry, no results were found.</strong></p>
<?php
			}
					
           	if (sizeof($results->resultKeyMatches) > 0) {

				foreach ($results->resultKeyMatches as $km) {
?>
          			<!-- key match result box -->
        			<div class="keymatch">
        				<h2><a  title="<?php print encodeHtml($km->name); ?>" href="<?php print encodeHtml($km->url); ?>"><?php print encodeHtml($km->name); ?></a></h2>
        					<p class="url"><?php print encodeHtml($km->url); ?></p>
        			</div>
<?php
				}

        	}
?>

            		<!-- search result box -->
<?php
        	if ($results->documentsFound > 0) {
        		
        		$tempRupaSearch = $rupaSearch->getClone();
				if ($rupaSearch->sortBy != 'date') {
					$tempRupaSearch->setSortBy('date');
					$sortText = '<a href="'.RUPA_HOME_URL.'results.php?'.$tempRupaSearch->getRupaQueryStringForXHTML().'">Sort by date</a> or <strong>relevance</strong>';
				}
				else {
					$tempRupaSearch->setSortBy('');
					$sortText = 'Sort by <strong>date</strong> or <a href="'.RUPA_HOME_URL.'results.php?'.$tempRupaSearch->getRupaQueryStringForXHTML().'">relevance</a>';
				}
        		
?>
					<p id="sorting" ><?php print $sortText; ?></p>
<?php   				
			

          		foreach ($results->resultItems as $resultItem) {
            		// check cache is available - $element->cacheAvailable
            		$cacheLink = encodeHtml(RUPA_HOME_URL.'result_cache.php?url='.$resultItem->url.'&CID='.$resultItem->cacheID);
            		
            		$icon = '';
			
					switch ($resultItem->mime) {
						case '':
						case 'text/html':
						case 'text/plain':
							break;
						default:
							$icon = getIconForMimeType($resultItem->mime);
							break;
					}
					
					$href = '';
					
					if ($resultItem->url != '') {
						$href = $resultItem->url;
						foreach ($allRupaRenameResultURLs as $renameResultURL) {
							$href = str_replace($renameResultURL->fromPattern, $renameResultURL->replaceWith, $href);	
						}	
					}
					
					$title = $resultItem->getTitleForXHTML();
					$snippet = $resultItem->getSnippetForXHTML();
					$restricted = '';
					
					if ((defined('RUPA_RESULT_TITLE_METADATA_FIELD') && RUPA_RESULT_TITLE_METADATA_FIELD != '') ||
						(defined('RUPA_RESULT_SNIPPET_METADATA_FIELD') && RUPA_RESULT_SNIPPET_METADATA_FIELD != '') ||
						(defined('RUPA_SECURE_RESULT_METADATA_TAG') && RUPA_SECURE_RESULT_METADATA_TAG != '')) {
						
						foreach ($resultItem->metadata as $metadata) {
							if (defined('RUPA_RESULT_TITLE_METADATA_FIELD') && $metadata->name == RUPA_RESULT_TITLE_METADATA_FIELD && $metadata->value != '') {
								$title = encodeHtml($metadata->value);	
							}		
							if (defined('RUPA_RESULT_SNIPPET_METADATA_FIELD') && $metadata->name == RUPA_RESULT_SNIPPET_METADATA_FIELD && $metadata->value != '') {
								$snippet = encodeHtml($metadata->value);	
							}
							if (defined('RUPA_SECURE_RESULT_METADATA_TAG') && $metadata->name == RUPA_SECURE_RESULT_METADATA_TAG && $metadata->value != '') {
								$restricted = '[Restricted]';	
							}
						}
					}
					
					
					if (mb_eregi('/download', $resultItem->url) && trim($title) == '') {
						$filename = str_replace('http://'.DOMAIN.'/', '', $resultItem->url);
						$filename = mb_eregi_replace('downloads/', '', $filename);
						$filename = mb_eregi_replace('file\/([0-9]+)/', '', $filename);
						$filename = mb_eregi_replace('download\/([0-9]+)/', '', $filename);
						$filename = mb_eregi_replace('([0-9]+)/', '', $filename);
						$filename = mb_eregi_replace('_', ' ', $filename);
						$title = ucwords($filename);
					}
					
					
					if (trim($title) == '') {
						$title = '[No title]';	
					}
					$title = str_replace(' - '.METADATA_GENERIC_NAME. '', '', $title);
					
					$extraInfo = '';
					
					if ($resultItem->size != '') {
						$extraInfo = ' - '.$resultItem->size;	
					}
					
					if ($resultItem->documentDate != '') {
						$extraInfo .= ' - '.$resultItem->documentDate;	
					}		
            			
            			
?>
        			<div class="googresult">
<?php
						//get the collection for this result
						$collections  = getCollectionsForUrl($resultItem->url, false);
						
						$images = array();
						if (is_array($collections)) { 
							foreach($collections as $collection) {
								$images[$collection->friendlyName] = $collection->imageName; 
							}
						}
?>
        				<div class="coll_image">
<?php			
        				        foreach ($images as $name => $image) {
?>
        				            <a href="<?php print encodeHtml($collectionImageLinks[$name]); ?>" class="showtooltip"><img src="<?php print getStaticContentRootURL().'/images/'.encodeHtml($image); ?>" alt="" /></a><br />
<?php
        				        }
?>
        				</div>      

					<h2>
						<a href="<?php print encodeHtml($href); ?>"><?php print $title; ?></a> 
<?php
						if ($restricted != '') {
?>
						<span><?php print $restricted; ?></span>
<?php													
						}
						
						if ($icon != '') {
?>
						<img alt="<?php print encodeHtml($resultItem->mime); ?>" src="<?php print RUPA_HOME_URL.'images/file_type_icons/'.encodeHtml($icon); ?>" /> [<?php print encodeHtml(substr($icon, 0, 3)); ?>]
<?php
						}
?>
					</h2>
									
					<p><?php print $snippet; ?></p>
					
					<!-- only if cache is available -->
					<p><span class="url"><?php print encodeHtml($href) . $extraInfo; ?></span>
<?php
						if (defined('RUPA_SHOW_CACHED_RESULTS') && RUPA_SHOW_CACHED_RESULTS) {
?>
					 - <a class="cache"  href="<?php  print encodeHtml($cacheLink); ?>"><?php print encodeHtml($cached); ?></a>
<?php
						}
?>
					</p>
				</div>
				<!-- END of googresult -->
<?php
        		}
        			
?>
			</div>
<?php
				$pageCount = floor($results->documentsFound / PAGE_SEARCH_RESULT_COUNT) + 1;

				// if number of pages is a multiple of ten we don't need the last page
		 		if (($results->documentsFound % 10) == 0) {
		 			$pageCount--;
		 		}

				// if we're filtering, we haven't reached the end and the appliance says
				// there's no next page, show option to turn off filter
				if ($rupaSearch->getFilterResults() != FILTER_OFF && $results->documentsFound > $results->endNumber  && $results->nextPage == false) {
		 				$tempRupaSearch = $rupaSearch->getClone();
						$tempRupaSearch->setStartNum(0);
						$tempRupaSearch->setFilterResults(FILTER_OFF);
?>
		 			<p class="resultsOmitted">In order to show you the most relevant results, we have omitted some entries very similar to the <?php print $displayedResultsCount; ?> already displayed. If you like, you can <a href="<?php print RUPA_HOME_URL.'results.php?' . encodeHtml($tempRupaSearch->getRupaQueryStringForURL()); ?>">repeat the search with the omitted results included</a>.</p>
<?php
		 		}
		 		
		 		if ($pageCount > 1) {
?>
            		<!-- Page navigation -->
        			<div id="pagenav">
<?php
		
			 		$currentPage = (($results->startNumber - 1) / $rupaSearch->numToShow) + 1;
			 		if ($currentPage > MAXIMUM_NAV_PAGE_COUNT / 2) {
			 			$startPage = max(1, intval($currentPage - (MAXIMUM_NAV_PAGE_COUNT / 2)));
			 			$endPage = min($pageCount, intval($currentPage + (MAXIMUM_NAV_PAGE_COUNT / 2)));
			 		}
			 		else {
			 			$startPage = 1;
			 			$endPage = $startPage + (MAXIMUM_NAV_PAGE_COUNT - 1);
			 		}
			 		
			 		$endPage = min($endPage, $pageCount);
                    
                    if (count($results->resultItems) > 0) {
?>
                		<p>
                			<span>
<?php
								//previous page
                				if ($results->previousPage || $results->startNumber > 1) {
                					$tempRupaSearch = $rupaSearch->getClone();
                					$tempRupaSearch->setStartNum($results->startNumber - (PAGE_SEARCH_RESULT_COUNT + 1));
                					print '<a href="'.RUPA_HOME_URL.'results.php?' . encodeHtml($tempRupaSearch->getRupaQueryStringForURL()).'">Previous</a>';
                				}

        						//next page
                				if ($results->nextPage && (($results->startNumber + $rupaSearch->numToShow) < $results->documentsFound && ($results->documentsFound - $rupaSearch->numToShow) > 0)) {
                					$tempRupaSearch = $rupaSearch->getClone();
					        		$tempRupaSearch->setStartNum($results->endNumber);
                					print '<a href="'.RUPA_HOME_URL.'results.php?' . encodeHtml($tempRupaSearch->getRupaQueryStringForURL()). '">Next</a>';
                				}
?>
							</span>
						</p>
<?php
                			}
                		
?>
            		</div>
<?php
						}
								
								
  	    				if ($results->documentsFound > 0) {
?>
            	<!-- END Page navigation-->
            	
            	<!-- search within results form -->
            	<br />
				<form id="searchWithinForm" method="get" action="<?php print RUPA_HOME_URL.'results.php'; ?>" class="search_within_form">
					<fieldset>
						<legend>Search within results</legend>
						<label for="googleSearchWithinBox">Search within results: </label>
<?php
							// searching within results we need to reset the start so returned results will begin on page 1
							$_GET['start'] = 0;
							foreach ($_GET as $getName => $getValue) {
								if (is_array($getValue)) {
									foreach ($getValue as $subName => $subValue) {	
?>
						<input type="hidden" name="<?php print encodeHtml($getName); ?>" value="<?php print encodeHtml($subValue); ?>" />
<?php
									}
								}
								else {
									if ($getName != 'q') {
?>
						<input type="hidden" name="<?php print encodeHtml($getName); ?>" value="<?php print encodeHtml($getValue); ?>" />
<?php										
									}
								}
							}
?>							
						<input type="hidden" name="pre_q" value="<?php print $htmlSafeQuery; ?>" />
						<input class="keyword_field" type="text" name="q" id="googleSearchWithinBox" value="" size="35" />
						<input type="submit" name="googleSearchSubmit" value="Search" class="small_button" />
					</fieldset>
				</form>

                <br class="clear"  />
<?php
					}
?>
 <?php
	}
?>   
            </div>
	</div>

	<!-- Drawer -->	
	<?php include_once("includes/footer.php"); ?> 

</body>
</html>
