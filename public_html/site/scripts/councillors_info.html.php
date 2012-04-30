<?php include_once("../includes/doctype.php"); ?>
<head>
	<title>Councillor <?php print encodeHtml($councillor->forename . ' ' . $councillor->surname); ?> | <?php if ($ward->id != -1) print encodeHtml($ward->name); else print "Unknown Ward"; ?> Ward | <?php if ($party->id != -1) print encodeHtml($party->name); else print "Unknown"; ?> Party | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="<?php print encodeHtml($councillor->forename . ' ' . $councillor->surname);?>, <?php if ($ward->id != -1) print encodeHtml($ward->name); else print "Unknown Ward"; ?> Ward, <?php if ($party->id != -1) print encodeHtml($party->name); else print "Unknown"; ?> Party, local authority, councillors, councillor, member, elect, MPs, MEPs, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="Details of Councillor <?php print encodeHtml($councillor->forename . ' ' . $councillor->surname);?> of <?php if ($ward->id != -1) print encodeHtml($ward->name); else print "Unknown Ward"; ?> Ward - <?php if ($party->id != -1) print encodeHtml($party->name); else print "Unknown"; ?> Party" />

	<meta name="DC.title" lang="en" content="Councillor <?php print encodeHtml($councillor->forename . ' ' . $councillor->surname);?> - <?php if ($ward->id != -1) print encodeHtml($ward->name); else print "Unknown Ward"; ?> Ward - <?php if ($party->id != -1) print encodeHtml($party->name); else print "Unknown"; ?> Party - <?php print encodeHtml(METADATA_GENERIC_NAME); ?>" />
	<meta name="DC.identifier" content="http://<?php print DOMAIN . encodeHtml($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']); ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<?php include("../includes/opening.php"); ?>
<!-- ####################################### -->

<div id="hcard-<?php print str_replace(' ', '-', encodeHtml($councillor->forename)); ?>-<?php print str_replace(' ', '-', encodeHtml($councillor->surname)); ?>" class="vcard">  
<?php 
	if ($councillor->imageURL != "") { 
		if (mb_strlen(getImageProperty($councillor->imageURL, 'longdesc')) > 0)  {
?>
		<div class="figcaption">
			<img class="photo" src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($councillor->imageURL); ?>" alt="<?php print encodeHtml($councillor->forename . ' ' . $councillor->surname); ?>" />
			<p><?php print encodeHtml(getImageProperty($councillor->imageURL, 'longdesc'));?></p>
		</div>
<?php
		}
		else {
?>
		<img class="photo" src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($councillor->imageURL); ?>" alt="<?php print encodeHtml($councillor->forename . ' ' . $councillor->surname); ?>" />
<?php 
		}
	} 
?>
	<p>Ward: <?php print $ward->id != -1 ? encodeHtml($ward->name) : "Unknown Ward"; ?></p>
	<p>Party: <?php print $party->id != -1 ? encodeHtml($party->name) : "Unknown"; ?></p>
<?php
	if (sizeof($typeArray) > 0) {
		$position = $typeArray[$councillor->leader];
		if ($position != '') {
?>
	<p>Position: <?php print $position;?></p>
<?php
		}
	}
?>
	<h2>Contact details</h2>
	
<?php 
	if ($councillor->forename != "" || $councillor->surname != "") { 
		print "<p class='fn hidden'>" . nl2br(encodeHtml($councillor->forename)) .' ' . nl2br(encodeHtml($councillor->surname)) . "</p>"; 
	} 
	
	
	if ($councillor->address != "") { 
		print "<p class='adr'>" . nl2br(encodeHtml($councillor->address)) . "</p>"; 
	} 
	if ($councillor->telephone != "") { 
		print '<p>Tel: <span class="tel">' . encodeHtml($councillor->telephone) . '</span></p>'; 
	} 
	if ($councillor->fax != "") { 
		print '<p class="tel"><span class="type">Fax</span>: ' . encodeHtml($councillor->fax) . '</p>'; 
	} 
	if ($councillor->email != "") { 
		print '<p>Email <a href="mailto:' . encodeHtml($councillor->email) . '" class="email">' . encodeHtml($councillor->email) . '</a></p>'; 
	} 
?>
	</div>
            
	<div class="byEditor article">
		<?php print processEditorContent($councillor->content); ?>
	</div>
    
<?php 
		if ($ward->id != -1) {
		    $otherCouncillors = getAllCouncillorsForWard($ward->id, true, true);
			foreach($otherCouncillors as $index => $otherCouncillor) {
				if ($otherCouncillor->id == $councillor->id) {
					unset($otherCouncillors[$index]);
				}
			}
		    if (sizeof($otherCouncillors) > 0) {
?>
  
	<h2>Other Councillors for <?php print encodeHtml($ward->name); ?></h2>
            
<?php
 				print '<ul>';	
               	foreach ($otherCouncillors as $otherCouncillor) {
					$ward = getWard($otherCouncillor->wardID);
					$party = getParty($otherCouncillor->partyID);
?>			
				<li>
					<p>
					<a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($otherCouncillor->id); ?>">
						<strong><?php print encodeHtml($otherCouncillor->forename . ' ' . $otherCouncillor->surname); ?></strong>
					</a>
					</p>
<?php
				if ($councillor->imageURL != "") { 
?>
				<p><a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($otherCouncillor->id); ?>"><img src="<?php print getStaticContentRootURL(); ?>/images/<?php print encodeHtml($otherCouncillor->imageURL); ?>" alt="<?php print encodeHtml($otherCouncillor->forename . ' ' . $otherCouncillor->surname); ?>" /></a></p>
<?php 
				} 
?>
				<p>Ward: <?php print $ward->id != -1 ? encodeHtml($ward->name) : "Unknown"; ?></p>
				<p>Party: <?php print $party->id != -1 ? encodeHtml($party->name) : "Unknown"; ?></p>
				<p>Full details on <a href="<?php print getSiteRootURL() . buildCouncillorsIndividualURL($otherCouncillor->id); ?>"><?php print encodeHtml($otherCouncillor->forename . ' ' . $otherCouncillor->surname); ?></a></p>                    
				</li>
<?php
                }
				print '</ul>';
?>
		 </div>   

<?php
            }
        }
?>
	<h3>Councillors</h3>
	<ul class="list">
		<li><a href="<?php print getSiteRootURL() . buildCouncillorsGroupURL('name'); ?>">View by name</a></li>
		<li><a href="<?php print getSiteRootURL() . buildCouncillorsGroupURL('ward'); ?>"> View by ward</a></li>
		<li><a href="<?php print getSiteRootURL() . buildCouncillorsGroupURL('party'); ?>">View by party</a></li>
		<li><a href="<?php print getSiteRootURL() . buildCouncillorLookupURL(); ?>"> Find my Councillor</a></li>
	</ul>

<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>