<?php

    session_start();
    include_once("JaduStyles.php");
	include_once("utilities/JaduStatus.php");
	include_once("marketing/JaduUsers.php");
	include_once("retail/JaduRetailProducts.php");
	include_once("retail/JaduRetailWishLists.php");
	include_once("retail/JaduRetailWishListItems.php");
	include_once("retail/JaduRetailTempShoppingBasketItems.php");	
	include_once("retail/JaduRetailCompanyDetails.php");
	include_once("retail/JaduRetailTax.php");
	
	$ETAILER_WISHLIST_TO_FRIENDS_EMAIL_TITLE = "NAME's Wishlist from COMPANY_NAME";
	$ETAILER_WISHLIST_TO_FRIENDS_EMAIL = "Hello,<br><br>NAME has created a wishlist at $DOMAIN" . 
		". They also sent you this message:<br><br>".
		"USER_COMMENTS<br><br>".
		"<b>NAME's Wish List Now Includes:</b><br>".
		"PRODUCT_LIST<br><br>".
		"We hope this list is helpful to you in shopping for NAME.<br><br>".
		"Best wishes,<br><br>".
		"Your friends at COMPANY_NAME<br><br><hr><br>".
		"NAME requested that we send this message. Your email address has been in no way recorded and will not be re-used for any other purposes. If you have questions, please view our FAQs at $DOMAIN or contact us at $DEFAULT_EMAIL_ADDRRESS<br><br>".
		"$DOMAIN";



	if (!isset($userID)) {
		header("Location: $ERROR_REDIRECT_PAGE");
		exit;
	}
	else {

		if (isset($to_wishlist)) {
			header("Location: ./wishlist.php");
			exit;
		}

		$user = getUser($userID);
		$wishlist = getWishListFromUserID ($userID);
		$wishListItems = getAllWishListItems ($wishlist->id);
		
		$company_details = getCompanyDetails();

		if (isset($submit)) {
			
			//	validate the email inputs
			$error_array = array();
			$email_array = explode(",", $emails);
			$valid_emails = array();
			foreach($email_array as $email) {
				$email = strtolower(ltrim(rtrim($email))); // format it nicely first
				if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) { 
					$error_array[] = $email;
				}
				else if (in_array($email, $valid_emails)) {
					$error_array[] = $email;
				}
				else {
					$valid_emails[] = $email;
				}
			}
			
			if (sizeof($error_array) == 0) {
			
				//	prepare content
				if ($user->forename != "" && $user->surname != "")
					$name = $user->forename . " " . $user->surname;
				else
					$name = $user->email;
	
				$product_list = "";
				if (sizeof($wishListItems) > 0) {					
					foreach($wishListItems as $item) {
						$product = getProduct($item->product_id);

						$tax = getTax($product->tax_id);
						
						$price = "";
						if ($product->discount_price == "0.00") {
							$price = $product->price;
						} 
						else {
							$price = $product->discount_price;
						}
						
						$product_list .= "$product->title (qty: $item->quantity) @ &pound;" . number_format($product->price + ($product->price * $tax->rate), 2, '.', '') . "<br>";
					}
				}

				//	send emails
				$email_title = str_replace('COMPANY_NAME', $company_details->title, $ETAILER_WISHLIST_TO_FRIENDS_EMAIL_TITLE);
				$email_title = str_replace('NAME', $name, $email_title);
				
				$email_message = str_replace('COMPANY_NAME', $company_details->title, $ETAILER_WISHLIST_TO_FRIENDS_EMAIL);
				$email_message = str_replace('NAME', $name, $email_message);
				$email_message = str_replace('USER_COMMENTS', $comments, $email_message);
				$email_message = str_replace('PRODUCT_LIST', $product_list, $email_message);
								
				mail($emails, $email_title, $START_MESSAGE.$email_message."</body></html>", $HEADER);
			}
		}
	}
	
	$breadcrumb = 'retailWishlistEmail';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Wishlist - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="wishlist, shop, commerce, basket, products, buy, gifts, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Wishlist" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Wishlist" />
	<meta name="DC.description" lang="en" content="The <?php print METADATA_GENERIC_COUNCIL_NAME;?> Wishlist" />
</head>
<body class="retail">
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
<?php
	if (isset($submit) && sizeof($error_array) == 0) {
?>

			<h2>Thank you.</h2>
			<p class="first">Your friends have been emailed regarding your wishlist.</p>
<?php
	} 
	else {
?>

		<p class="first">Let your friends and family know about your Wish List. </p>
		<p>Just fill in the address and message boxes below and submit.</p>

		<form action="http://<?php print $DOMAIN;?>/site/scripts/retail_wishlist_tell_friends.php" name="mainForm" method="post" class="basic_form">
<?php
			if (sizeof($error_array) > 0) {
				print "<p class=\"first\"> The email addresses that you have entered contain errors. They should be comma seperated, valid email addresses that are not entered more than once. <span class=\"red\">Please ammend your entry</span>.</p>";
			}
?>	
			<p><label>Email Addresses (comma seperated)</label><textarea name="emails" rows="4" cols="40" class="field"><?php print $emails;?></textarea></p>
			<p><label>Comments</label><textarea name="comments" rows="6" cols="40" class="field"><?php print $comments;?></textarea></p>
			<p class="center"><input type="submit" value="Send Email" name="submit" class="button" /></p>
		</form>

<?php
	}
?>

<!-- ###################################### -->
<?php include("../includes/closing_retail.php"); ?>