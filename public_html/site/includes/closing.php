
<?php include(HOME . "site/includes/contactbox.php"); ?>

<?php
	if (!isset($indexPage) || !$indexPage) {
?>	
	</div>			
	<div id="content-additional">
	
	
<?php 
	if (basename($_SERVER['SCRIPT_NAME']) == 'events_index.php' || basename($_SERVER['SCRIPT_NAME']) == 'events_info.php' || basename($_SERVER['SCRIPT_NAME']) == 'events_categories.php' || basename($_SERVER['SCRIPT_NAME']) == 'events.php' || basename($_SERVER['SCRIPT_NAME']) == 'events_new.php'  || basename($_SERVER['SCRIPT_NAME']) == 'events_thanks.php' ) {
		 ?>
		<div class="clear"></div>
		<a class="button red" href="<?php print getSiteRootURL() . buildNewEventURL();?>"><span>Submit your event</span></a>
<?php		include("../includes/calendar.php");
	}
?>
	
<?php
				if (sizeof($allPages) > 1) {
?>

		<!-- No padge -->
	
	<?php
			} 
	
				if (sizeof($allPages) > 1) {
					$pageNumberPrev = $pageNumber -1;
					$pageNumberNext = $pageNumber +1;
					$pageTotal = count($allPages);
	?>
				<ul id="pagination">
					<?php { ?>
					<li><a href="<?php print getSiteRootURL() . buildDocumentsURL($document->id, $categoryID, $pageNumberPrev);?>">
					&laquo;</a></li><?php } ?>
					<li>Page <?php print $pageNumber; ?> of <?php print $pageTotal; ?></li>
					<?php { ?>
					<li><a href="<?php print getSiteRootURL() . buildDocumentsURL($document->id, $categoryID, $pageNumberNext);?>">
					&raquo;</a></li><?php } ?>
				</ul>
				<div class="clear"></div>
				
			
		
<?php
	}
	else {
?>
	

<?php } ?>	
<div class="clear"></div>
		<?php if (basename($_SERVER['SCRIPT_NAME']) == 'documents.php') { ?>
		<?php include(HOME . 'site/includes/right_supplements.php'); ?>
		<?php include(HOME . "site/includes/related_info.php"); ?>
		<div id="pageAction">
			<ul class="func">
				<li class="print"><a rel="nofollow" href="#" onclick="window.print();return false;">Print this page</a></li>
				<li class="email"><a id="emailFriendLink" rel="nofollow" href="<?php print getSiteRootURL() . buildEmailFriendURL($currentScriptEmailURL); ?>">Email to a friend</a></li>
				<li class="comment"><a href="<?php print getSiteRootURL() . buildFeedbackURL(); ?>">Comment on this page</a></li>
			</ul>
		</div>
		<?php  } ?>
	</div>
<?php  } ?>	
</div>
<?php
/*  
	PRIMARY COLUMN
	* To show/hide this column, edit site/includes/lib.php
*/
	$hideColumn = (boolean) hideColumn();
	
	if (($script != "documents_info.php" && $hideColumn == false ) || ($script == "documents_info.php" && $pageStructure->id != '2')) {
		include(HOME . "/site/includes/structure/column.php");
	}
?><div class="clear"></div></div><div id="ieFix"></div>
		<?php include(HOME . "site/includes/structure/footer.php"); ?>
</div>

<!-- #################################### -->
<?php
    if (defined('ANALYTICS_INCLUDE') && ANALYTICS_INCLUDE != '') {
        print ANALYTICS_INCLUDE;
    }
?>
</body>
</html>
