<?php
	require_once('rupa/JaduRupaSearchLog.php');
	require_once('rupa/JaduRupaGoogle.php');
	require_once('rupa/JaduRupaSearch.php');
	require_once('rupa/JaduRupaAppliance.php');
	require_once('rupa/JaduRupaCollection.php');
	require_once('rupa/JaduRupaCollectionUrl.php');
	require_once('rupa/JaduRupaRenameResultURL.php');
	require_once('JaduMimeTypeList.php');
	require_once('JaduFileTypeMappings.php');
	require_once('rupa/JaduRupaSeasoning.php');
	include_once('includes/login_header.php');
	require_once('rupa/JaduRupaAppliance.php');

	define(PAGE_SEARCH_RESULT_COUNT, 10);
	define(MAXIMUM_NAV_PAGE_COUNT, 10);

	$liveAppliances = getRupaAppliances('live', true, '=');
	if (empty($liveAppliances)) {
		header('Location: http://'.DOMAIN.'/search/offline.php');
	}
	
	$allRenameRules = getAllRupaRenameResultURLs();
	$allCollections = getAllRupaCollections();

	$defaultStylesheet = getSeasoningStylesheet(RUPA_STYLESHEET);
	
	$rupaSearch = new RupaSearch();
	
	$allowedCollections = array();
	
	foreach ($allCollections as $collection) {
		$allowedCollections[] = $collection->collectionName;
	}
		
	if (is_array($_GET['collections']) && sizeof($_GET['collections']) > 0) {
		foreach($_GET['collections'] as $collection) {
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
		$searchAll = true;
	}
	else {
		$searchAll = false;
	}

	if (isset($_GET['q'])) {
		if (isset($_GET['pre_q'])) {
			$query = $_GET['pre_q'].' ';
		}
		$query .= $_GET['q'];
		
 		$rupaSearch->setQuery($query);
	}
	if (!empty($query)) {
		
		// exact match
		if (!empty($_GET['quoteQuery'])) {
				$rupaSearch->setQuoteQuery($_GET['quoteQuery']);
		}
	
		// 'or' match
		if (!empty($_GET['orQuery'])) {
			$rupaSearch->setOrQuery($_GET['orQuery']);
		}
	
		// exclude words
		if (!empty($_GET['excludeWords'])) {
			$rupaSearch->setExcludeWords($_GET['excludeWords']);
		}
	
		// file format
		if (!empty($_GET['fileFormat']) && is_array($_GET['fileFormat'])) {
			foreach ($_GET['fileFormat'] as $fileFormat) {
				$rupaSearch->addFileFormat($fileFormat);
			}
		}
		
		if (!empty($_GET['fileFormatInclusion'])) {
			$rupaSearch->setFileFormatInclusion($_GET['fileFormatInclusion']);
		}
		
		if (!empty($_GET['start'])) {
			$rupaSearch->setStartNum($_GET['start']);
		}
		
		if (!empty($_GET['num'])) {
			$rupaSearch->setNumToShow($_GET['num']);
		}
	
		if (!empty($_GET['sortBy'])) {
			$rupaSearch->setSortBy($_GET['sortBy']);
		}
		
		/*$google_query = array(
							'q' => html_entity_decode($query),
							'client' => RUPA_GSA_FRONTEND,
							'site' => $siteString,
							'collections' => $sites,
							'output' => 'xml',
							'ie' => '',
							'lr' => '',
							'oe' => '',
							'filter' => 0,
							'getfields' => '*',
							'start' => $_GET['start'],
							'sort' => $_GET['sort']
							);*/
	
			
		$results = $rupaSearch->search();
	
		//build sort links
		$sortOptions = array(
			'Sort by relevance' => '',
			'Sort by date' => 'date:D:R:d1'
		);
	
		/*$tempSort = $google_query['sort'];
	
		if ($google_query['sort'] == $sortOptions['Sort by relevance'] || ($google_query['sort'] == '')) {
			$google_query['sort'] = $sortOptions['Sort by date'];
			$sortQuery = http_build_query($google_query);
			$sort = '<a href="'.RUPA_HOME_URL.'results.php?'.$sortQuery.'" title="Sort by date" >Sort by date</a> / Sort by relevance';
		}
		else{
			$google_query['sort'] = $sortOptions['Sort by relevance'];
			$sortQuery = http_build_query($google_query);
			$sort = 'Sort by date / <a href="'.RUPA_HOME_URL.'results.php?'.$sortQuery.'" title="Sort by relevance" >Sort by relevance</a>';
		}
		
		$google_query['sort'] = $tempSort;*/
	
		//$htmlSafeQuery = htmlentities(stripslashes(stripslashes(trim($results['GSP']['Q']))));
		$htmlSafeQuery = $rupaSearch->getFullQueryForXHTML();
		
		$resultsCount = $results->documentsFound;
		
		/*$oneBoxResults = $results['GSP']['ENTOBRESULTS']['OBRES'];
		if (!is_array($oneBoxResults['MODULE_RESULT'][0]) && is_array($oneBoxResults)) {
			$temp = $oneBoxResults['MODULE_RESULT'];
			$oneBoxResults['MODULE_RESULT'] = array();
			$oneBoxResults['MODULE_RESULT'][0] = $temp;
		}*/
	
	
		//only log the first page request for non-blank searches
		if ($rupaSearch->getFullQuery() != '' && $rupaSearch->startNum == -1) {
			
			$logDate = date('Y-m-d');
			$logHour = date('H');
		
			$searchLog = getRupaSearchLog (strtolower($rupaSearch->getFullQuery()), strtolower($rupaSearch->getSitesAsString()), $logDate, $logHour);
			
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
   	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<title><?php print RUPA_INSTALLATION_NAME; ?> - Results</title>
	<link rel="search" type="application/opensearchdescription+xml" href="http://<?php print DOMAIN; ?>/search/openSearch.php" title="<?php print RUPA_INSTALLATION_NAME; ?>" />
	<link rel="stylesheet" type="text/css" href="<?php print $defaultStylesheet->fullWebPath;?>" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php print RUPA_HOME_URL; ?>styles/google_results.css" media="screen" />
	<link rel="Shortcut Icon" type="image/x-icon" href="<?php print SHARED_HOME_URL; ?>favicon.ico" />
	<!-- general metadata -->
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="content-language" content="en" />
	<meta name="generator" content="http://www.jadu.co.uk" />
	<meta name="robots" content="index,follow" />
	<meta name="revisit-after" content="2 days" />
	
	<script type="text/javascript" src="<?php print RUPA_HOME_URL; ?>javascript/BubbleTooltips.js"></script>
	<script src="<?php print RUPA_HOME_URL; ?>javascript/rupa.js" type="text/javascript"></script>	
	<script type="text/javascript">
		window.onload=function(){enableTooltips()};

    function resizeRelativeWidth(element_id, start, end, type)
    {
        $(element_id).style.width = end + type;
    }
    
    function switchRelativeWidth(element_id, width_a, width_b)
    {
        if($(element_id).style.width == width_a){
            $(element_id).style.width = width_b;
        }else{
            $(element_id).style.width = width_a;
        }
    
    }
    
    function newWindow (site)
    {        
        window.open($(site+'_frame').src);
    }
	</script>
	
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
			Results <strong><?php print $results->startNumber; ?> - <?php print $results->endNumber; ?></strong> of about <strong><?php print $results->documentsFound; ?></strong>. <strong>(<?php print $results->time; ?>)</strong> seconds
		<?php
		}else{
		?>
			No results were found.
<?php
		}
?>
		</p>
		
<?php
	if (!empty($query)) {
?>
		<p class="first">You searched for <strong><?php print $rupaSearch->getFullQueryForXHTML(); ?></strong>.</p>
		
		<!-- Search Key -->
		<div id="searchKey">
			<h3>Refine by:</h3>
			<ul id="keyList">
<?php
			$collectionImageLinks = array();
		
			foreach($allCollections as $coll){
				$image = $collectionImages[$coll->image_id];
				
				$tempSite = $_GET['site'];
				$tempSearchType = $_GET['searchType'];
				$tempCollections = $_GET['collections'];
				$tempStart = $_GET['start'];
				$tempFileFormatInclusion = $_GET['fileFormatInclusion'];
				$tempFileFormat = $_GET['fileFormat'];
				$tempSearchMain = $_GET['googleSearchSubmitMain'];
				unset($_GET['googleSearchSubmitMain']);

				$_GET['q'] = stripslashes(html_entity_decode($_GET['q']));
				$_GET['site'] = '';
				$_GET['searchType'] = 'advanced';
				$_GET['collections'] = array();
				$_GET['collections'][] = $coll->collectionName;
				$_GET['start'] = '';
				$_GET['fileFormatInclusion'] = '';
				$_GET['fileFormat'] = '';
				$keyQuery = http_build_query($_GET);
				$collectionImageLinks[$coll->image_id] = $keyQuery;
				
?>
				<li><a href="<?php print RUPA_HOME_URL.'results.php?'.$keyQuery; ?>" title="<?php print $coll->friendlyName; ?>"><img alt="" src="<?php print PROTOCOL.DOMAIN.'/images/'.$coll->imageName	; ?>" /><?php print $coll->friendlyName; ?></a></li>

<?php
				$_GET['site'] = $tempSite;
				$_GET['searchType'] = $tempSearchType;
				$_GET['collections'] = $tempCollections;
				$_GET['start'] = $tempStart;
				$_GET['fileFormatInclusion'] = $tempFileFormatInclusion;
				$_GET['fileFormat'] = $tempFileFormat;
				if ($tempSearchMain != '') {
					$_GET['googleSearchSubmitMain'] = $tempSearchMain;
				}
			}
?>
			</ul>
		</div>
		<!-- END search Key -->
<div id="searchResults" class="full_screen">
								
<?php
					if ($synonyms) {
						$temp = $_GET['q'];
						$_GET['q'] = urldecode($synonyms['!q']);
						$synonymQuery = http_build_query($_GET);
						$_GET['q'] = $temp;
?>
					<p class="first">You could also try: <a href="<?php print RUPA_HOME_URL.'results.php?'.htmlentities($synonymQuery); ?>"><?php print $synonyms['!']; ?></a>.</p>
<?php	
						
					}
    				if (sizeof($results->spellingSuggestions) > 0) {
    				    $temp = $_GET['q'];
    					$_GET['q'] = $results->spellingSuggestions[0]->query;
    					$query = http_build_query($_GET);
    					$_GET['q'] = $temp;
?>
           			<p class="first">
        				Did you mean: <strong><a href="<?php print RUPA_HOME_URL.'results.php?'.htmlentities($query); ?>" class="copy"><?php print $results->spellingSuggestions[0]->query; ?></a></strong>
        			</p>
<?php
					}
					elseif (($results->documentsFound == 0) && (sizeof($results->resultKeyMatches) < 1)) {
?>
        			<p class="first"><strong>Sorry, no results were found.</strong></p>
<?php
					}
					
           			if (sizeof($results->resultKeyMatches) > 0) {

						foreach ($results->resultKeyMatches as $km) {
?>
          			<!-- key match result box -->
        			<div class="keymatch">
        				<h2><a  title="<?php print strip_tags($km->name); ?>" href="<?php print htmlentities($km->url); ?>"><?php print $km->name; ?></a></h2>
        					<p class="url"><?php print htmlentities($km->url); ?></p>
        			</div>
<?php
						}

        			}
?>

            		<!-- search result box -->
<?php
        				if ($results->documentsFound > 0) {
?>
					<p id="sorting" ><?php
						print $sort; 
?>
					</p>
<?php   				
						}

            			foreach ($results->resultItems as $resultItem) {
            			// check cache is available - $element->cacheAvailable
            			$cacheLink = RUPA_HOME_URL.'result_cache.php?url='.htmlentities($resultItem->url).'&CID='.$resultItem->cacheID;
        				$cacheLink = str_replace('&', '&amp;', $cacheLink);
?>
        			<div class="googresult">
<?php
						//get the collection for this result
						$collections  = getCollectionsForUrl($resultItem->url);
						
						foreach ($allRenameRules as $renameRule) {
							$resultItem->url = str_replace($renameRule->fromPattern, $renameRule->replaceWith, $resultItem->url);
							$resultItem->encodedUrl = str_replace($renameRule->fromPattern, $renameRule->replaceWith, $resultItem->encodedUrl);
						}
						
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
        				            <a href="<?php print RUPA_HOME_URL.'results.php?'.$collectionImageLinks[$image->id]; ?>" title="Refine results: <?php print $name; ?>" class="showtooltip"><img src="<?php print PROTOCOL.DOMAIN.'/images/'.$image; ?>" alt="" /></a><br />
<?php
        				        }
?>
        				</div>      

					<h2>
			<?php
						switch ($resultItem->mime) {
							case '':
							case 'text/html':
							case 'text/plain':
								$cached = 'cached';
								break;
							default:
								$cached = 'HTML Version';
								break;
						}

						$icon = getIconForMimeType($resultItem->mime);

?>
						<a title="<?php print $resultItem->title; ?>" href="<?php print str_replace('</b>', '</strong>', str_replace('<b>', '<strong>',  str_replace('&', '&amp;', $resultItem->url))); ?>"><?php if ($resultItem->title == '') { print '[no title]'; } else { print  str_replace('</b>', '</strong>', str_replace('<b>', '<strong>', $resultItem->title)); } ?></a>
<?php
						if ($icon != '') {
?>
						<img alt="<?php print $resultItem->mime; ?>" src="<?php print RUPA_HOME_URL.'images/file_type_icons/'.$icon; ?>" />
<?php
						}
?>
					</h2>
									
					<p><?php print str_replace('</b>', '</strong>', str_replace('<b>', '<strong>', $resultItem->snippet)); ?></p>
					<?php
					//get the date if available
					?>
					
					<!-- only if cache is available -->
					<p><span class="url"><?php print $resultItem->url; ?> - <?php if ($resultItem->size != '') { print $resultItem->size.' '; } if ($resultItem->crawlDate != null) { print $resultItem->crawlDate; } ?></span>
<?php
	if (defined('RUPA_SHOW_CACHED_RESULTS') && RUPA_SHOW_CACHED_RESULTS) {
?>
					 - <a class="cache"  href="<?php  print $cacheLink; ?>"><?php print $cached; ?></a>
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

        				if ($pageCount > 1) {
?>
            		<!-- Page navigation -->
        			<div id="pagenav">
<?php
							// if $_GET['start'] is not set we are on the first page of results.
                			if (!isset($_GET['start']) || $_GET['start'] > $results->documentsFound) {
                				$currentPage = 1;
                				$_GET['start'] = 0;
                			}
                			else {
                				$currentPage = (floor($_GET['start'] / PAGE_SEARCH_RESULT_COUNT)) + 1;
                			}
                			
							if ($currentPage > MAXIMUM_NAV_PAGE_COUNT / 2) {
								$startPage = max(1, intval($currentPage - (MAXIMUM_NAV_PAGE_COUNT / 2)));
								$endPage = min($pageCount, intval($currentPage + (MAXIMUM_NAV_PAGE_COUNT / 2)));
							} else {
								$startPage = 1;
								$endPage = $startPage + (MAXIMUM_NAV_PAGE_COUNT - 1);
							}
							
                			 $endPage = min($endPage, $pageCount);
                    		if ($results->documentsFound > 0) {
?>
                			<p>Result Page:
<?php
								//previous page
                				if ($_GET['start'] >= 10) {
                				    $temp = $_GET['start'];
                				    $_GET['start'] -= 10;
                					print '<a href="'.RUPA_HOME_URL.'results.php?' . str_replace('&', '&amp;', http_build_query($_GET)).'">Previous</a> | ';
                					$_GET['start'] = $temp;
                				}
								//numbered pages
								$pageCount < 10 ? $i = 1 : $i = $startPage;
                				for ($i; $i <= $endPage; $i++) {
                					if ($i != $currentPage) {
                					    $temp = $_GET['start'];
                						$_GET['start'] = ($i - 1) * 10;
                                    	$href = RUPA_HOME_URL.'results.php?' . str_replace('&', '&amp;',http_build_query($_GET));
                                    	print '<a href="' . $href . '">' . $i . '</a> | ';
                                    	$_GET['start'] = $temp;
                                    } else {
                						print '<strong>' . $i . '</strong> |  ';
                                    }
                				}
        						
        						//next page
                				if ($_GET['start'] < $resultsCount - 10) {
                					$temp = $_GET['start'];
                				    $_GET['start'] += 10;
                					print '<a href="'.RUPA_HOME_URL.'results.php?' . str_replace('&', '&amp;',http_build_query($_GET)). '">Next</a>';
                					$_GET['start'] = $temp;
                				}
                			}
                		
?>
            		</p>
            	</div>
<?php
								}
								
								
  	    				if ($results->documentsFound > 0) {
?>
            	<!-- END Page navigation-->
            	
            	<!-- search within results form -->
            	<br />
				<form name="searchWithinForm" id="searchWithinForm" method="get" action="<?php print RUPA_HOME_URL.'results.php'; ?>" class="search_within_form">
					<fieldset>
						<p>
							<label for="googleSearchWithinBox" class="">Search within results: </label>
<?php
							// searching within results we need to reset the start so returned results will begin on page 1
							$_GET['start'] = 0;
							foreach ($_GET as $getName => $getValue) {
								if (is_array($getValue)) {
									foreach ($getValue as $subName => $subValue) {	
?>
							<input type="hidden" name="<?php print $getName; ?>" value="<?php print htmlentities($subValue); ?>" />
<?php
									}
								}
								else {
									if ($getName != 'q') {
?>
							<input type="hidden" name="<?php print $getName; ?>" value="<?php print htmlentities($getValue); ?>" />
<?php										
									}	
								}
							}
?>							
							<input type="hidden" name="pre_q" value="<?php print $htmlSafeQuery; ?>" />
							<input class="keyword_field" type="text" name="q" id="googleSearchWithinBox" value="" size="35" />
							<input type="submit" name="googleSearchSubmit" value="Search" class="small_button" />
						</p>
					</fieldset>
				</form>

                <br class="clear"  />
<?php
					}
?>
            </div>
 <?php
	}
	else {
?>
		<p class="first">You must enter a <strong>Search Term</strong>.</p>
<?php
	}
?> 	    
	</div>

	<!-- Drawer -->	
	<?php include_once("includes/drawer.php"); ?> 

</body>
</html>
