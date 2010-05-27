<?php
	if (defined('RUPA_SHOW_USER_HISTORY') && RUPA_SHOW_USER_HISTORY) {

		require_once('rupa/JaduRupaGSACollectionImage.php');
		$collectionNamesToImages = getAllCollectionsToImages();
?>
<script language="javascript" type="text/javascript">
	 function reallyClearAll(url) { 
		var ans; 
		return window.confirm('Are you sure you want to delete ALL search history for ALL users?');
		/*if (ans == true) {
			return url;
		} else {
			return '#';
		}*/
	 }
	 
	function clearHistory(user)
	{
		var url = '<?php print RUPA_HOME_URL; ?>includes/ajax/clear_search_history.php';
		var params = 'user=' + user + '&clear=1&target=user_top';
		var ajax = new Ajax.Updater(
		{success: 'user_top'},
		url,
		{method: 'post', parameters: params, onFailure: reportError});

		var url = '<?php print RUPA_HOME_URL; ?>includes/ajax/clear_search_history.php';
		var params = 'user=' + user + '&cleared=1&target=user_last';
		var ajax = new Ajax.Updater(
		{success: 'user_last'},
		url,
		{method: 'post', parameters: params, onFailure: reportError});

		var url = '<?php print RUPA_HOME_URL; ?>includes/ajax/clear_search_history.php';
		var params = 'cleared=1&target=all_top';
		var ajax = new Ajax.Updater(
		{success: 'all_top'},
		url,
		{method: 'post', parameters: params, onFailure: reportError});

	}
	 
	function clearAllHistory()
	{
		if(reallyClearAll('') == true){
			var url = '<?php print RUPA_HOME_URL; ?>includes/ajax/clear_search_history.php';
			var params = 'clear_all=1&target=all_top';
			var ajax = new Ajax.Updater(
			{success: 'all_top'},
			url,
			{method: 'post', parameters: params, onFailure: reportError});

			var url = '<?php print RUPA_HOME_URL; ?>includes/ajax/clear_search_history.php';
			var params = 'cleared=1&target=user_last';
			var ajax = new Ajax.Updater(
			{success: 'user_last'},
			url,
			{method: 'post', parameters: params, onFailure: reportError});

			var url = '<?php print RUPA_HOME_URL; ?>includes/ajax/clear_search_history.php';
			var params = 'cleared=1&target=user_top';
			var ajax = new Ajax.Updater(
			{success: 'user_top'},
			url,
			{method: 'post', parameters: params, onFailure: reportError});
		}else{
			return false;
		}
	}

	function reportError()
	{
		alert('There was an error.');
	}

	function toggleSearchHistory()
	{
		Element.toggle('expanded_browse');
		
		if (navigator.appVersion.indexOf("MSIE") != -1) {
			Element.toggle('hidden_iframe');
		}
	}

</script>
<iframe src="" id="hidden_iframe" class="ie6_iframe_fix" style="display:none;"></iframe>

<div id="content_browse">
	<ul class="tabs">
		<li><a href="#" class="current" onclick="toggleSearchHistory(); return false;">Search History</a></li>
		<!-- <li><a href="#"  onclick="Effect.toggle('expanded_browse', 'blind'); return false;">Extra One</a></li> -->
	</ul>

	<div id="expanded_browse" class="history_list_container" style="display:none;">

		<span>
			<a href="#" onclick="toggleSearchHistory(); return false;" title="Close drawer">
			<img src="<?php print RUPA_HOME_URL; ?>images/close_arrow.png"  alt="Close drawer" title="Close drawer" /></a>
		</span>

		<dl id="user_top">
			<dt>Your top ten searches</dt>
			<?php
			$userTop = getTopSearchLogsByUserId($user->id, 'DESC', 10);
			if(!empty($userTop)){
				foreach($userTop as $search){
				?>  
			<dd>
				<a title="<?php print htmlentities(urldecode($search->term)); ?>" href="<?php print RUPA_HOME_URL.RUPA_RESULTS_PATH.'?'.htmlentities(str_replace('%5C%22', '%22', $search->query)); ?>">
				<?php print htmlentities(urldecode($search->term)); ?> <em>(<?php print $search->count; ?>)</em>
<?php
				if (($search->collections == '') || ($search->collections == 'all')) {
?> [ALL] <?php
				}
				else {
					$collectionsArray = explode('|', str_replace(array('(', ')'), array('', ''), $search->collections));
					foreach ($collectionsArray as $collectionName) {
?>
					<img src="<?php print RUPA_HOME_URL.'images/'.$collectionNamesToImages[$collectionName]->image; ?>" alt="<?php print $collectionNamesToImages[$collectionName]->friendly_name; ?>" />
<?php
					}
				}
?>
				</a>
			</dd>
			<?php  
			}
		}else{
			?>                        
		<dd>No searches found</dd>
		<?php
		}
		?>
		</dl>
	
		<dl id="user_last">
			<dt>Last ten searches</dt>
			<?php
			$userLast = getLastSearchLogsByUserId($user->id, 'DESC', 10);
			if(!empty($userLast)){
				foreach($userLast as $search){
				?>    
			<dd>
				<a title="<?php print htmlentities(urldecode($search->term)); ?>" href="<?php print RUPA_HOME_URL.RUPA_RESULTS_PATH.'?'.htmlentities(str_replace('%5C%22', '%22', $search->query)); ?>">
				<?php print htmlentities(urldecode($search->term)); ?>
<?php
				if (($search->collections == '') || ($search->collections == 'all')) {
?> [ALL] <?php
				}
				else {
					$collectionsArray = explode('|', str_replace(array('(', ')'), array('', ''), $search->collections));
					foreach ($collectionsArray as $collectionName) {
?>
					<img src="<?php print RUPA_HOME_URL.'images/'.$collectionNamesToImages[$collectionName]->image; ?>" alt="<?php print $collectionNamesToImages[$collectionName]->friendly_name; ?>" />
<?php
					}
				}
?>
				</a>
			</dd>
			<?php  
			}
		}else{
		?>
			<dd>No searches found</dd>                            
		<?php
		}
		?>
			<dd>
				<p><a href="#" title="Clear my search history" onClick="clearHistory(<?php print $user->id; ?>); return false;">Clear my search history</a></p>
			</dd>
		</dl>
		
		<dl id="all_top">
			<dt>Popular searches</dt>
			<?php
			$allTop = getTopSearchLogs('termCount', 'DESC', '', '', 10);
			if(!empty($allTop)){
				foreach($allTop as $search){
				?>   
			<dd>
				<a title="" href="<?php print RUPA_HOME_URL.RUPA_RESULTS_PATH.'?'.htmlentities(str_replace('%5C%22', '%22', $search->query)); ?>">
				<?php print htmlentities(urldecode($search->term)); ?> <em>(<?php print $search->count; ?>)</em>
<?php
				if (($search->collections == '') || ($search->collections == 'all')) {
?> [ALL] <?php
				}
				else {
					$collectionsArray = explode('|', str_replace(array('(', ')'), array('', ''), $search->collections));
					foreach ($collectionsArray as $collectionName) {
?>
					<img src="<?php print RUPA_HOME_URL.'images/'.$collectionNamesToImages[$collectionName]->image; ?>" alt="<?php print $collectionNamesToImages[$collectionName]->friendly_name; ?>" />
<?php
					}
				}
?>				
				</a>
			</dd>
			<?php  
			}
		}else{
		?>
		<dd>No searches found</dd>                            
		<?php
		}
		?>
		
		<?php
			if(is_array($userMemberOfs)){
				if(in_array(GSA_ADMIN_GROUP, $userMemberOfs)){
		?>
		<dd>
		<p><a href="#" title="Clear ALL search history" onClick="clearAllHistory(); return false;" >Clear ALL search history</a></p>
		</dd>
		<?php
				}
			}
		?>     
		
		</dl>
		<br class="clear" />
	<p>Powered by Jadu <a href="http://www.jadu.co.uk/site/index.php" title="Jadu Enterprise Content Management">Content Management with Google Appliance</a></p>

	</div>
</div>
<?php
	}
	else {
?>

	<p class="powered_by">Powered by Jadu <a href="http://www.jadu.co.uk/site/index.php" title="Jadu Enterprise Content Management">Content Management with Google Appliance</a></p>
<?php	
	}
?>
