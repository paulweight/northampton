<?php
    $url = ($_SERVER['HTTPS']) ? 'https://' : 'http://';
    $url .= DOMAIN . $_SERVER['REQUEST_URI'];
    $url = htmlentities($url);
?>
    <div class="sociable">
    <h3>Bookmark this page</h3>
    	<ul>
    		<li class="digg"><a href="http://digg.com/submit?phase=2&amp;url=<?php print $url; ?>&amp;title=<?php print urlencode($MAST_HEADING); ?>">digg</a></li>
    		<li class="delicious"><a href="http://delicious.com/post?url=<?php print $url; ?>&amp;title=<?php print urlencode($MAST_HEADING); ?>">delicious</a></li>
    		<li class="stumbleupon"><a href="http://www.stumbleupon.com/submit?url=<?php print $url; ?>&amp;title=<?php print urlencode($MAST_HEADING); ?>">StumbleUpon</a></li>
    		<li class="reddit"><a href="http://reddit.com/submit?url=<?php print $url; ?>&amp;title=<?php print urlencode($MAST_HEADING); ?>">Reddit</a></li>
    		<li class="facebook"><a href="http://www.facebook.com/share.php?u=<?php print $url; ?>">Facebook</a></li>
    		<li class="mixx"><a href="http://www.mixx.com/submit?page_url=<?php print $url; ?>&amp;title=<?php print urlencode($MAST_HEADING); ?>">Mixx</a></li>
    		<li class="google"><a href="http://www.google.com/bookmarks/mark?op=edit&bkmk=<?php print $url; ?>&amp;title=<?php print urlencode($MAST_HEADING); ?>">Google</a></li>
    		<li class="linkedin"><a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php print $url; ?>&amp;title=<?php print urlencode($MAST_HEADING); ?>">LinkedIn</a></li>
    		<li class="live"><a href="https://favorites.live.com/quickadd.aspx?marklet=1&url=<?php print $url; ?>&amp;title=<?php print urlencode($MAST_HEADING); ?>">Live</a></li>
    		<li class="newsvine"><a href="http://www.newsvine.com/_tools/seed&save?u=<?php print $url; ?>&amp;h=<?php print urlencode($MAST_HEADING); ?>">NewsVine</a></li>
    	</ul>
    </div>