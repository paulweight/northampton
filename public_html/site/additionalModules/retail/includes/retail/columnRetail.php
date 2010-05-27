<?php
	include_once('retail/JaduRetailCategories.php');
	include_once("utilities/JaduNavWidgets.php");

	$productCategories = getRetailCategories();
	
	$allWidgets = getAllNavWidgets();        
?>
<div id="column_nav">

	<h2 class="catalogue"><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_index.php">Catalogue</a></h2>
	<ul class="navWidget">
<?php 
		foreach($productCategories as $productCategory) {
?>
		<li><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_products.php?category_id=<?php print $productCategory->id;?>"><?php print $productCategory->title;?></a></li>
<?php
		}
?>
	</ul>

<?php
		if (sizeof($allWidgets) > 0) {
			foreach ($allWidgets as $widget) {
				$allLinks = getAllNavWidgetLinksInNavWidget ($widget->id);
?>
		<h2 class="navWidget"><?php print $widget->title;?></h2>
		<ul class="navWidget">
<?php
			foreach ($allLinks as $widgetLink) {
				$key = "";
				if ($widgetLink->accessKey != "") {
					$key = ' accesskey="'.$widgetLink->accessKey.'"';
				}
				print '<li><a href="' . htmlentities($widgetLink->link) . '" title="' .  stripslashes(htmlentities($widgetLink->title)) . '." ' . $key . '>' . stripslashes(htmlentities($widgetLink->title)) . '</a></li>';
			}
?>
		</ul>
<?php
			}
		}
?>

</div>