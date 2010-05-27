<?php
	include_once('utilities/JaduStatus.php');   
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("intranet/JaduForSaleBoardItems.php");
	include_once("intranet/JaduForSaleBoardCategories.php");
	include_once("intranet/JaduForSaleBoardItemsToCategories.php");

	$categoryID = -1;
	$orderBy = 'datePublished';
	$ascending = 'false';

	if (isset($_GET['categoryID'])) {
		$categoryID = $_GET['categoryID'];
		$category = getForSaleCategory($categoryID);
	}

	if (isset($_GET['ascending'])) {
		$ascending = $_GET['ascending'];
	}

	if (isset($_GET['orderBy'])) {
		$orderBy = $_GET['orderBy'];
	}
	
	$items = getAllForSaleItems($categoryID, $orderBy, $ascending);
	$latestAdded = getLatestAddedForSaleItem($categoryID);

	$categories = getAllForSaleCategories();
	
	$breadcrumb = 'forSaleIndex';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - For sale board</title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="opinions, poll, results, previous, past, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Opinion polls - <?php print $currentPoll->question;?>" />

	<meta name="DC.title" lang="en" content="<?php print $currentPoll->question;?> - <?php print METADATA_GENERIC_COUNCIL_NAME;?> Opinion Poll Results" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Opinion polls - <?php print $currentPoll->question;?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
	
<?php
	if ($categoryID != -1) {
?>
	<h2><?php print $category->title; ?></h2>
<?php
	}
?>
	<div class="display_box">
		<div class="info_left">
<?php
		if ($categoryID != -1) {
?>
			<h3>Latest in <?php print $category->title; ?></h3>
<?php
		}
        else {
			if($latestAdded->id != -1) {
?>
			<h3>Latest added <?php print date("d M y", $latestAdded->datePublished); ?></h3>
<?php
			}
			else {
?>
            <h3>No Items</h3>
<?php
			}
		}
		if(!empty($latestAdded->title)) {
?>
			<p><a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_item_details.php?itemID=<?php print $latestAdded->id; ?>"><?php print $latestAdded->title; ?></a></p>
			<p><strong>
			<?php 
				if (!empty($latestAdded->price) && !$latestAdded->price == 0) { 
					print '&pound;'.number_format($latestAdded->price, 2, '.', ''); 
				}
				else { 
					print 'FREE'; 
				} 
				if ($latestAdded->priceNegotiable == '1' && !empty($latestAdded->price)) print ' (negotiable)'; 
			?>
			</strong></p>
<?php
			if (!empty($latestAdded->imageFilename)) {
?>
			<a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_item_details.php?itemID=<?php print $latestAdded->id; ?>"><img alt="" src="http://<?php print $DOMAIN; ?>/images/<?php print $latestAdded->imageFilename; ?>" class="salesBoard" /></a>
<?php
			}
?>
			<p><?php print nl2br(stripslashes(substr($latestAdded->description, 0, 120))); ?>...</p>						
<?php
		}
		else {
?>
			<p>There are currently no items for sale.</p>
<?php
		}
?>
		</div>
		<div class="info_right">
			<h3>Publish a new advert</h3>
			<p>You can:</p>
				<ul class="list">
					<li>Advertise an item to sell</li>
					<li>Display a wanted advert</li>
					<li>Advertise a service</li>
				</ul>
			<p><a  href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_item_admin.php">Create a new advert</a></p>
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<form class="salesboard_categories" action="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_index.php" method="get" class="basic_form">
		<input type="hidden" name="orderBy" value="<?php print $orderBy; ?>" />
		<input type="hidden" name="ascending" value="<?php print $ascending; ?>" />
			<p>
			<label for="itemCategories">Sales board items</label>
			<select id="itemCategories" name="categoryID" >
				<option value="-1" <?php if ($_GET['categoryID'] == '-1' || !isset($_GET['categoryID'])) print 'selected="selected"'; ?>>View all</option>
<?php
		if (sizeof($categories) > 0) {
			foreach ($categories as $category) {
?>
				<option value="<?php print $category->id; ?>" <?php if ($_GET['categoryID'] == $category->id) print 'selected="selected"'; ?>><?php print $category->title; ?></option>
<?php
			}
		}
?>
			</select>
			<input type="submit" value="Go" name="submit" class="button"  />
			<span class="clear"></span>
			</p>
	</form>	
<?php
	if (sizeof($items) > 0) {
?>
	<table id="sales_board">
		<caption>Intranet sales board</caption>
		<tr>
			<th scope="col" class="<?php ($ascending == 'false' && $orderBy == 'title') ? print 'sales_board_fliped' : print 'sales_board_flip'; ?>">
<?php
				$tmpAscending = 'true';
				
				if ($orderBy == 'title' && $ascending == 'true') {
					$tmpAscending = 'false';
				}
?>
				<a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_index.php?categoryID=<?php print $categoryID; ?>&amp;orderBy=title&amp;ascending=<?php print $tmpAscending; ?>">Item</a>
			</th>
			<th scope="col" class="<?php ($ascending == 'false' && $orderBy == 'price') ? print 'sales_board_fliped' : print 'sales_board_flip'; ?>">
<?php
				$tmpAscending = 'true';
				
				if ($orderBy == 'price' && $ascending == 'true') {
					$tmpAscending = 'false';
				}
?>
				<a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_index.php?categoryID=<?php print $categoryID; ?>&amp;orderBy=price&amp;ascending=<?php print $tmpAscending; ?>">Price</a>
			</th>
			<th scope="col" class="<?php ($ascending == 'false' && $orderBy == 'datePublished') ? print 'sales_board_fliped' : print 'sales_board_flip'; ?>">
<?php
				$tmpAscending = 'true';
				
				if ($orderBy == 'datePublished' && $ascending == 'true') {
					$tmpAscending = 'false';
				}
?>
				<a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_index.php?categoryID=<?php print $categoryID; ?>&amp;orderBy=datePublished&amp;ascending=<?php print $tmpAscending; ?>">Added</a>
			</th>
<?php
				$tmpAscending = 'true';
				
				if ($orderBy == 'viewCount' && $ascending == 'true') {
					$tmpAscending = 'false';
				}
?>
			<th scope="col" class="<?php ($ascending == 'false' && $orderBy == 'viewCount') ? print 'sales_board_fliped' : print 'sales_board_flip'; ?>">
				<a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_index.php?categoryID=<?php print $categoryID; ?>&amp;orderBy=viewCount&amp;ascending=<?php print $tmpAscending; ?>">Views</a>
			</th>
		</tr>
<?php
	$row = 1;
	for ($i = 0; $i < sizeof($items); $i++) {
		$item = $items[$i];
?>
		<tr <?php if ($row++ % 2 == 0) print 'class="zebra"'; ?>>
			<td>
				<a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_item_details.php?itemID=<?php print $item->id; ?>">
					<?php if ($item->advertType == 'wanted') print '<em>Wanted:</em>'; ?>
					<?php if ($item->advertType == 'service') print '<em>Advert:</em>'; ?>
					<?php print $item->title; ?>
				</a>
			</td>
			<td class="col_pad"><?php if ($item->price > 0) print '&pound;' . number_format($item->price, 2, '.', ''); ?></td>
			<td class="col_pad"><?php print date("dS M", $item->datePublished); ?></td>
			<td><?php print $item->viewCount; ?></td>
		</tr>
<?php
	}
?>
	</table>
	
<?php
	}
?>

	<div id="az_index">
		<p><?php print METADATA_GENERIC_COUNCIL_NAME; ?> is providing this site on an "as is" basis and makes no representations or warranties of any kind with respect to the site or items advertised on it.</p>
		<p>In addition, <?php print METADATA_GENERIC_COUNCIL_NAME; ?> makes no representations or warranties about the accuracy, completeness or suitability for any purpose of any items advertised on this site and accepts no liability for any losses associated with any transaction.</p>
		<p><?php print METADATA_GENERIC_COUNCIL_NAME; ?> reserves the right to remove advertised items which are deemed to be unsuitable for this site.</p>
		<p>Using this site constitutes acceptance of these terms and conditions.</p>
	</div>
	
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>