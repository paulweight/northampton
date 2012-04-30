<?php
	require_once('rupa/JaduRupaNavigation.php');
	
	$navigationLinks = getAllRupaNavigation();
?>
                <!-- NAVIGATION LINKS -->
<?php
                if (!empty($navigationLinks)) {
?>
                <ul id="user_nav">
<?php
                        foreach ($navigationLinks as $navLink) {
                        	                        	
                        	if ($_SERVER['SCRIPT_URI'] == $navLink->url ||
                        		$_SERVER['SCRIPT_URI'] . 'index.php' == $navLink->url ||
                        		$_SERVER['SCRIPT_URI'] ==  $navLink->url.'index.php') {
                        		// we're on this page, don't link		
?>
                        <li><?php print encodeHtml($navLink->linkText); ?></li>
<?php                        			
                        	}
                        	else {
?>
                        <li><a href="<?php print encodeHtml($navLink->url); ?>"><?php print encodeHtml($navLink->linkText); ?></a></li>
<?php
                        	}
                        }
?>
                </ul>
<?php
                }
?>
                <!-- NAVIGATION LINKS END -->
