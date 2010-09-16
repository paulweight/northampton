<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");
	include_once('JaduLibraryFunctions.php');
	include_once('rupa/JaduRupaGoogle.php');

	if (is_array($JADOOGLE_COLLECTIONS)) {
		$allowedCollections = array_keys($JADOOGLE_COLLECTIONS);
	}
	else {
		$allowedCollections = array();
	}

	$sites = array();
	
	if (is_array($_GET['collections']) && sizeof($_GET['collections']) > 0) {

		foreach($_GET['collections'] as $collection) {
			if (in_array($collection, $allowedCollections)) {
				$sites[] = $collection;
			}
		}
	}
	
	if (sizeof($sites) < 1) {
		$siteString = JADOOGLE_SITE;
	}
	elseif (sizeof($sites) == 1) {
		$siteString = $sites[0];
	}
	else {
		$siteString = '('.implode('|', $sites).')';
	}

	$query = $_GET['q'];

	if (!empty($query)) {

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
		
		$query = stripslashes($query);
	
	
		define(PAGE_SEARCH_RESULT_COUNT, 10);
		define(MAXIMUM_NAV_PAGE_COUNT, 10);
		
		$google_query = array(
			'q' => html_entity_decode($query),
			'client' => JADOOGLE_CLIENT,
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
		$htmlQuery = htmlentities($lastQuery, ENT_QUOTES, 'UTF-8');

		$jgr = new JaduGoogleResults();
		$results = $jgr->retrieveResults(http_build_querystring($google_query));

		if (empty($results)) {
			header("Location: ./google_advanced.php");
			exit;
		}
		
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
			$sortQuery = http_build_querystring($google_query);
			$sort = '<a href="http://'.$DOMAIN.'/site/scripts/google_results.php?'.encodeHtml($sortQuery).'">Sort by date</a> / Sort by relevance';
		}
		else{
			$google_query['sort'] = $sortOptions['Sort by relevance'];
			$sortQuery = http_build_querystring($google_query);
			$sort = 'Sort by date / <a href="http://'.$DOMAIN.'/site/scripts/google_results.php?'.encodeHtml($sortQuery).'">Sort by relevance</a>';
		}
		
		$google_query['sort'] = $tempSort;
	
		$htmlSafeQuery = encodeHtml(stripslashes(stripslashes(trim($results['GSP']['Q']))));	
	}
	
	$breadcrumb  = 'googleResults';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Search results for <?php print $htmlSafeQuery;?> - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Accessibility, dda, disability discrimination act, disabled access, access keys, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Accessibility features" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php 
	if (!empty($query)) {
?>
		<h2><span>You searched for</span> <?php print $htmlSafeQuery;?><span>.</span></h2>
    			
<?php
            if (is_array($results['GSP']['Spelling']) && is_array($results['GSP']['Spelling']['Suggestion'])) {
                //$_GET['q'] = $jgr->suggestionQuery;
                $suggQuery = $google_query;
                $suggQuery['q'] = stripslashes(urldecode($results['GSP']['Spelling']['Suggestion']['!q']));
                $query = http_build_querystring($suggQuery);
?>
    			
		<!-- For the little comments -->
		<h3>Did you mean <a href="http://<? print $DOMAIN ?>/site/scripts/google_results.php?<?php print $query;?>"><?php print htmlentities(str_replace('<b><i>', '', stripslashes(urldecode($results['GSP']['Spelling']['Suggestion']['!q']))), ENT_QUOTES, 'UTF-8');?></a>?</h3>
    			
<?php
            }
            elseif (($results['GSP']['RES']['M'] == 0) && (count($results['GSP']['GM']) == 0)) {
?>
    			
            <!-- For the little comments -->
            <h2 class="warning">Sorry, no results were found.</h2>
    			
<?php
            }
            elseif ($jgr->comments) {
?>
    			
            <p><?php print $jgr->comments;?></p>
            
<?php
            }	
					if (is_array($synonyms) && sizeof($synonyms) > 0) {
?>
						<h3>You could also try:
<?php
						$index = 1;
						foreach ($synonyms as $synonym) {
							
							$temp = $google_query['q'];
							$google_query['q'] = urldecode($synonym['!q']);
							$synonymQuery = http_build_querystring($google_query);
							$google_query['q'] = $temp;
?>
					 		<a href="http://<?php print $DOMAIN; ?>/site/scripts/google_results.php?<?php print htmlentities($synonymQuery, ENT_QUOTES, 'UTF-8'); ?>"><?php print $synonym['!']; ?></a><?php
							if (sizeof($synonyms) > $index++) {
								print ', ';
							}
						}
?>
						</h3>
<?php
					}


          if ($results['GSP']['RES']['M'] > 0) {
?>
	
	<div class="displayBox">
		<div class="displayBoxIn">
			<p>
				Results <strong><?php print $results['GSP']['RES']['!SN'];?> - <?php print $results['GSP']['RES']['!EN'];?></strong> of about <strong><?php print $results['GSP']['RES']['M'];?></strong>. Search took <strong><?php print $results['GSP']['TM'];?></strong> seconds.
			</p>
		</div>
	</div>
    					 		
<?php
    		}
?>
		<!-- ########## key match result box ########## -->
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
			<div class="search_result">
				<h3><a href="<?php print htmlentities($km['GL'], ENT_QUOTES, 'UTF-8'); ?>"><?php print $km['GD']; ?></a></h3>
				<p><?php print htmlentities($km['GL'], ENT_QUOTES, 'UTF-8'); ?></p>
			</div>
<?php
            }
          }
?>     
		<!-- ########## Search result box ########## -->
<?php  		
    		if (is_array($results['GSP']['RES']['R'])) {
    			if (sizeof($results['GSP']['RES']['R']) > 0) {
?>
					<p id="sorting" ><?php
						print $sort; 
?>
						/ <a href="http://<?php print $DOMAIN; ?>/site/scripts/google_advanced.php">Advanced search</a>
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
				
					$result['T'] = str_replace('Northampton Borough Council - ', '', $result['T']);
					$result['T'] = str_replace('<b>Northampton</b> Borough Council - ', '', $result['T']);
					$result['T'] = str_replace('Northampton <b>Borough</b> Council - ', '', $result['T']);
					$result['T'] = str_replace('Northampton Borough <b>Council</b> - ', '', $result['T']);	
					
					$result['T'] = str_replace('- Northampton Borough Council', '', $result['T']);
					$result['T'] = str_replace('- <b>Northampton</b> Borough Council', '', $result['T']);
					$result['T'] = str_replace('- Northampton <b>Borough</b> Council', '', $result['T']);
					$result['T'] = str_replace('- Northampton Borough <b>Council</b>', '', $result['T']);
					
					$result['T'] = str_replace('| Northampton Borough Council', '', $result['T']);
					$result['T'] = str_replace('| <b>Northampton</b> Borough Council', '', $result['T']);
					$result['T'] = str_replace('| Northampton <b>Borough</b> Council', '', $result['T']);
					$result['T'] = str_replace('| Northampton Borough <b>Council</b>', '', $result['T']);
					
					// check cache is available
					$cacheLink = RUPA_HOME_URL.'scripts/google_cache.php?url='.htmlentities($result['U'], ENT_QUOTES, 'UTF-8').'&CID='.$result['!CID'];
					$cacheLink = str_replace('&', '&amp;', $cacheLink);

?>

		<div class="search_result">
			<h3>
				<a href="<?php print str_replace('&', '&amp;', $result['U']);?>">
					<?php if ($result['T'] == '') { print '[no title]'; } else { print  $result['T']; } ?>
				</a>
			</h3>
			<p><?php print str_replace('<b>', '<strong>', str_replace('</b>', '</strong>', str_replace('<br>', '', str_replace('<b>...</b>', '...', $result['S'])))); ?></p>
			<p class="url"><?php print str_replace('&', '&amp;', $result['U']); ?></p>
		</div>
		
<?php
    			}
?>
		<!-- ########## End of Search result box ########## -->
<?php
 		$pageCount = floor($results['GSP']['RES']['M'] / PAGE_SEARCH_RESULT_COUNT) + 1;

		// if number of pages is a multiple of ten we don't need the last page
 		if (($results['GSP']['RES']['M'] % 10) == 0) {
 			$pageCount--;
 		}
 		
 		if ($pageCount > 1) {
?>
		
		<!-- ########## Page navigation ########## -->
	<div class="displayBox">
		<div class="displayBoxIn">
			<p>
<?php
			 		$currentPage = (($results['GSP']['RES']['!SN'] - 1) / 10) + 1;
			 		if ($currentPage > MAXIMUM_NAV_PAGE_COUNT / 2) {
			 			$startPage = max(1, intval($currentPage - (MAXIMUM_NAV_PAGE_COUNT / 2)));
			 			$endPage = min($pageCount, intval($currentPage + (MAXIMUM_NAV_PAGE_COUNT / 2)));
			 		}
			 		else {
			 			$startPage = 1;
			 			$endPage = $startPage + (MAXIMUM_NAV_PAGE_COUNT - 1);
			 		}
			 		
			 		$endPage = min($endPage, $pageCount);
			 		
			 		if (count($results['GSP']['RES']['R']) > 0) {
?>
			Result Page:
<?php
	        	if ($results['GSP']['RES']['NB']['PU']) {
	        		$temp = $google_query['start'];
              $google_query['start'] = $results['GSP']['RES']['!SN'] - 11;
	        		print '<a href="http://'.$DOMAIN.'/site/scripts/google_results.php?' . str_replace('&', '&amp;', http_build_querystring($google_query)) . '">Previous</a> ';	
	        		$google_query['start'] = $temp;
	        	}
	        	
	        	$temp = $google_query;
	        	for ($i = $startPage; $i <= $endPage; $i++) {
	        		if ($i != $currentPage) {
	        			$google_query['start'] = ($i - 1) * 10;
	        			$href = 'http://'.$DOMAIN.'/site/scripts/google_results.php?' . str_replace('&', '&amp;', (http_build_querystring($google_query)));
	        			print '<a href="' . $href . '">' . $i . '</a> | ';
	        		} else {
	        			print '<strong>' . $i . '</strong> |  ';
	        		}
	        	}
	        	$google_query = $temp;
	        	
	        	if ($results['GSP']['RES']['NB']['NU']) {
	        		$google_query['start'] = $results['GSP']['RES']['!EN'];
	        		print '<a href="http://'.$DOMAIN.'/site/scripts/google_results.php?' . str_replace('&', '&amp;',http_build_querystring($google_query)). '">Next</a>';	
	        	}
        	}
        }
?>

			</p>
		</div>
	</div>
		<!-- ########## End of Page navigation ########## -->

<?php
    		}
    }
    else {
?>    
    	<h2><span>You must enter a </span>Search Term<span>.</span></h2>
<?php    
    }
?>


		<form class="basic_form" method="get" action="http://<?php print $DOMAIN ?>/site/scripts/google_results.php">
			<p>
				<label for="searchAgain">Search again:</label>
			 	<input type="text" id="searchAgain" name="q" maxlength="256" value="<?php print $htmlSafeQuery; ?>" class="field" />
				<input type="submit" name="btnG" value="Go" class="button" />
			</p>
		</form>
		
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->		
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
