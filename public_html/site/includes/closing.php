			
<?php
	if (!toggleColumn('include') && !$indexPage && $breadcrumb != 'homeInfo') {
		include($HOME . "site/includes/structure/rightColumn.php");
	}
?>
						
			</div><!-- End content -->
			
		</div>
<?php
	if (!toggleColumn('include')) {
		include($HOME . "site/includes/structure/column.php");
	}
?>
		<br class="clear" />

	<?php include($HOME . "site/includes/structure/footer.php"); ?>
</div>



<!-- #################################### -->
<?php
    if (defined('ANALYTICS_INCLUDE') && ANALYTICS_INCLUDE != '') {
        print ANALYTICS_INCLUDE;
    }
?>
</body>
</html>
