<?php
    $encodedURL = encodeHtml(urlencode(PROTOCOL . DOMAIN . $_SERVER['REQUEST_URI']));
    $encodedTitle = encodeHtml(urlencode($MAST_HEADING));
?>
    <div class="sociable">
    <h3>Bookmark this page</h3>
    	<ul>
    		<li class="digg"><a href="http://digg.com/submit?phase=2&amp;url=<?php print $encodedURL; ?>&amp;title=<?php print $encodedTitle; ?>">digg</a></li>
    		<li class="delicious"><a href="http://delicious.com/post?url=<?php print $encodedURL; ?>&amp;title=<?php print $encodedTitle; ?>">delicious</a></li>
    		<li class="stumbleupon"><a href="http://www.stumbleupon.com/submit?url=<?php print $encodedURL; ?>&amp;title=<?php print $encodedTitle; ?>">StumbleUpon</a></li>
    		<li class="reddit"><a href="http://reddit.com/submit?url=<?php print $encodedURL; ?>&amp;title=<?php print $encodedTitle; ?>">Reddit</a></li>
    		<li class="facebook"><a href="http://www.facebook.com/share.php?u=<?php print $encodedURL; ?>">Facebook</a></li>
    		<li class="google"><a href="http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=<?php print $encodedURL; ?>&amp;title=<?php print $encodedTitle; ?>">Google</a></li>
    		<li class="linkedin"><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php print $encodedURL; ?>&amp;title=<?php print $encodedTitle; ?>">LinkedIn</a></li>
    		<li class="live"><a href="https://favorites.live.com/quickadd.aspx?marklet=1&amp;url=<?php print $encodedURL; ?>&amp;title=<?php print $encodedTitle; ?>">Live</a></li>
    		<li class="newsvine"><a href="http://www.newsvine.com/_tools/seed&amp;save?u=<?php print $encodedURL; ?>&amp;h=<?php print $encodedTitle; ?>">NewsVine</a></li>
    	</ul>
    </div>