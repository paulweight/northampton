<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("directoryBuilder/JaduDirectories.php");
	include_once("directoryBuilder/JaduDirectoryZones.php");
	include_once("../includes/lib.php");

	if (isset($_REQUEST['directoryID']) && is_numeric($_REQUEST['directoryID'])) {
        $directory = getDirectory($_REQUEST['directoryID']);
    }
    else {
        header("Location: http://$DOMAIN/site/index.php");
        exit();
    }

    $categories = getDirectoryCategories($parentID = -1, $directory->id);
    $splitArray = splitArray($categories);

	$breadcrumb = 'directoriesInfo';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - <?php print $directory->name; ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print $directory->title;?> directory, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> <?php print $directory->name;?> directory" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> <?php print $directory->name;?> directory" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> <?php print $directory->name;?> directory" />
    

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<p class="first"><?php print nl2br($directory->description); ?></p>

	<p>
	    You can also <a href="http://<?php print $DOMAIN;?>/site/scripts/directory_search.php?directoryID=<?php print $directory->id; ?>">advanced search</a> 
	    for a directory entry or <a href="http://<?php print $DOMAIN;?>/site/scripts/directory_az.php?directoryID=<?php print $directory->id; ?>">view the A to Z</a>.
	</p>
	
	<form action="http://<?php print $DOMAIN; ?>/site/scripts/directory_search.php" method="get" class="basic_form">
		<h2 class="legend">Search the directory</h2>
		<fieldset>
	    <input type="hidden" name="directoryID" value="<?php print $directory->id; ?>" />
		<p>
			<label for="keywords">Keywords</label>
			<input type="text" class="field" name="keywords" value="" id="keywords" /> 
			<input type="submit" value="Search" name="search" class="button" />
		</p>
		</fieldset>
	</form>

<?php
    if (sizeof($splitArray['left']) > 0 || sizeof($splitArray['right']) > 0) {
?>
    	<div class="cate_info">
    		<h2>Categories in <?php print $directory->name; ?></h2>
<?php
        if (sizeof($splitArray['left']) > 0) {
?>
    		<ul class="info_left list">
<?php
            foreach ($splitArray['left'] as $category) {
                $zone = getDirectoryZoneForCategory ($category->id);
?>
    			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/zone.php?zoneID=<?php print $zone->id ?>"><?php print $category->title; ?></a></li>
<?php
            }
?>
    		</ul>
<?php
        }

        if (sizeof($splitArray['right']) > 0) {
?>
    		<ul class="info_right list">
<?php
            foreach ($splitArray['right'] as $category) {
                $zone = getDirectoryZoneForCategory ($category->id);
?>
    			<li><a href="http://<?php print $DOMAIN; ?>/site/scripts/zone.php?zoneID=<?php print $zone->id ?>"><?php print $category->title; ?></a></li>
<?php
            }
?>
    		</ul>
<?php
        }
?>
    		<div class="clear"></div>
    	</div>
<?php
    }
?>
	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>