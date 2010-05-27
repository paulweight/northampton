<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("JaduMetadata.php");
	include_once("JaduCategories.php");
	include_once("ePayments/JaduEpaymentsProducts.php");
	include_once("ePayments/JaduEpaymentsHomepage.php");
	include_once("xforms2/JaduXFormsFormEpaymentsIntegration.php");
	
	$homepage = new EpaymentsHomepage();
	
	if (isset($_GET['categoryID'])) {
		$allProducts = getAllProductsWithCategory ($_GET['categoryID'], true);
		
		//	Category Links
		$lgclList = new CategoryList();
		$allCategories = $lgclList->getChildCategories($_GET['categoryID']);
		$categories = filterCategoriesInUse($allCategories, EPAYMENTS_PRODUCT_APPLIED_CATEGORIES_TABLE, true);
		$categoriesSize = sizeof($categories);
		
		//	Category Links
		$currentCategory = $lgclList->getCategory($_GET['categoryID']);
		$dirTree = $lgclList->getFullPath($categoryID);		
	} 
	else {
		$allProducts = getAllProducts(true);
		$dirTree = array();
	}
	
	//	Remove any links to services that are provided through XForms Professional.
	foreach ($allProducts as $productIndex => $product) {
		$xformMappings = getAllXFormsFormEpaymentsIntegrations ('productID', $product->id);
		if (sizeof($xformMappings) > 0) {
			unset($allProducts[$productIndex]);
		}
	}
	array_merge($allProducts);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Online payments - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include("../includes/meta.php"); ?>

	<!-- general metadata -->
	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments" />

	<!-- Dublin Core Metadata -->
	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online payments" />
	
	<!-- IPSV / LGNL Metadata -->
	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council and democracy" />
</head>
<body>
<?php include("../includes/opening.php"); ?>
<!-- #################################### -->
<!-- ########### MAIN CONTENT ########### -->
	<div id="middle">

		<h1><?php print htmlentities($homepage->title);?></h1>
				
<?php
	//	Show a category heading if we have come from categorised content
	if (isset($currentCategory)) {
?>

<?php
		if (sizeof($categories) > 0) {
?>

		<p><?php print $currentCategory->name;?>:</p>
		<ul class="ul">
<?php
			foreach ($categories as $subCat) {
?>
			<li><a href="./payments.php?categoryID=<?php print $subCat->id;?>"><?php print $subCat->name; ?></a></li>
<?php
			}
?>
		</ul>

<?php
		}
	}
?>
				
<?php
	if (sizeof($allProducts) > 0) {
?>
		<p>Select the service you are interested in paying for:</p>
		<ul class="ul">
	<?php
		foreach ($allProducts as $product) {
	?>
			<li><a href="./payment_details_1.php?productID=<?php print $product->id;?>"><?php print $product->title;?></a></li>
	<?php
		}
	?>
		</ul>

<?php
	}
	else if (sizeof($categories) == 0) {
?>
		<h2>Sorry. There are no services currently available within this section</h2>
<?php
	}
?>

		<div class="fromEditor">
			<?php print $homepage->content;?>
		</div>
		
	</div><!-- End of Middle -->

	<div id="secondarycontent">
	<?php include("../includes/rightcolumn_payments.php"); ?>
	</div>
	
<!-- ###################################### -->
<?php include("../includes/closing.php"); ?>
</body>
</html>