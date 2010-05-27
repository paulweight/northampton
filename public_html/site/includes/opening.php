<?php
	include_once('lib.php');
	include_once('websections/JaduAnnouncements.php');	
	include($HOME . "site/includes/structure/breadcrumb.php");
	$liveUpdate = getLiveAnnouncement();	
?>
<div id="wrapper">

	<?php include($HOME . "site/includes/structure/mast.php"); ?>

	<div id="page_wrap">
		<div id="page">
			<div id="content"<?php echo toggleColumn();  ?>>
			
			<!-- BREAD CRUMB NAVIGATION -->
			<?php include($HOME . "site/includes/structure/breadcrumb.php"); ?>

			<?php
				if(!$indexPage) {
			?>

			 <?php
				if (!empty($MAST_BREADCRUMB)) {
					print '<!-- Breadcrumb --><!-- googleoff:all -->
						<ul id="breadcrumb">
							' 
							. $MAST_BREADCRUMB .
						'</ul>
						<!-- END Breadcrumb --><!-- googleon:all -->';
				}
				else {
					print '<ul><li><a href="http://'. $DOMAIN .'/site/" title="'. METADATA_GENERIC_COUNCIL_NAME .' Homepage"><em>You are here:</em> Home</a></li></ul>';
				}
			?>

				<!-- END BREAD CRUMB -->
				<div id="mainHeading">
			<?php 
				if (basename($_SERVER['PHP_SELF']) == 'documents_info.php');
				if (sizeof($allPages) > 1) {
			?>
			
				<p class="page_down"><a href="http://<?php print $DOMAIN.$_SERVER['PHP_SELF'] ?>?<?php print str_replace('&','&amp;',$_SERVER['QUERY_STRING']); ?>#pagenavbox">View further pages</a></p>
			<?php
				}
			?>
				
				<h1><?php print $MAST_HEADING;?></h1>
			</div>
			<?php
				}
			?>