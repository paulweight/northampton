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
                                if (strpos($navLink->url, 'http://'.RUPA_HOME_URL) !== 0) {
                                        $external = ' - links to external website';
                                }
                                else {
                                        $external = '';
                                }

?>
                        <li><a href="<?php print $navLink->url; ?>" title="<?php print $navLink->linkText.$external;?>"><?php print $navLink->linkText; ?></a></li>
<?php
                        }
?>
                </ul>
<?php
                }
?>
                <!-- NAVIGATION LINKS END -->
