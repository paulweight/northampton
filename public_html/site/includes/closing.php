			</div><!-- End content -->
<?php		
/*  
	SECONDARY COLUMN
	* Make sure body class is updated if ColumnSecondary div is removed.
*/
?>
		<div id="columnSecondary">
			<?php include(HOME . 'site/includes/right_supplements.php'); ?>
			
			<!-- Related information -->
			<?php include(HOME . "site/includes/related_info.php"); ?>

			<!-- The Contact box -->
			<?php include(HOME . "site/includes/contactbox.php"); ?>
			<!-- END of the Contact box -->
		</div>
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
