<?php
	include_once('JaduConstants.php');
    include_once('utilities/JaduStatus.php');   
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("intranet/JaduForSaleBoardItems.php");
	include_once("intranet/JaduForSaleBoardCategories.php");
	include_once("intranet/JaduForSaleBoardItemsToCategories.php");
	
	if (isset($_GET['itemID'])) {
		$item = getForSaleItem($_GET['itemID']);
		
		$item->viewCount = $item->viewCount + 1;
		//$item->datePublished = date("YmdHis", $item->datePublished);
		//$item->displayUntilDate = date("Ymd235959", $item->displayUntilDate);
		updateForSaleItem($item);
		
		$item = getForSaleItem($_GET['itemID']);
		
		//	get all categories assigned to the item
		$itemCategories = getAllForSaleCategoriesAssignedToItem($item->id);
	}
	
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
	
	$breadcrumb = 'forSaleItem';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print $title;?></title>
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
    <div id="sales_detail">
		<h2>
<?php
		if (isset($_SESSION['userID']) && $_SESSION['userID'] == $item->userID) {
?>
			 <a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_item_admin.php?itemID=<?php print $item->id; ?>">Edit my item:</a>
<?php
		}
?>
		<?php print $item->title; ?></h2>
<?php
		if(!empty($item->imageFilename)) {
?>
		<img alt="<?php print $item->title; ?>" src="http://<?php print $DOMAIN; ?>/images/<?php print $item->imageFilename; ?>" class="contentimage" />
<?php
		}
?>
		<p class="date">
			Added on <?php print date("dS M y", $item->datePublished); ?> in 
<?php
		$i = 0;
		foreach ($itemCategories as $itemCat) {
			$category = getForSaleCategory($itemCat->categoryID);
?>
			<a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_index.php?categoryID=<?php print $category->id; ?>">
<?php
				print $category->title;

				if ($i++ < sizeof($itemCategories) - 1) {
					print ',';
				}
?>
			</a>
<?php
		}
?>
		</p>
		<p><strong><?php print '&pound;' . number_format($item->price, 2, '.', ''); if ($item->priceNegotiable == '1' && !empty($item->price)) print ' (negotiable)'; ?></strong>
		<p><?php print nl2br(stripslashes($item->description)); ?></p>
		
		<ul class="list">
			<li><strong>Interested?</strong></li>
			<li>This will be on display until <?php print date("dS M y", $item->displayUntilDate); ?></li>
			<li><em>Contact:</em> <?php print $item->contactName; ?></li>
			<li><em>Email:</em> <a href="mailto:<?php print $item->contactEmail; ?>"><?php print $item->contactEmail; ?></a></li>
<?php
		if (!empty($item->contactPhone)) {
?>
			<li><em>Telephone:</em> <?php print $item->contactPhone; ?></li>
<?php
		}
?>
		</ul>
    </div>

	<form class="salesboard_categories" action="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_item_details.php" method="get" class="basic_form">
		<input type="hidden" name="orderBy" value="<?php print $orderBy; ?>" />
		<input type="hidden" name="ascending" value="<?php print $ascending; ?>" />
		<input type="hidden" name="itemID" value="<?php print $_GET['itemID']; ?>" />

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
			<input type="submit" value="Go" name="submit" class="button" />
		</p>
	</form>

	<table id="sales_board">
		<caption>Intranet sales board</caption>
		<tr>
			<th scope="col" class="<?php ($ascending == 'false' && $orderBy == 'title') ? print 'sales_board_fliped' : print 'sales_board_flip'; ?>">
<?php
				if ($orderBy == 'title') {
					if ($ascending == 'true') {
						$tmpAscending = 'false';
					}
					else {
						$tmpAscending = 'true';
					}
				}
				else {
					$tmpAscending = 'true';
				}
?>
				<a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_item_details.php?itemID=<?php print $_GET['itemID'] ; ?>&amp;categoryID=<?php print $categoryID; ?>&amp;orderBy=title&amp;ascending=<?php print $tmpAscending; ?>">Item</a>
			</th>
			<th scope="col" class="<?php ($ascending == 'false' && $orderBy == 'price') ? print 'sales_board_fliped' : print 'sales_board_flip'; ?>">
<?php
				if ($orderBy == 'price') {
					if ($ascending == 'true') {
						$tmpAscending = 'false';
					}
					else {
						$tmpAscending = 'true';
					}
				}
				else {
					$tmpAscending = 'true';
				}
?>
				<a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_item_details.php?itemID=<?php print $_GET['itemID'] ; ?>&amp;categoryID=<?php print $categoryID; ?>&amp;orderBy=price&amp;ascending=<?php print $tmpAscending; ?>">Price</a>
			</th>
			<th scope="col" class="<?php ($ascending == 'false' && $orderBy == 'datePublished') ? print 'sales_board_fliped' : print 'sales_board_flip'; ?>">
<?php
				if ($orderBy == 'datePublished') {
					if ($ascending == 'true') {
						$tmpAscending = 'false';
					}
					else {
						$tmpAscending = 'true';
					}
				}
				else {
					$tmpAscending = 'true';
				}
?>
				<a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_item_details.php?itemID=<?php print $_GET['itemID'] ; ?>&amp;categoryID=<?php print $categoryID; ?>&amp;orderBy=datePublished&amp;ascending=<?php print $tmpAscending; ?>">Added</a>
			</th>
<?php
				if ($orderBy == 'viewCount') {
					if ($ascending == 'true') {
						$tmpAscending = 'false';
					}
					else {
						$tmpAscending = 'true';
					}
				}
				else {
					$tmpAscending = 'true';
				}
?>
			<th scope="col" class="<?php ($ascending == 'false' && $orderBy == 'viewCount') ? print 'sales_board_fliped' : print 'sales_board_flip'; ?>">
				<a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_item_details.php?itemID=<?php print $_GET['itemID'] ; ?>&amp;categoryID=<?php print $categoryID; ?>&amp;orderBy=viewCount&amp;ascending=<?php print $tmpAscending; ?>">Views</a>
			</th>
		</tr>
<?php
	$row = 1;
	for ($i = 0; $i < sizeof($items); $i++) {
		$item = $items[$i];
?>
		<tr <?php if ($row++ % 2 == 0) print 'class="zebra"'; ?>>
			<td>
				<a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_item_details.php?itemID=<?php print $item->id; ?>&amp;categoryID=<?php print $categoryID; ?>&amp;orderBy=viewCount&amp;ascending=<?php print $ascending; ?>">
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

	<div class="display_box">
		<h3>Publish a new advert</h3>
			<p>You can:</p>
				<ul>
					<li>Advertise an item to sell</li>
					<li>Display a wanted advert</li>
					<li>Advertise a service</li>
				</ul>
			<p><a href="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_item_admin.php">Create a new advert</a></p>
		<span class="clear"></span>
	</div>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>