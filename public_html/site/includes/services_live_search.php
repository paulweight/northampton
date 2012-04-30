<!-- Live Search -->
	<form class="basic_form" action="<?php print getSiteRootURL(); ?>/site/scripts/az_home.php" method="get">
		<p id="az_live_find">
		Begin to type the name and select from the appearing choices.
			<label class="hidden" for="searchText">Search by keyword</label>
			<span>
				<input class="field" type="text" name="searchText" id="searchText" value="" />
				<img id="loading" style="display:none;" alt="Loading" src="<?php print getStaticContentRootURL(); ?>/site/images/loading.gif" />
				<noscript><input name="searchAZButton" type="submit" value="Go" class="button" /></noscript>
			</span>
		</p>
		<div id="search_results">
	<?php 
	if(isset($_GET['searchAZButton'])) {
		include(HOME . 'az_search_results.php' ); 
	}
	?>
		</div>
	</form>
	<!-- End live search -->
	<script type="text/javascript" src="<?php print getSiteRootURL(); ?>/site/javascript/livesearch.js"></script>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php print encodeHtml(GOOGLE_MAPS_API_KEY); ?>" type="text/javascript"></script>
	<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=<?php print encodeHtml(GOOGLE_MAPS_API_KEY); ?>" type="text/javascript"></script>
	<script type="text/javascript" src="<?php print getSiteRootURL(); ?>/site/javascript/services.js"></script>