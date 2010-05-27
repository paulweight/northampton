<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("directoryBuilder/JaduDirectories.php");
	include_once("directoryBuilder/JaduDirectoryCategoryInformation.php");
	include_once("../includes/lib.php");

	if (!isset($_REQUEST['directoryID']) || !is_numeric($_REQUEST['directoryID'])) {
		header("Location: http://$DOMAIN/site/index.php");
        exit();
	}

    $directory = getDirectory($_REQUEST['directoryID']);

    $categories = getDirectoryCategories($parentID = -1, $directory->id);
    $splitCategories = splitArray($categories);

	if (sizeof($categories) == 0) {
		$records = getAllDirectoryEntries ($directory->id, $live = 1, $categoryID = -1, 
     	                                $titleMatch = '', $userSubmitteOnly = false, $orderBy = 'title', 
     	                                $orderDir = 'ASC', $offset = 0, $numRows = 20);
     	                                
     	$splitRecords = splitarray($records);
	}

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

	<div class="lead">

	
	<ul class="list">
		<li>View the <a href="http://<?php print $DOMAIN;?>/site/scripts/directory_az.php?directoryID=<?php print $directory->id; ?>">A to Z of records</a></li>
<?php
	if ($directory->allowPublicSubmissions == '1') {
?>
		<li>Make a <a href="http://<?php print $DOMAIN;?>/site/scripts/directory_submit.php?directoryID=<?php print $directory->id; ?>">submission to this directory</a></li>
<?php
	}
?>
	</ul>
	
	<div class="clear"></div>
	</div>
<?php
    if (sizeof($splitCategories['left']) > 0 || sizeof($splitCategories['right']) > 0) {
?>
    	<div class="cate_info">
    		<h2>Categories in <?php print $directory->name; ?></h2>
<?php
        if (sizeof($splitCategories['left']) > 0) {
?>
    		<ul class="info_left list">
<?php
            foreach ($splitCategories['left'] as $category) {
                $categoryInfo = getDirectoryCategoryInformationForCategory ($category->id);
?>
    			<li><a href="http://<?php print DOMAIN; ?>/site/scripts/directory_category.php?categoryInfoID=<?php print $categoryInfo->id ?>&directoryCategoryID=<?php print $category->id; ?>"><?php print $category->title; ?></a></li>
<?php
            }
?>
    		</ul>
<?php
        }

        if (sizeof($splitCategories['right']) > 0) {
?>
    		<ul class="info_right list">
<?php
            foreach ($splitCategories['right'] as $category) {
                $categoryInfo = getDirectoryCategoryInformationForCategory ($category->id);
?>
    			<li><a href="http://<?php print DOMAIN; ?>/site/scripts/directory_category.php?categoryInfoID=<?php print $categoryInfo->id ?>&directoryCategoryID=<?php print $category->id; ?>"><?php print $category->title; ?></a></li>
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

	if(sizeof($splitRecords['left']) > 0) {
?>
   	<div class="cate_info">
    	<h2>Browse <?php print $directory->name; ?></h2>
    		<ul class="info_left list">
<?php
            foreach ($splitRecords['left'] as $record) {
?>
    			<li><a href="http://<?php print DOMAIN; ?>/site/scripts/directory_record.php?recordID=<?php print $record->id ?>"><?php print $record->title; ?></a></li>
<?php
            }
?>
    		</ul>

<?php
        if (sizeof($splitRecords['right']) > 0) {
?>
    		<ul class="info_right list">
<?php
            foreach ($splitRecords['right'] as $record) {
?>
    			<li><a href="http://<?php print DOMAIN; ?>/site/scripts/directory_record.php?recordID=<?php print $record->id ?>"><?php print $record->title; ?></a></li>
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
	<form action="http://<?php print $DOMAIN; ?>/site/scripts/directory_search.php" method="get" class="basic_form">
		<h3>Search the directory</h3>
		<fieldset>
		<input type="hidden" name="directoryID" value="<?php print $directory->id; ?>" />
		<p>
			<label for="keywords">Keywords</label>
			<input type="text" class="field" name="keywords" value="" id="keywords" /> 
			<input type="submit" value="Search" name="search" class="button" />
		</p>
		<p class="center">Try the <a href="http://<?php print $DOMAIN;?>/site/scripts/directory_search.php?directoryID=<?php print $directory->id; ?>">advanced search</a></p>
		</fieldset>
	</form>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>