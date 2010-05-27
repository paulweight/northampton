<?php
	require_once('rupa/JaduRupaSearchLog.php');
	require_once('rupa/JaduRupaSearch.php');
	require_once('rupa/JaduRupaAppliance.php');
	require_once('rupa/JaduRupaCollection.php');
	require_once('JaduMimeTypeList.php');
	require_once('rupa/JaduRupaSeasoning.php');
	include_once('includes/login_header.php');


	define(PAGE_SEARCH_RESULT_COUNT, 10);
	define(MAXIMUM_NAV_PAGE_COUNT, 10);

	$allCollections = getAllRupaCollections();

	$defaultStylesheet = getSeasoningStylesheet(RUPA_STYLESHEET);
	
	$rupaSearch = new RupaSearch();
	
	$allowedCollections = array();
	
	foreach ($allCollections as $collection) {
		$allowedCollections[] = $collection->collectionName;
	}
		
	$sites = array();
	
	if (is_array($_GET['collections']) && sizeof($_GET['collections']) > 0) {

		foreach($_GET['collections'] as $collection) {
			if (in_array($collection, $allowedCollections)) {
				$rupaSearch->addSite($collection);
			}
		}
	}

	// override any chosen collections id the main collection button is clicked
	if (isset($_GET['googleSearchSubmitMain'])) {
		$sites = array();
		$sites[] = RUPA_MAIN_COLLECTION;
	}
	
	$sitesSize = sizeof($sites);
	
	if ($sitesSize < 1) {
		// use all allowed collections if no sites chosen
		if (sizeof($allowedCollections) > 1) {
			$siteString = '('.implode('|', $allowedCollections).')';
		}
		else {
			$siteString = $allowedCollections[0];
		}
	}
	elseif ($sitesSize == 1) {
		$siteString = $sites[0];
	}
	else {
		$siteString = '('.implode('|', $sites).')';
	}

	if ($sitesSize == sizeof($allowedCollections)) {
		$searchAll = true;
	}
	else {
		$searchAll = false;
	}

	$query = $_GET['q'];
	
	if (is_numeric($_GET['searchType'])) {
		
		$siteString == '';
		$sites = array();
		
		$chosenCollection = getGSACollections('id', $_GET['searchType']);

		foreach ($allowedCollections as $collection) {
			
			if ($collection == $chosenCollection[0]->collection_name) {
				
				$siteString = $collection;
				$sites[] = $collection;
			}
		}
		
		if ($siteString == '') {
			$siteString = '('.implode('|', $allowedCollections).')';
			$sites = $allwedCollections;
		}
		
	}
	
	if ($_GET['searchType'] == 'advanced') {
		
		// exact match
		if (!empty($_GET['quoteQuery'])) {
			$query .= ' "' . $_GET['quoteQuery'] . '" ';
		}

		// 'any' match
		if (!empty($_GET['orQuery'])) {
			$tokens = explode(" ", $_GET['orQuery']);
			$orQuery = implode(" OR ", $tokens);
			$query .= " $orQuery ";
		}

		// exclude words
		if (!empty($_GET['excludeWords'])) {
			$tokens = explode(" ", $_GET['excludeWords']);

			$excludeQuery = implode(' -', $tokens);

			$query .= " -$excludeQuery ";
		}

		// file format
		if (!empty($_GET['fileFormat'])) {
			$query .= " ".$_GET['fileFormatInclusion']."filetype:".$_GET['fileFormat'];
		}
	}
	
	if (isset($_GET['pre_q'])) {
		$query = ' '.trim($_GET['pre_q']).' '.$query;
	}
	
	$query = stripslashes($query);
	
	$google_query = array(
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
						);

	$lastQuery = stripslashes(urldecode($google_query['q']));
	$htmlQuery = htmlentities($lastQuery);
		
	$jgr = new JaduGoogleResults();
	$results = $jgr->retrieveResults(http_build_query($google_query));
	var_dump($results);
	$resultsCount = sizeof($results['GSP']['RES']['M']);

	if (is_array($results['GSP']['Synonyms']['OneSynonym'])) {
		$synonyms = $results['GSP']['Synonyms']['OneSynonym'];
	}
	else {
		$synonyms = false;	
	}
	
	//build sort links
	$sortOptions = array(
		'Sort by relevance' => '',
		'Sort by date' => 'date:D:R:d1'
	);

	$tempSort = $google_query['sort'];

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
	
	$google_query['sort'] = $tempSort;

	$htmlSafeQuery = htmlentities(stripslashes(stripslashes(trim($results['GSP']['Q']))));


	$resultsCount = sizeof($results['GSP']['RES']['M']);

	//print_r($results);
		
	$oneBoxResults = $results['GSP']['ENTOBRESULTS']['OBRES'];
	if (!is_array($oneBoxResults['MODULE_RESULT'][0]) && is_array($oneBoxResults)) {
		$temp = $oneBoxResults['MODULE_RESULT'];
		$oneBoxResults['MODULE_RESULT'] = array();
		$oneBoxResults['MODULE_RESULT'][0] = $temp;
	}


	//only log the first page request for non-blank searches
	if (!empty($google_query['q']) && empty($google_query['start'])) {
    	$searchLog = new JaduSearchLog();
    	$searchLog->user_id = $_SESSION['USER_ID'];
    	    	
    	$searchLog->term = html_entity_decode($htmlSafeQuery);
    	
    	$logQuery = array();
    	$logQuery['q'] = html_entity_decode($htmlSafeQuery);
    	$logQuery['searchType'] = $_GET['searchType'];
    	$logQuery['collections'] = $google_query['site'];
    	
    	$searchLog->query = http_build_query($logQuery);
    	
    	
    	$searchLog->date = time();
    	if ($searchAll) {
    		$searchLog->collections = 'all';
    	}
    	else {
    		$searchLog->collections = $google_query['site'];
    	}

    	$searchLog->insert();
   	}
		
	//print_r($results);
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<title><?php print RUPA_INSTALLATION_NAME; ?> - Results</title>
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
	
	<script src="<?php print SHARED_HOME_URL; ?>javascript/prototype.js" type="text/javascript"></script>
	<script src="<?php print SHARED_HOME_URL; ?>javascript/scriptaculous.js" type="text/javascript"></script>
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

		if ($results['GSP']['RES']['M'] > 0) {
		?>
			Results <strong><?php print $results['GSP']['RES']['!SN'];?> - <?php print $results['GSP']['RES']['!EN'];?></strong> of about <strong><?php print $results['GSP']['RES']['M'];?></strong>. <strong>(<?php print $results['GSP']['TM'];?>)</strong> seconds
		<?php
		}else{
		?>
			No results were found.
<?php
		}
?>
		</p>
		
        			
		<p class="first">You searched for <strong><?php print $htmlSafeQuery; ?></strong>.</p>
		
		<!-- Search Key -->
		<div id="searchKey">
			<h3>Refine by:</h3>
			<ul id="keyList">
<?php
			$collectionImageLinks = array();
		
			foreach($gsaCollections as $coll){
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
				$_GET['collections'][] = $coll->collection_name;
				$_GET['start'] = '';
				$_GET['fileFormatInclusion'] = '';
				$_GET['fileFormat'] = '';
				$keyQuery = http_build_query($_GET);
				$collectionImageLinks[$coll->image_id] = $keyQuery;
				
?>
				<li><a href="<?php print RUPA_HOME_URL.RUPA_RESULTS_PATH.'?'.$keyQuery; ?>" title="<?php print $coll->friendly_name; ?>"><img alt="" src="<?php print RUPA_HOME_URL.'images/'.$image->image; ?>" /><?php print $coll->friendly_name; ?></a></li>

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
					<p class="first">You could also try: <a href="<?php print RUPA_HOME_URL.RUPA_RESULTS_PATH.'?'.htmlentities($synonymQuery); ?>"><?php print $synonyms['!']; ?></a>.</p>
<?php	
						
					}

    				if (is_array($results['GSP']['Spelling']) && is_array($results['GSP']['Spelling']['Suggestion'])) {
    				    $temp = $_GET['q'];
    					$_GET['q'] = $results['GSP']['Spelling']['Suggestion']['!q'];
    					$query = http_build_query($_GET);
    					$_GET['q'] = $temp;
?>
           			<p class="first">
        				Did you mean: <strong><a href="<?php print RUPA_HOME_URL.RUPA_RESULTS_PATH.'?'.htmlentities($query); ?>" class="copy"><?php print htmlentities($results['GSP']['Spelling']['Suggestion']['!q']); ?></a></strong>
        			</p>
<?php
           				} else if ((count($results['GSP']['RES']['R']) == 0) && (!is_array($results['GSP']['GM']))) {
?>
        			<p class="first"><strong>Sorry, no results were found.</strong></p>
<?php
        				} else if ($jgr->comments) {
?>
            		<p class="first"><strong><?=$jgr->comments;?></strong></p>
<?php
    				}
?>
<?php
           			if (is_array($results['GSP']['GM'])) {
           				
           				//make an array if we only have one keymatch
           				if (!empty($results['GSP']['GM']['GL'])) {
           					$temp = array();
           					$temp[0] = array();
           					$temp[0]['GL'] = $results['GSP']['GM']['GL'];
           					$temp[0]['GD'] = $results['GSP']['GM']['GD'];
           					$results['GSP']['GM'] = array();
           					$results['GSP']['GM'][0] = $temp[0];
           				}

						foreach ($results['GSP']['GM'] as $km) {
?>
          			<!-- key match result box -->
        			<div class="keymatch">
        				<h2><a  title="<?=strip_tags($km['GD']);?>" href="<?php print htmlentities($km['GL']); ?>"><?php print $km['GD']; ?></a></h2>
        					<p class="url"><?php print htmlentities($km['GL']); ?></p>
        			</div>
<?php
						}

        			}
?>

            		<!-- search result box -->
<?php
        			if (is_array($results['GSP']['RES']['R'])) {
        				if (sizeof($results['GSP']['RES']['R']) > 0) {
?>
					<p id="sorting" ><?php
						print $sort; 
?>
					</p>
<?php   				
						}

        				//	create the 0th item if only 1 result is returned
        				if (!is_array($results['GSP']['RES']['R'][0])) {
        					$tempResult = $results['GSP']['RES']['R'];
        					$results['GSP']['RES']['R'] = array();
        					$results['GSP']['RES']['R'][0] = $tempResult;
        				}

            			foreach ($results['GSP']['RES']['R'] as $result) {
            			// check cache is available - $element->cacheAvailable
            			$cacheLink = RUPA_HOME_URL.'scripts/google_cache.php?url='.htmlentities($result['U']).'&CID='.$result['!CID'];
        				$cacheLink = str_replace('&', '&amp;', $cacheLink);
?>
        			<div class="googresult">
<?php

					$result['U'] = str_replace('mcc.jadu.co.uk', 'www.manchester.gov.uk', $result['U']);
					$result['UE'] = str_replace('mcc.jadu.co.uk', 'www.manchester.gov.uk', $result['UE']);
					

        			        //get the collection for this result
        			        $collections  = getCollectionForUrl($result['U']);
        			        $images = array();
        			        if (is_array($collections)) { 
								foreach($collections as $collection) {
									$images[$collection->friendly_name] = $collectionImages[$collection->image_id]; 
								}
							}
?>
        				<div class="coll_image">
<?php
        				        foreach ($images as $name => $image) {
?>
        				            <a href="<?php print RUPA_HOME_URL.RUPA_RESULTS_PATH.'?'.$collectionImageLinks[$image->id]; ?>" title="Refine results: <?php print $name; ?>" class="showtooltip"><img src="<?php print RUPA_HOME_URL.'images/'.$image->image; ?>" alt="" /></a><br />
<?php
        				        }
?>
        				</div>      

					<h2>
			<?php
						switch ($result['!MIME']) {
							case '':
							case 'text/html':
							case 'text/plain':
								$cached = 'cached';
								break;
							default:
								$cached = 'HTML Version';
								break;
						}

						$icon = getIconForMimeType($result['!MIME']);

						$output = $_GET['output'];
						$_GET['output'] = 'xml_no_dtd';
						$hrefString = 'https://'.GSA_ADDRESS.'/search?q=cache'.$result['!CID'].':'.str_replace('http://', '', $result['UE'].'&amp;'.http_build_query($_GET).'&amp;proxystylesheet=default_frontend');
						$_GET['output'] = $output;
?>
						<a title="<?php print strip_tags($result['T']);?>" href="<?php print str_replace('</b>', '</strong>', str_replace('<b>', '<strong>',  str_replace('&', '&amp;', $result['U']))); ?>"><?php if ($result['T'] == '') { print '[no title]'; } else { print  str_replace('</b>', '</strong>', str_replace('<b>', '<strong>', $result['T'])); } ?></a>
<?php
						if($icon != ''){
?>
						<img alt="<?php print $result['!MIME']; ?>" src="<?php print RUPA_HOME_URL.'images/file_type_icons/'.$icon; ?>" />
<?php
						}
?>
					</h2>
									
					<p><?php print str_replace('</b>', '</strong>', str_replace('<b>', '<strong>', $result['S'])); ?></p>
					<?php
					//get the date if available
					?>
					
					<!-- only if cache is available -->
					<p><span class="url"><?php print str_replace("http://thesource.lbsouthwark.ad.southwark.gov.uk/", "http://thesource/", str_replace('&', '&amp;', $result['U'])); ?> - <?php if ($result['HAS']['C']['!SZ'] != '') { print $result['HAS']['C']['!SZ']; } if (($result['FS']['!NAME'] == 'date')  && ($results['FS']['!VALUE'] != '')) { print $result['FS']['!VALUE']; } ?></span>
<?php
	if (defined('RUPA_SHOW_CACHED_RESULTS') && RUPA_SHOW_CACHED_RESULTS) {
?>
					 - <a class="cache"  href="<?php 
						//print htmlentities($result['U']);
						print $hrefString;
						 ?>"><?php print $cached; ?></a>
<?php
	}
?>
					</p>
				</div>
				<!-- END of googresult -->
<?php
        				}
        			}
?>
</div>
<?php
        				$pageCount = floor($results['GSP']['RES']['M'] / PAGE_SEARCH_RESULT_COUNT) + 1;

								// if number of pages is a multiple of ten we don't need the last page
						 		if (($results['GSP']['RES']['M'] % 10) == 0) {
						 			$pageCount--;
						 		}

        				if ($pageCount > 1) {
?>
            		<!-- Page navigation -->
        			<div id="pagenav">
<?php
                			$currentPage = (($results['GSP']['RES']['!SN'] - 1) / 10) + 1;
                			if ($currentPage > MAXIMUM_NAV_PAGE_COUNT / 2) {
            					$startPage = max(1, intval($currentPage - (MAXIMUM_NAV_PAGE_COUNT / 2)));
                			 	$endPage = min($pageCount, intval($currentPage + (MAXIMUM_NAV_PAGE_COUNT / 2)));
                			 } else {
            					$startPage = 1;
                			 	$endPage = $startPage + (MAXIMUM_NAV_PAGE_COUNT - 1);
                			 }
                			 $endPage = min($endPage, $pageCount);
                    		if (count($results['GSP']['RES']['R']) > 0) {
?>
                			<p>Result Page:
<?php
								//previous page
                				if ($results['GSP']['RES']['NB']['PU']) {
                				    $temp = $_GET['start'];
                				    $_GET['start'] = $results['GSP']['RES']['!SN'] - 11;
                					print '<a href="'.RUPA_HOME_URL.RUPA_RESULTS_PATH.'?' . str_replace('&', '&amp;', http_build_query($_GET)).'">Previous</a> ';
                					$_GET['start'] = $temp;
                				}

								//numbered pages
                				for ($i = $startPage; $i <= $endPage; $i++) {
                					if ($i != $currentPage) {
                					    $temp = $_GET['start'];
                						$_GET['start'] = ($i - 1) * 10;
                                    	$href = RUPA_HOME_URL.RUPA_RESULTS_PATH.'?' . str_replace('&', '&amp;',http_build_query($_GET));
                                    	print '<a href="' . $href . '">' . $i . '</a> | ';
                                    	$_GET['start'] = $temp;
                                    } else {
                						print '<strong>' . $i . '</strong> |  ';
                                    }
                				}
        						
        						//next page
                				if ($results['GSP']['RES']['NB']['NU']) {
                					$temp = $_GET['start'];
                				    $_GET['start'] = $results['GSP']['RES']['!EN'];
                					print '<a href="'.RUPA_HOME_URL.RUPA_RESULTS_PATH.'?' . str_replace('&', '&amp;',http_build_query($_GET)). '">Next</a>';
                					$_GET['start'] = $temp;
                				}
                			}
                		
?>
            		</p>
            	</div>
<?php
								}
								
								
  	    				if (sizeof($results['GSP']['RES']['R']) > 0) {
?>
            	<!-- END Page navigation-->
            	
            	<!-- search within results form -->
            	<br />
				<form name="searchWithinForm" id="searchWithinForm" method="get" action="<?php print RUPA_HOME_URL.RUPA_RESULTS_PATH; ?>" class="search_within_form">
					<fieldset>
						<p>
							<label for="googleSearchWithinBox" class="">Search within results: </label>
<?php
							foreach ($_GET as $getName => $getValue){
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
    	    
	</div>
	<!-- Drawer -->	
	<?php include_once("includes/drawer.php"); ?> 

</body>
</html>