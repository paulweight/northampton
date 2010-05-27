<?php
	session_start();
	include_once("JaduStyles.php"); 
	include_once('JaduConstants.php');
    include_once('utilities/JaduStatus.php');   
	include_once("intranet/JaduIntranetPersonnel.php");
	include_once("intranet/JaduIntranetPersonnelDepartments.php");
	include_once("intranet/JaduIntranetPersonnel.php");
	
	$allDepartments = getAllDepartments();
	
    $breadcrumb = 'personnelIndex';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - People Directory</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="home, homepage, index, root, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print $DOMAIN;?> is the online resource for <?php print METADATA_GENERIC_COUNCIL_NAME;?> - with council services online" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online - Tel: <?php print $address->telephone;?>" />
	<meta name="DC.identifier" content="http://<?php print $DOMAIN.$_SERVER['PHP_SELF'];?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/prototype.js"></script>
	<script type="text/javascript" src="http://<?php print $DOMAIN; ?>/site/javascript/personnel.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
		
		<p class="first">Search for a member of staff from the below options. To add new staff or update existing details <a href="http://<?php print $DOMAIN; ?>/site/scripts/contact.php" >contact us</a> or use the <a href="http://<?php print $DOMAIN;?>/site/scripts/personnel_add.php">personnel form</a>.</p>
				
		<!-- A-Z top list-->
		<div id="az_index">
			<ul>
				<li><?php if (getAllPersonnelWithInitial('A', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=A">A</a><?php } else print "<span class=\"aznone_index\">A</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('B', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=B">B</a><?php } else print "<span class=\"aznone_index\">B</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('C', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=C">C</a><?php } else print "<span class=\"aznone_index\">C</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('D', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=D">D</a><?php } else print "<span class=\"aznone_index\">D</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('E', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=E">E</a><?php } else print "<span class=\"aznone_index\">E</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('F', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=F">F</a><?php } else print "<span class=\"aznone_index\">F</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('G', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=G">G</a><?php } else print "<span class=\"aznone_index\">G</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('H', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=H">H</a><?php } else print "<span class=\"aznone_index\">H</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('I', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=I">I</a><?php } else print "<span class=\"aznone_index\">I</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('J', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=J">J</a><?php } else print "<span class=\"aznone_index\">J</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('K', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=K">K</a><?php } else print "<span class=\"aznone_index\">K</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('L', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=L">L</a><?php } else print "<span class=\"aznone_index\">L</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('M', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=M">M</a><?php } else print "<span class=\"aznone_index\">M</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('N', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=N">N</a><?php } else print "<span class=\"aznone_index\">N</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('O', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=O">O</a><?php } else print "<span class=\"aznone_index\">O</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('P', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=P">P</a><?php } else print "<span class=\"aznone_index\">P</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('Q', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=Q">Q</a><?php } else print "<span class=\"aznone_index\">Q</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('R', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=R">R</a><?php } else print "<span class=\"aznone_index\">R</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('S', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=S">S</a><?php } else print "<span class=\"aznone_index\">S</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('T', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=T">T</a><?php } else print "<span class=\"aznone_index\">T</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('U', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=U">U</a><?php } else print "<span class=\"aznone_index\">U</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('V', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=V">V</a><?php } else print "<span class=\"aznone_index\">V</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('W', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=W">W</a><?php } else print "<span class=\"aznone_index\">W</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('X', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=X">X</a><?php } else print "<span class=\"aznone_index\">X</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('Y', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=Y">Y</a><?php } else print "<span class=\"aznone_index\">Y</span>"; ?></li>
				<li><?php if (getAllPersonnelWithInitial('Z', 'surname')) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=Z">Z</a><?php } else print "<span class=\"aznone_index\">Z</span>"; ?></li>
			</ul>
			<div class="clear"></div>
		</div>

		<!-- Live search -->
		<div class="basic_form">
			<h2>Live search</h2>
			<p id="az_live_find">
				<label for="searchText">Begin to type the details you are searching for and select from the options that appear.</label>
				<span>
					<input class="field" type="text" name="searchText" id="searchText" value="" />
					<img id="loading" style="display:none;" alt="Loading" src="http://<?php print $DOMAIN;?>/site/images/loading.gif" />
				</span>
				<span class="clear"></span>
				<div id="search_results"></div>
			</p>			
		</div>
		
		<h2>Basic search</h2>

		<!-- People Search -->
		<form action="http://<?php print $DOMAIN;?>/site/scripts/personnel.php" method="post" enctype="x-www-form-encoded" class="basic_form">		
			<p>
				<label for="name" >Name</label>
				<input type="text" name="name" id="name" value="" class="field" /> 
				<input type="submit" value="Go" name="personnelSearch"  class="button" />
				<span class="clear"></span>
			</p>
		</form>

		<form action="http://<?php print $DOMAIN;?>/site/scripts/personnel.php" method="get" enctype="x-www-form-encoded" class="basic_form">
			<input type="hidden" name="viewBy" value="department" />
			<p>
				<label for="departmentID">Department</label>
				<select name="departmentID" id="departmentID">
					<option value="any" selected="selected">Any</option>
<?php
					foreach ($allDepartments as $dept) {
						if (getAllPersonnelForDepartment($dept->id)) {
?>
					<option value="<?php print $dept->id;?>"><?php print $dept->title; ?></option>
<?php
					}
				}
?>
				</select>
				<input type="submit" value="Go" name="councillorSearchSubmit" class="button"  />
				<span class="clear"></span>
			</p>
		</form>
		<!-- END People Search -->
		
		<p class="first"><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=department">View ALL Department</a></p>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>