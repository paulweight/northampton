<?php
	session_start();
	include_once('JaduConstants.php');
 	include_once('utilities/JaduStatus.php');   
	include_once("JaduStyles.php");
	include_once("intranet/JaduIntranetPersonnel.php");
	include_once("intranet/JaduIntranetPersonnelDepartments.php");

	include_once("../includes/lib.php");
	
	$orderBy = 'surname';

	if (isset($_REQUEST['orderBy'])) {
		$orderBy = $_REQUEST['orderBy'];
	}

    if (isset($_GET['viewBy']) && $_GET['viewBy'] == 'name') {
    
        $surnameInitial = 'A';

        if (isset($_GET['startsWith'])) {
            $initial = $_GET['startsWith'];
        }

        $people = getAllPersonnelWithInitial($initial, $orderBy);

        $bcText = "View by name";        
    }

    if (isset($_GET['viewBy']) && $_GET['viewBy'] == 'department') {

        $allDepartments = getAllDepartments();

        if (isset($_GET['departmentID'])) {
            
            $people = getAllPersonnelForDepartment($_GET['departmentID']);
        }

        $bcText = "View by department";
    }

    if (isset($_REQUEST['name'])) {
    	if (!empty($_REQUEST['name'])) {
    		$people = searchPersonnelByName($_REQUEST['name'], $orderBy);
    		$bcText = "Search for " . $_REQUEST['name'];
    	}
    }
    
    $breadcrumb = 'personnel';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Personnel directory</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="home, homepage, index, root, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print $DOMAIN;?> is the online resource for <?php print METADATA_GENERIC_COUNCIL_NAME;?> - with council services online" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online - Tel: <?php print $address->telephone;?>" />
	<meta name="DC.description" lang="en" content="<?php print $DOMAIN;?> is the online resource for <?php print METADATA_GENERIC_COUNCIL_NAME;?> - with council services online" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	if (isset($_GET['viewBy']) && $_GET['viewBy'] == 'name') {
?>
	<!-- A-Z top list-->
	<div id="az_index">
		<ul>
			<li><?php if (getAllPersonnelWithInitial('A', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=A&amp;orderby=$orderBy">A</a><?php } else print "<span class=\"aznone_index\">A</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('B', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=B&amp;orderby=$orderBy">B</a><?php } else print "<span class=\"aznone_index\">B</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('C', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=C&amp;orderby=$orderBy">C</a><?php } else print "<span class=\"aznone_index\">C</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('D', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=D&amp;orderby=$orderBy">D</a><?php } else print "<span class=\"aznone_index\">D</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('E', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=E&amp;orderby=$orderBy">E</a><?php } else print "<span class=\"aznone_index\">E</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('F', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=F&amp;orderby=$orderBy">F</a><?php } else print "<span class=\"aznone_index\">F</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('G', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=G&amp;orderby=$orderBy">G</a><?php } else print "<span class=\"aznone_index\">G</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('H', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=H&amp;orderby=$orderBy">H</a><?php } else print "<span class=\"aznone_index\">H</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('I', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=I&amp;orderby=$orderBy">I</a><?php } else print "<span class=\"aznone_index\">I</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('J', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=J&amp;orderby=$orderBy">J</a><?php } else print "<span class=\"aznone_index\">J</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('K', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=K&amp;orderby=$orderBy">K</a><?php } else print "<span class=\"aznone_index\">K</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('L', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=L&amp;orderby=$orderBy">L</a><?php } else print "<span class=\"aznone_index\">L</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('M', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=M&amp;orderby=$orderBy">M</a><?php } else print "<span class=\"aznone_index\">M</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('N', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=N&amp;orderby=$orderBy">N</a><?php } else print "<span class=\"aznone_index\">N</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('O', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=O&amp;orderby=$orderBy">O</a><?php } else print "<span class=\"aznone_index\">O</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('P', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=P&amp;orderby=$orderBy">P</a><?php } else print "<span class=\"aznone_index\">P</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('Q', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=Q&amp;orderby=$orderBy">Q</a><?php } else print "<span class=\"aznone_index\">Q</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('R', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=R&amp;orderby=$orderBy">R</a><?php } else print "<span class=\"aznone_index\">R</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('S', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=S&amp;orderby=$orderBy">S</a><?php } else print "<span class=\"aznone_index\">S</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('T', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=T&amp;orderby=$orderBy">T</a><?php } else print "<span class=\"aznone_index\">T</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('U', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=U&amp;orderby=$orderBy">U</a><?php } else print "<span class=\"aznone_index\">U</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('V', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=V&amp;orderby=$orderBy">V</a><?php } else print "<span class=\"aznone_index\">V</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('W', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=W&amp;orderby=$orderBy">W</a><?php } else print "<span class=\"aznone_index\">W</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('X', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=X&amp;orderby=$orderBy">X</a><?php } else print "<span class=\"aznone_index\">X</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('Y', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=Y&amp;orderby=$orderBy">Y</a><?php } else print "<span class=\"aznone_index\">Y</span>"; ?></li>
			<li><?php if (getAllPersonnelWithInitial('Z', $orderBy)) { ?><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=name&amp;startsWith=Z&amp;orderby=$orderBy">Z</a><?php } else print "<span class=\"aznone_index\">Z</span>"; ?></li>
		</ul>
		<div class="clear"></div>
	</div>
<?php
		$_SERVER['REQUEST_URI'] = str_replace('&orderBy=forename', '', $_SERVER['REQUEST_URI']);
		$_SERVER['REQUEST_URI'] = str_replace('&orderBy=surname', '', $_SERVER['REQUEST_URI']);
	
		if ($orderBy == 'forename') {
			print 'Sorted by forename. Sort by ' . '<a href="http://' . $DOMAIN . $_SERVER['REQUEST_URI'] . '&orderBy=surname">surname</a>';
		}
		else {
			print 'Sorted by surname. Sort by ' . '<a href="http://' . $DOMAIN . $_SERVER['REQUEST_URI'] . '&orderBy=forename">forename</a>';
		}

	}

	if ($people) {
		$splitArray = splitArray($people);			
			
		print '<dl class="person_box">';
		foreach ($splitArray['left'] as $person) {

			$dept = getDepartment($person->departmentID);
            $person->imageURL = trim($person->imageURL);
        	if (!empty($person->imageURL)) { 
?>
        	<dd><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel_info.php?personID=<?php print $person->id;?>"><img src="http://<?php print $DOMAIN; ?>/images/<?php print $person->imageURL;?>" alt="<?php print "$person->forename $person->surname";?>" /></a></dd>
<?php 
        	} 
?>
            <dt><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel_info.php?personID=<?php print $person->id;?>"><strong>
<?php 
			if ($orderBy == 'forename') {
				print $person->forename .  " " . $person->surname; 
			}
			else {
				print $person->surname .  ", " . $person->forename; 
			}
?>
			</strong></a><dt>
			<dd>Job title: <?php print $person->jobTitle; ?></dd>
			<dd>Team: <?php print $person->team;?></dd>
<?php
           	if (!empty($person->departmentID)) {
?>                	
            <dd>Department: <?php print $dept->title; ?></dd>
<?php
            }
            else {
?>
            <dd>Department: Unknown</dd>
<?php
			}
?>
			<dd>Telephone: <?php print "$person->telephone"; ?></dd>
			<dd>Email: <a href="mailto:<?php print $person->email; ?>"><?php print $person->email;?></a></dd>
			<dd>Extension: <?php print "$person->extension"; ?></dd>
			<dd class="personBorder"><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel_info.php?personID=<?php print $person->id;?>" >View full details</a></dd>
<?php
		}
		print '</dl>';
		
		print '<dl class="person_box">';
		foreach ($splitArray['right'] as $person) {
			$dept = getDepartment($person->departmentID);
			$person->imageURL = trim($person->imageURL);
			if (!empty($person->imageURL)) { 
?>
        	<dd><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel_info.php?personID=<?php print $person->id;?>" ><img src="http://<?php print $DOMAIN; ?>/images/<?php print $person->imageURL;?>" alt="<?php print "$person->forename $person->surname";?>" /></a></dd>
<?php 
        	} 
?>                   
            <dt><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel_info.php?personID=<?php print $person->id;?>"><strong>
<?php 
			if ($orderBy == 'forename') {
				print $person->forename .  " " . $person->surname; 
			}
			else {
				print $person->surname .  ", " . $person->forename; 
			}
?>
			</strong></a><dt>
			<dd>Job title: <?php print $person->jobTitle; ?></dd>
			<dd>Team: <?php print $person->team;?></dd>
<?php
			if (!empty($person->departmentID)) {
?>
			<dd>Department: <?php print $dept->title; ?></dd>
<?php
			}
			else {
?>
			<dd>Department: Unknown</dd>
<?php
			}
?>
			<dd>Telephone: <?php print "$person->telephone"; ?></dd>
			<dd>Email: <a href="mailto:<?php print $person->email; ?>"><?php print $person->email;?></a></dd>
			<dd>Extension: <?php print "$person->extension"; ?></dd>
			<dd class="personBorder"><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel_info.php?personID=<?php print $person->id;?>">View full details</a></dd>
<?php
			}
			print '</dl>';
		} 

		elseif ($allDepartments) {
			print "<h2>Select a Department</h2><div class=\"display_box\" ><ul class=\"list\">";
			foreach ($allDepartments as $department) {
				if (getAllPersonnelForDepartment($department->id)) {
?>
		    <li><a href="http://<?php print $DOMAIN; ?>/site/scripts/personnel.php?viewBy=department&amp;departmentID=<?php print $department->id;?>"><?php print $department->title;?></a></li>
<?php
				}
		    }
		   	print "</ul></div>";
		}
		
		else {
?>
			<p>Sorry. There were no matches found.</p>
<?php
		}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
					
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>