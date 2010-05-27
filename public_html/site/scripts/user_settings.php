<?php
	include_once("utilities/JaduStatus.php");	
	include_once("JaduStyles.php");

	if (isset($_POST["saveButton"])) {
		if (isset($_POST["colourScheme"])) {
			setcookie("userColourscheme", $_POST["colourScheme"], time() + 3600, "/", $DOMAIN);
			$_COOKIE['userColourscheme'] = $_POST["colourScheme"];
		}
	
		if (isset($_POST["fontSize"])) {	
			setcookie("userFontsize", $_POST["fontSize"], time() + 3600, "/", $DOMAIN);	
			$_COOKIE['userFontsize'] = $_POST["fontSize"];
		}
	
		if (isset($_POST["fontChoice"])) {	
			setcookie("userFontchoice", $_POST["fontChoice"], time() + 3600, "/", $DOMAIN);	
			$_COOKIE['userFontchoice'] = $_POST["fontChoice"];
		}
		
		if (isset($_POST["letterSpacing"])) {	
			setcookie("userLetterspacing", $_POST["letterSpacing"], time() + 3600, "/", $DOMAIN);	
			$_COOKIE['userLetterspacing'] = $_POST["letterSpacing"];
		}	
	
		if (isset($_POST["userLayout"])) {	
			setcookie("userLayout", $_POST["Layout"], time() + 3600, "/", $DOMAIN);	
			$_COOKIE['userLayout'] = $_POST["Layout"];	
		}
	}

	if (isset($_POST["resetButton"])) {	
		setcookie("userColourscheme", "", time() - 3600, "/", $DOMAIN);
		unset($_COOKIE['userColourscheme']);
		setcookie("userFontsize", "", time() - 3600, "/", $DOMAIN);
		unset($_COOKIE['userFontsize']);		
		setcookie("userFontchoice", "", time() - 3600, "/", $DOMAIN);	
		unset($_COOKIE['userFontchoice']);	
		setcookie("userLetterspacing", "", time() - 3600, "/", $DOMAIN);	
		unset($_COOKIE['userLetterspacing']);		
		setcookie("userLayout", "", time() - 3600, "/", $DOMAIN);	
		unset($_COOKIE['userLayout']);		
	}
		
	$breadcrumb = 'userSettings';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Accessibility settings | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Accessibility, dda, disability discrimination act, disabled access, access keys, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Accessibility features" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
<?php 
		if (!isset($_COOKIE['TestCookie']) && (isset($_POST['saveButton']) || isset($_POST['previewButton']))) {
			print '<h2 class="warning">Sorry!</h2><p class="first">You must have cookies turned on to use this sites user setting options.</p><p>To view a tutorial and instructions on how to enable cookies, please see our <a href="../cookies/cookie_instructions.php">cookie instructions</a>.</p>';		
		}
		else {
?>
			
		<p class="first">
<?php 	if (isset($_POST["saveButton"]) || isset($_POST["resetButton"])) {
			print "Your settings have been changed. If you are using the browser Opera, you will need to refresh the page to see the changes.";
		}
		else {
			print "In this section you can adapt this website to suit you. The settings you create on this page will be saved for future visits. If you wish to return to the standard settings, click the \"Reset site settings\" button.";
		}
?>
		</p>			

			
		<form action="http://<?php print $DOMAIN; ?>/site/scripts/user_settings.php#1" method="post" name="userSet" class="basic_form" >		
			<fieldset class="settingsText">
				<legend>Change the site text</legend>
				<p>
					<label for="fontSize">Text size
					<select class="select" id="fontSize" name="fontSize">
						<option value="" <?php if ($_COOKIE['userFontsize'] == '' || !isset($_COOKIE['userFontsize'])  || (isset($_POST["previewButton"]) && $_POST["fontSize"] == "") ) print 'selected="selected"'; ?>>Standard</option>
						<option value="medium" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontsize'] == 'medium') || (isset($_POST["previewButton"]) && $_POST["fontSize"] == "medium") ) print 'selected="selected"'; ?>>+1</option>
						<option value="large" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontsize'] == 'large') || (isset($_POST["previewButton"]) && $_POST["fontSize"] == "large") ) print 'selected="selected"'; ?>>+2</option>
						<option value="larger" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontsize'] == 'larger') || (isset($_POST["previewButton"]) && $_POST["fontSize"] == "larger") ) print 'selected="selected"'; ?>>+3</option>
						<option value="largest" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontsize'] == 'largest') || (isset($_POST["previewButton"]) && $_POST["fontSize"] == "largest") ) print 'selected="selected"'; ?>>+4</option>					
					</select>
					</label>

					<label for="fontChoice">Font
					<select class="select" id="fontChoice" name="fontChoice">
						<option value="" <?php if ($_COOKIE['userFontchoice'] == '' || !isset($_COOKIE['userFontchoice']) || (isset($_POST["previewButton"]) && $_POST["fontChoice"] == "") ) print 'selected="selected"'; ?>>Standard</option>
						<option value="times" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontchoice'] == 'times') || (isset($_POST["previewButton"]) && $_POST["fontChoice"] == "times") ) print 'selected="selected"'; ?>>Times</option>
						<option value="comicsans" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontchoice'] == 'comicsans') || (isset($_POST["previewButton"]) && $_POST["fontChoice"] == "comicsans") ) print 'selected="selected"'; ?>>Comic Sans</option>
						<option value="courier" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontchoice'] == 'courier') || (isset($_POST["previewButton"]) && $_POST["fontChoice"] == "courier") ) print 'selected="selected"'; ?>>Courier</option>
						<option value="ariel" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontchoice'] == 'ariel') || (isset($_POST["previewButton"]) && $_POST["fontChoice"] == "ariel") ) print 'selected="selected"'; ?>>Arial</option>
					</select>
					</label>

					<label for="letterSpacing">Letter spacing
					<select class="select" id="letterSpacing" name="letterSpacing">
						<option value="" <?php if ($_COOKIE['userLetterspacing'] == '' || !isset($_COOKIE['userLetterspacing']) || (isset($_POST["previewButton"]) && $_POST["letterSpacing"] == "") ) print 'selected="selected"'; ?>>Standard</option>
						<option value="small" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userLetterspacing'] == 'small') || (isset($_POST["previewButton"]) && $_POST["letterSpacing"] == "small") ) print 'selected="selected"'; ?>>+1</option>
						<option value="medium" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userLetterspacing'] == 'medium') || (isset($_POST["previewButton"]) && $_POST["letterSpacing"] == "medium") ) print 'selected="selected"'; ?>>+2</option>
						<option value="wide" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userLetterspacing'] == 'wide') || (isset($_POST["previewButton"]) && $_POST["letterSpacing"] == "wide") ) print 'selected="selected"'; ?>>+3</option>
					</select>
					</label>
				</p>	
			</fieldset>
			
			<fieldset class="settingsColours">
				<legend>High contrast colours</legend>
				<p> 
					<span>
						<label for="default_colour" style="background-color:#EBEBEB; color:#000000;">
							<input name="colourScheme" value="" id="default_colour" type="radio" <?php if (!isset($_COOKIE["userColourscheme"]) || $_COOKIE["userColourscheme"] == '' || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "") ) print 'checked="checked"'; ?> />
						 Standard</label>
					</span>

					<span>
						<label for="contrast_colour" style="background-color:#000000; color:#FFFF00;">
							<input id="contrast_colour" name="colourScheme" value="highcontrast" type="radio" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE["userColourscheme"] == "highcontrast") || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "highcontrast") ) print 'checked="checked"'; ?>  />
						 Contrast</label>
					</span>

					<span>
						<label for="simple_colour" style="background-color:#FFFFFF; color:#010066;">
							<input id="simple_colour" name="colourScheme" value="simple" type="radio" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE["userColourscheme"] == "simple") || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "simple") ) print 'checked="checked"'; ?> />
						 Simple</label>
					</span>

					<span>
						<label for="news_colour"  style="background-color:#FFFFFF; color:#000000;">
							<input id="news_colour" name="colourScheme" value="newsprint" type="radio" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE["userColourscheme"] == "newsprint") || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "newsprint") ) print 'checked="checked"'; ?> />
						 News Print</label>
					</span>
					<span>
						<label for="grey_colour" style="background-color:#6F6F6F; color:#FFFFFF;">
							<input id="grey_colour" name="colourScheme" value="slate" type="radio" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE["userColourscheme"] == "slate") || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "slate") ) print 'checked="checked"'; ?> />
						 Grey</label>
					</span>
					<span>
						<label for="navy_colour" style="background-color:#112233; color:#FFFFFF;">
							<input id="navy_colour" name="colourScheme" value="reversed" type="radio" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE["userColourscheme"] == "reversed") || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "reversed") ) print 'checked="checked"'; ?> />
						 Navy</label>
					</span>
					<span class="clear"></span>
				</p>
				</fieldset>
				<fieldset class="settingsColours">	
					<legend>Soft Background Options</legend>
				<p> 
					<span class="clear"></span>

					<span>
						<label for="silver_colour" style="background-color:#CECFCE; color:#000000;">
						<input id="silver_colour" name="colourScheme" value="reflect" type="radio" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE["userColourscheme"] == "reflect") || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "reflect") ) print 'checked="checked"'; ?> />
						 Silver</label>
					</span>
					<span>
						<label for="cream_colour" style="background-color:#FFF9D2; color:#010066;">
						<input id="cream_colour" name="colourScheme" value="soft1" type="radio" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE["userColourscheme"] == "soft1") || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "soft1") ) print 'checked="checked"'; ?> />
						 Cream</label>
					</span>
					
					<span>
						<label for="yellow_colour" style="background-color:#FFFFCC; color:#000000;">			
						<input id="yellow_colour" name="colourScheme" value="soft2" type="radio" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE["userColourscheme"] == "soft2") || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "soft2") ) print 'checked="checked"'; ?> />
						 Yellow	</label>
					</span>
					
					<span>
						<label for="mutedb_colour" style="background-color:#9FCFFF; color:#010066;">					
						<input id="mutedb_colour" name="colourScheme" value="blue1" type="radio" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE["userColourscheme"] == "blue1") || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "blue1") ) print 'checked="checked"'; ?> />
						 Muted Blue</label>
					</span>

					<span>
						<label for="bblue_colour" style="background-color:#9FCFFF; color:#0000FF;">
						<input id="bblue_colour" name="colourScheme" value="blue2" type="radio" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE["userColourscheme"] == "blue2") || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "blue2") ) print 'checked="checked"'; ?> />
						 Bright Blue</label>
					</span>

					<span>
						<label for="cblue_colour" style="background-color:#9FCFFF; color:#010066;">
						<input id="cblue_colour" name="colourScheme" value="blue3" type="radio" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE["userColourscheme"] == "blue3") || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "blue3") ) print 'checked="checked"'; ?> />
						 Contrast Blue</label>
					</span>
					<span class="clear"></span>
				</p>
				<p class="centre">
				<input type="submit" value="Use These Settings" name="saveButton" class="button" />
				<input type="submit" value="Preview" name="previewButton" class="button" />
				<input type="submit" name="resetButton" value="Reset Site Settings" class="button"/>
				</p>
			</fieldset>


		</form>

		<?php include_once("../includes/user_settings/settings_preview.php"); ?>
			
<?php 
	}
?>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->


<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>