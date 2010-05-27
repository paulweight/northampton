<?php
	include_once('JaduConstants.php');
    include_once('utilities/JaduStatus.php');
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("intranet/JaduForSaleBoardItems.php");
	include_once("intranet/JaduForSaleBoardCategories.php");
	include_once("intranet/JaduForSaleBoardItemsToCategories.php");
	include_once("JaduUpload.php");

	$errors = array();

	if (isset($_POST['delete']) && $_POST['confirmDelete'] == '1') {
		deleteForSaleCategoriesAssignedToItem($_GET['itemID']);
		deleteForSaleItem($_GET['itemID']);

		header("Location: http://$DOMAIN/site/scripts/for_sale_index.php");
		exit();
	}

	if (isset($_GET['itemID'])) {

		$item = getForSaleItem($_GET['itemID']);
		$itemCategories = getAllForSaleCategoriesAssignedToItem($item->id);

		// kick the user to the index page if they did not create this item.
		if ($item->userID != $_SESSION['userID']) {
			header("Location: http://$DOMAIN/site/scripts/for_sale_index.php");
			exit();
		}
	}

	if (isset($_POST['addItem']) || isset($_POST['updateItem'])) {

		if (isset($_POST['addItem'])) {
			$item = new ForSaleItem();
		}

		$item->id = $_POST['id'];
		$item->userID = $_SESSION['userID'];
		$item->advertType = $_POST['advertType'];

		if (!empty($_POST['displayUntilMonth']) && !empty($_POST['displayUntilDay']) && !empty($_POST['displayUntilYear'])) {
			$item->displayUntilDate = mktime(23, 59, 59, $_POST['displayUntilMonth'], $_POST['displayUntilDay'], $_POST['displayUntilYear']);
		}
		else {
			$item->displayUntilDate = time();
		}

		//	re-assign the image to the item if updating, otherwise leave the image blank.
		//	The image will be uploaded and added to the item later.
		if (isset($_POST['updateItem'])) {
			$item->imageFilename = $item->imageFilename;
			$item->datePublished = date("YmdHis", $item->datePublished);
		}
		else {
			$item->imageFilename = '';
			$item->datePublished = time();
		}

		$item->title = $_POST['title'];
		$item->description = $_POST['description'];
		$matches = array();

		preg_match("/[^0-9]*([0-9]+\.?[0-9]{0,2}).*/", $_POST['price'], $matches);
		if (sizeof($matches) > 1) {
			$item->price = $matches[1];
		}
		else {
			$item->price = '';
		}

		$item->priceNegotiable = $_POST['priceNegotiable'];
		$item->contactName = $_POST['contactName'];
		$item->contactEmail = $_POST['contactEmail'];
		$item->contactPhone = $_POST['contactPhone'];
		$item->viewCount = 0;

		$errors = validateForSaleItem($item);

		if (!isset($_POST['categories'])) {
			$errors['categories'] = true;
		}
		//	if there are errors create item to category 
		//	objects so that they can be displayed
		else if (sizeof($errors) > 0) {

			$itemCategories = array();

			foreach ($_POST['categories'] as $catID) {
				$iToC = new ForSaleItemToCategory();
				$iToC->itemID = $item->id;
				$iToC->categoryID = $catID;

				$itemCategories[] = $iToC;
			}
		}

		if (sizeof($errors) < 1) {

			// add or update the for sale item record
			if (isset($_POST['addItem'])) {
				$item->id = newForSaleItem($item);
			}
			elseif(isset($_POST['updateItem'])) {

				updateForSaleItem($item);

				deleteForSaleCategoriesAssignedToItem($itemID);
			}

			// assign all of the selected categories
			foreach ($_POST['categories'] as $catID) {
				$iToC = new ForSaleItemToCategory();
				$iToC->itemID = $item->id;
				$iToC->categoryID = $catID;

				newForSaleItemToCategory($iToC);
			}

			// upload the image if one has been selected
			if (isset($_FILES['imageFilename']['name']) && !empty($_FILES['imageFilename']['name'])) {

				//	Strip some illegal characters
				$cleanFilename = str_replace(" ", "_", $_FILES['imageFilename']['name']);
				$cleanFilename = str_replace(",", "_", $cleanFilename);
				$cleanFilename = str_replace("/", "-", $cleanFilename);
				$cleanFilename = str_replace("\\", "-", $cleanFilename);
				$cleanFilename = str_replace("'", "", $cleanFilename);
				$cleanFilename = str_replace("\"", "", $cleanFilename);

				$cleanFilename = checkFileNameClash($cleanFilename, $HOME."images/");

				$destination = $HOME."images/$cleanFilename";

				uploadFile($_FILES['imageFilename']['tmp_name'], $destination);
				
				$item->imageFilename = $cleanFilename;
				
				updateForSaleItem($item);
			}
			
			header("Location: http://$DOMAIN/site/scripts/for_sale_index.php");
			exit();
		}
	}

	$categories = getAllForSaleCategories();
	
	$breadcrumb = 'forSaleAdmin';
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

	<script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/prototype.js"></script>

	<script type="text/javascript">

		/**
		* Check that a category has not been added
		*/
		function hasCategoryBeenAdded(categoryID)
		{
			categories = document.getElementsByClassName('selectedCats');

			for (var i = 0; i < categories.length; i++) {
				if (categories[i].value == categoryID) {
					return true;
				}
			}

			return false;
		}

		/**
		* Add a category to the list displaying what categories have been added
		* and create a hidden input with the category id
		*/
		function addCategory()
		{
			categoryID = $("category").options[$("category").selectedIndex].value;
			categoryTitle = $("category").options[$("category").selectedIndex].innerHTML;

			if (categoryID != '' && !hasCategoryBeenAdded(categoryID)) {
				new Insertion.Bottom('selectedCategories', '<span id="catDisplay_' + categoryID + '">' + categoryTitle + ' - <a href="#" onclick="deleteCategory(' + categoryID + '); return false;">Delete<\/a><\/span><br \/>');
				new Insertion.Bottom('itemForm', '<input type="" id="hiddenCategory_' + categoryID + '" class="selectedCats" name="categories[]" value="' + categoryID + '" \/>');
			}
			return false;
		}

		/**hidden
		* Delete the category from the list of those selected and delete the respective hidden input.
		*/
		function deleteCategory(categoryID)
		{
			categoryLiToDelete = $('hiddenCategory_' + categoryID);
			Element.remove(categoryLiToDelete);

			categoryLiToDelete = $('catDisplay_' + categoryID);
			Element.remove(categoryLiToDelete);
		}

		function updateTitlePreview ()
		{
			if ($('itemTitle').value == '') {
				$('title_preview').innerHTML = 'A preview of your advert title';
			}
			else {
				preview = '';
				
				if ($('service').checked) {
					preview = '<em>Service:<\/em> ';
				}
				
				if ($('wanted').checked) {
					preview = '<em>Wanted:<\/em> ';
				}
				
				if ($('sale').checked) {
					preview = '<em>For Sale:<\/em> ';
				}
				
				preview += $('itemTitle').value;
				
				$('title_preview').innerHTML = preview;
			}
		}

		window.onload = function () { updateTitlePreview(); }
	</script>

</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<p class="first">All fields are required unless stated otherwise.</p>

<?php
	if (!isset($_SESSION['userID'])) {
?>
		<h2 class="warning">You must be <a href="http://<?php print $DOMAIN; ?>/site/scripts/register.php">registered</a> and <a href="http://<?php print $DOMAIN;?>/site/index.php?sign_in=true">signed in</a> to add an item.</h2>
<?php
	}
	else {	
	if (sizeof($errors) > 0) {
?>
		<h2 class="warning">Please check <strong>fields marked !</strong> are entered correctly</h2>
<?php
	}
?>

	<form action="http://<?php print $DOMAIN; ?>/site/scripts/for_sale_item_admin.php<?php if (isset($_GET['itemID'])) print '?itemID=' . $_GET['itemID']; ?>" method="post" enctype="multipart/form-data" class="basic_form" id="itemForm">

		<input type="hidden" name="id" value="<?php (isset($_GET['itemID'])) ? print $_GET['itemID'] : print -1; ?>" />

		<fieldset>
			<legend>About the advert</legend>
			<p>
<?php
				if ($errors['advertType']) {
?>
			<em class="star">! Is this item (required)</em>
<?php
				}
				else {
?>
					<label>Is this item</label>
					<span class="clear"></span>
<?php
				}
?>
<?php
			$tabIndex = 1000;
?>
                <label for="sale">
					<!-- <input id="adType_<?php print $advertType->id; ?>" type="radio" name="advertTypeID" value="<?php print $advertType->id; ?>" <?php if ($item->advertTypeID == $advertType->id) print 'checked="checked"'; ?> />
					<?php print $advertType->title; ?> -->
					<input id="sale" type="radio" name="advertType" value="sale" <?php if ($item->advertType == 'sale' || ($item->advertType != 'service' && $item->advertType != 'wanted')) print 'checked="checked"'; ?> onclick="updateTitlePreview();" />
					An item to sell
				</label>
				<label for="service">
					<input id="service" type="radio"  name="advertType" value="service" <?php if ($item->advertType == 'service') print 'checked="checked"'; ?> onclick="updateTitlePreview();" />
					A service
				</label>
				<label for="wanted">
					<input id="wanted" type="radio" name="advertType" value="wanted" <?php if ($item->advertType == 'wanted') print 'checked="checked"'; ?> onclick="updateTitlePreview();" />
					A wanted advert
				</label>
				<span class="clear"></span>
            </p>


            <p>
				<label for="
				">
<?php
				if ($errors['categories']) {
?>
                	<em class="star">! Add Categories (required)</em>
<?php
				}
				else {
?>
					Add Categories</label>
<?php
				}
?>
                <select id="category">
                    <option value="">Please select</option>
<?php
				foreach ($categories as $category) {
?>
					<option value="<?php print $category->id; ?>"><?php print $category->title; ?></option>
<?php
				}
?>
                </select>
                <input type="button" onclick="addCategory(); return false;" value="Add" class="button" />

   				<span class="clear"></span>
            </p>
			<p id="selectedCategories">
                    <span>Your chosen categories:</span><br />
<?php
			if ((isset($_GET['itemID']) ||  isset($_POST['addItem']) || isset($_POST['updateItem'])) && sizeof($itemCategories) > 0) {
				foreach ($itemCategories as $iToC) {
					$cat = getForSaleCategory($iToC->categoryID);
?>
					<span id="catDisplay_<?php print $cat->id; ?>"><?php print $cat->title; ?> <a href="#" onclick="deleteCategory(<?php print $cat->id; ?>); return false;"> - Delete</a></span><br />
					<input type="hidden" id="hiddenCategory_<?php print $cat->id; ?>" class="selectedCats" name="categories[]" value="<?php print $cat->id; ?>" />
<?php
				}
			}
?>
            </p>


				<p>
<?php
				if ($errors['displayUntilDate']) {
?>
                	<em class="star">! Final display date (required)</em>
<?php
				}
				else {
?>
					<label>Final display date</label>
					<span class="clear"></span>
<?php
				}
?>
                <label class="jform_multipleinput"  for="displayUntilDay">dd <input type="text" id="displayUntilDay" value="<?php if (isset($_GET['itemID']) || isset($_POST['addItem']) || isset($_POST['updateItem'])) print date("d", $item->displayUntilDate); ?>" size="2" name="displayUntilDay" maxlength="2" class="datemyform" /></label>
                <label class="jform_multipleinput" for="displayUntilMonth">mm <input type="text" id="displayUntilMonth" value="<?php if (isset($_GET['itemID']) || isset($_POST['addItem']) || isset($_POST['updateItem'])) print date("m", $item->displayUntilDate); ?>" size="2" name="displayUntilMonth" maxlength="2" class="datemyform" /></label>
                <label class="jform_multipleinput" for="displayUntilYear">yy <input type="text" id="displayUntilYear" value="<?php if (isset($_GET['itemID']) || isset($_POST['addItem']) || isset($_POST['updateItem'])) print date("y", $item->displayUntilDate); ?>" size="2" name="displayUntilYear" maxlength="2" class="datemyform" /></label>
				<span class="clear"></span>
            </p>


			<p>
                <label for="AddImage">Use an image (optional)</label>
                <input id="AddImage" type="file" class="upload_image" name="imageFilename" value="" />
				<span class="clear"></span>
            </p>
                <p class="center">Your image must be no bigger than 180 pixels wide</p>

        <fieldset class="sale_item_advert">
            <legend>Create your advert</legend>

            <p>
                <label for="itemTitle">
<?php
				if ($errors['title']) {
?>
					<em class="star">! Advert title (required)</em>
<?php
				}
				else {
?>
					Advert Title
<?php
				}
?>
				</label>
				<input id="itemTitle" type="text" name="title" class="field" value="<?php print $item->title; ?>" onkeyup="updateTitlePreview();" />
				<span class="clear"></span>
            </p>
				<p class="center" id="title_preview"></p>

            <p>
				<label for="itemPrice">Price - e.g. <em>12.99 </em> (optional)</label><input id="itemPrice" name="price" type="text" class="field" value="<?php print $item->price; ?>" />
				<span class="clear"></span>
            </p>

			<p>
				<label for="negotiable">Is the price negotiable?</label>
				<select id="negotiable" class="selectwidth"name="priceNegotiable">
					<option value="1" <?php if ($item->priceNegotiable == '1') print 'selected="selected"' ?>>Yes</option>
					<option value="0" <?php if ($item->priceNegotiable == '0') print 'selected="selected"' ?>>No</option>
				</select>
				<span class="clear"></span>
            </p>

			<p>
				<label for="itemDesc">
<?php
				if ($errors['description']) {
?>
					<em class="star">! Item description (required)</em>
<?php
				}
				else {
?>
					Item description
<?php
				}
?>
				</label>
				<textarea id="itemDesc" name="description" rows="8" cols="52" class="jform"><?php print stripslashes($item->description); ?></textarea>
				<span class="clear"></span>
            </p>

			<p>
				<label for="personContactName">
<?php
				if ($errors['contactName']) {
?>
					<em class="star">! Person to contact (required)</em>
<?php
				}
				else {
?>
					Person to contact
<?php
				}
?>
				</label>
				<input id="personContactName" name="contactName" type="text" class="field" value="<?php if(isset($item->contactName)) { print $item->contactName; } else { print $user->forename ." ". $user->surname; } ?>" />
				<span class="clear"></span>
            </p>

			<p>
				<label for="personContactEmail">
<?php
				if ($errors['contactEmail']) {
?>
                	<em class="star">! Contact email address (required)</em>
<?php
				}
				else {
?>
					Contacts email address
<?php
				}

?>
				</label>
				<input id="personContactEmail" name="contactEmail" type="text" class="field" value="<?php if(isset($item->contactEmail)){ print $item->contactEmail; } else { print $user->email; } ?>" />
  				<span class="clear"></span>
            </p>

            <p>
                <label for="personContactTel">Contact telephone (optional)</label><input id="personContactTel" type="text" name="contactPhone" class="field" value="<?php print $item->contactPhone; ?>" />
				<span class="clear"></span>
            </p>

        </fieldset>

<?php
	if (isset($_GET['itemID']) && $user->id == $item->userID) {
?>
        <fieldset class="simple_form">
            <legend>Update your advert</legend>
            <p>Please check all the required details are correct before publishing.</p>
			<p class="center">
            <input type="submit" name="updateItem" value="Update Item" class="button" />
            </p>

            <p>Would you like to remove this advert from public display? If so, confirm that you would like to and press Delete. This will remove the item permanently.</p>
            <p><label for="dataProtection">I confirm I wish to delete this item. </label><input type="checkbox" id="dataProtection" name="confirmDelete" value="1"  />
                        <span class="clear"></span>
			</p>
            <p class="center"><input type="submit" value="Delete Item" class="button" name="delete" /></p>
            <span class="clear"></span>
        </fieldset>
<?php
	}
	else {
?>
		<fieldset class="simple_form">
            <legend>Publish your advert</legend>
            <p>Check all the required details are correct before publishing. You will be able to update or delete your item at any time from the your <a href="http://<?php print $DOMAIN;?>/site/scripts/user_home.php">account page</a> once signed-in.</p>
			<p class="center">
            <input type="submit" name="addItem" value="Publish Item" class="button" />
            </p>
        </fieldset>
<?php
	}
?>
	</form>
<?php
}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>