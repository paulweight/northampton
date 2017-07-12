<?php
    require_once('includes/login_header.php');
	require_once('rupa/JaduRupaSearchLog.php');
	require_once('rupa/JaduRupaCollection.php');
	require_once('rupa/JaduRupaAppliance.php');
    

	$liveAppliances = getRupaAppliances('live', true, '=');
	if (empty($liveAppliances)) {
		header('Location: '.getSiteRootURL().'/search/offline.php');
		exit;
	}
	
	define('PAGE_SEARCH_RESULT_COUNT', 10);
	define('MAXIMUM_NAV_PAGE_COUNT', 10);

	if ($frontend == '') {
		$frontend = 'default_frontend';
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<title><?php print RUPA_INSTALLATION_NAME; ?> - Advanced Search</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="search" type="application/opensearchdescription+xml" href="<?php print getStaticContentRootURL(); ?>/search/opensearch.php" title="<?php print RUPA_INSTALLATION_NAME; ?>" />
	<link rel="stylesheet" type="text/css" href="<?php print RUPA_HOME_URL; ?>styles/<?php print encodeHtml(RUPA_STYLESHEET); ?>" media="screen" />
	<link rel="Shortcut Icon" type="image/x-icon" href="<?php print RUPA_HOME_URL; ?>favicon.ico" />
	
	<script src="<?php print RUPA_HOME_URL; ?>javascript/rupa.js" type="text/javascript"></script>	
	
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
<?php include_once('includes/search_mast.php'); ?>

<div id="trackrefine">
		<p class="googstrip">Advanced Search</p>
		<form name="searchAreaForm" id="searchAreaForm" method="get" action="<?php print RUPA_HOME_URL.'results.php'; ?>" class="refine_search_form">
			<fieldset>
				<legend>Refine Search</legend>
					<!-- Keyword -->

					<span>
						<label for="googleSearchBox1">With <strong>all</strong> the words: </label>
						<input class="keyword_field" type="text" name="q" id="googleSearchBox1" value="" />
					</span>
					<input class="big_button" type="submit" name="advancedSearchSubmit" value="Search" />
					<span>
						<label for="googleSearchBox1">With the <strong>exact phrase</strong>: </label>
						<input class="keyword_field" type="text" name="quoteQuery" id="googleSearchBox1" value="" />
					</span>
					<span>
						<label for="googleSearchBox1">With <strong>at least one</strong> of the words: </label>
						<input class="keyword_field" type="text" name="orQuery" id="googleSearchBox1" value="" />
					</span>

					<span>
						<label for="googleSearchBox1"><strong>Without</strong> the words: </label>
						<input class="keyword_field" type="text" name="excludeWords" id="googleSearchBox1" value="" />
					</span>

					<input name="searchType" value="advanced" type="hidden" />

					<span>
						<label for="result_number">Number of results per page:</label>
						<select class="select_group wide" id="result_number" name="numToShow">
							<option value="10">10 results</option>
							<option value="20">20 results</option>
							<option value="30">30 results</option>
							<option value="50">50 results</option>
							<option value="100">100 results</option>
						</select>
					</span>

					<span>
						<label>Return results:</label>

						<select class="select_group" name="fileFormatInclusion" id="fileFormatInclusion">
							<option value="">Only as</option>
							<option value="-">Excluding</option>
						</select>

						<select class="select_group" name="fileFormat" id="fileFormat">
							<option value="">any format</option>
							<option value="pdf">Adobe Acrobat (.pdf)</option>
							<option value="doc">Microsoft Word (.doc)</option>
							<option value="xls">Microsoft Excel (.xls)</option>
							<option value="ppt">Microsoft Powerpoint (.ppt)</option>
							<option value="rtf">Rich text format (.rtf)</option>
						</select>
					</span>

			</fieldset>

			<fieldset>
				<legend>Groups and subcollections</legend>
				<!-- Refine search -->
				<?php include_once("includes/refine.php"); ?>
			</fieldset>		
		</form>
		<div class="clear"></div>
</div>
		<!-- Draw -->	
		<?php include_once("includes/footer.php"); ?> 

</body>
</html>
