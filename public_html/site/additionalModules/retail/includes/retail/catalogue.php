<?php
	include_once('retail/JaduRetailProducts.php');
	include_once('retail/JaduRetailProductsToImages.php');
	include_once('retail/JaduRetailCategoryTree.php');
	include_once('retail/JaduRetailManufacturers.php');
	include_once('retail/JaduRetailBasket.php');
	
	$productCategories = getRetailCategories();

	$allManufacturers = getAllManufacturers();

	$basket = new Basket();
	$basket_items = $basket->items;

	$latestItems = getAllProducts('date_created', 'DESC', '0', '5', '', 'online');
	
?>
<div id="column_nav">
	<h2 class="navWidget">Your basket</h2>
	<ul  class="navWidget">
		<li><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_basket.php">You have <?php print count($basket_items); ?> item<?php if(count($basket_items) != '1') { print 's'; } ?> in your basket</a></li>
		<li><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_basket.php">View basket</a></li>
<?php 
	if (isset($_SESSION['userID'])) { 
?>
		<li><a href="http://<?php print $DOMAIN;?>/site/scripts/user_home.php">Your Account</a></li>
		<li><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_order_history.php">Order History</a></li>			
<?php 
    }  
?>
	</ul>

	<h2 class="navWidget">Catalogue</h2>
	<ul class="navWidget">
		<li><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_index.php">Catalogue index</a></li>
<?php 
		foreach($productCategories as $productCategory) {
?>
		<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/retail_products.php?category_id=<?php print $productCategory->id;?>"><?php print $productCategory->title;?></a></li>
<?php
		}
?>
	</ul>


	<h2 class="navWidget">Manufacturer</h2>
	<ul class="navWidget">
<?php
		foreach ($allManufacturers as $manufacturer) {
			print '<li><a href="http://'.$DOMAIN.'/site/scripts/retail_manufacturers.php?manufacturer_id='.$manufacturer->id.'" title="'.$manufacturer->title.'">'.$manufacturer->title.'</a></li>';
		}
?>
	</ul>


<h2 class="navWidget">What's New?</h2>
	<ul class="navWidget">
<?php
	$clones = array();
	foreach($latestItems as $latestItem) {
		if($latestItem->siblingID != '-1' && !in_array($latestItem->siblingID, $clones)) {
?>
		<li><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_product_browse.php?product_id=<?php print $latestItem->id ?>"><?php print $latestItem->title; ?></a></li>
<?php
			$clones[] = $latestItem->siblingID;
	}
		else if ($latestItem->siblingID == '-1') {
?>
		<li><a href="http://<?php print $DOMAIN;?>/site/scripts/retail_product_browse.php?product_id=<?php print $latestItem->id ?>"><?php print $latestItem->title; ?></a></li>
<?php
		}

	}
?>
	</ul>
</div>