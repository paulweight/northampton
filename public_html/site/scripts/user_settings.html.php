<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Accessibility, dda, disability discrimination act, disabled access, access keys, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Accessibility features" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> is committed to providing accessible web content and council services online for all" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
			
<?php 
	if (!isset($_COOKIE[session_name()]) && (isset($_POST['saveButton']) || isset($_POST['previewButton']))) {
		print '<h2 class="warning">Sorry!</h2><p>You must have cookies turned on to use this sites user setting options.</p><p>To view a tutorial and instructions on how to enable cookies, please see our <a href="../cookies/cookie_instructions.php">cookie instructions</a>.</p>';		
	}
	else {
?>
			
	<p>
<?php 	if (isset($_POST["saveButton"]) || isset($_POST["resetButton"])) {
		print "Your settings have been changed. If you are using the browser Opera, you will need to refresh the page to see the changes.";
	}
	else {
		print "In this section you can adapt this website to meet your needs. The settings you create on this page will be saved for future visits. If you wish to return to the standard settings, click the \"Reset site settings\" button.";
	}
?>
	</p>			

	<h2>Access Keys</h2>
	<p>This site also supports access keys for increased navigation help. A <a href="<?php print getSiteRootURL() . buildAccessibilityURL() ;?>">full listing of access keys</a> and how to use them is available.</p>
				
	<form class="basic_form xform" action="<?php print getSiteRootURL() . buildNonReadableUserSettingsURL(); ?>" method="post" enctype="multipart/form-data">
		<fieldset>
			<legend>Choose your text preferences</legend>
			<ul>
				<li>
					<label for="fontSize">Text size</label>	
					<select id="fontSize" name="fontSize">
						<option value="" <?php if ($_COOKIE['userFontsize'] == '' || !isset($_COOKIE['userFontsize'])  || (isset($_POST["previewButton"]) && $_POST["fontSize"] == "") ) print 'selected="selected"'; ?>>Standard</option>
						<option value="small" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontsize'] == 'small') || (isset($_POST["previewButton"]) && $_POST["fontSize"] == "small") ) print 'selected="selected"'; ?>>+1</option>
						<option value="medium" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontsize'] == 'medium') || (isset($_POST["previewButton"]) && $_POST["fontSize"] == "medium") ) print 'selected="selected"'; ?>>+2</option>
						<option value="large" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontsize'] == 'large') || (isset($_POST["previewButton"]) && $_POST["fontSize"] == "large") ) print 'selected="selected"'; ?>>+3</option>
					</select>
				</li>
				<li>
					<label for="fontChoice">Font</label>
					<select id="fontChoice" name="fontChoice">
						<option value="" <?php if ($_COOKIE['userFontchoice'] == '' || !isset($_COOKIE['userFontchoice']) || (isset($_POST["previewButton"]) && $_POST["fontChoice"] == "") ) print 'selected="selected"'; ?>>Standard</option>
						<option value="times" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontchoice'] == 'times') || (isset($_POST["previewButton"]) && $_POST["fontChoice"] == "times") ) print 'selected="selected"'; ?>>Times</option>
						<option value="comicsans" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontchoice'] == 'comicsans') || (isset($_POST["previewButton"]) && $_POST["fontChoice"] == "comicsans") ) print 'selected="selected"'; ?>>Comic Sans</option>
						<option value="courier" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontchoice'] == 'courier') || (isset($_POST["previewButton"]) && $_POST["fontChoice"] == "courier") ) print 'selected="selected"'; ?>>Courier</option>
						<option value="ariel" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userFontchoice'] == 'ariel') || (isset($_POST["previewButton"]) && $_POST["fontChoice"] == "ariel") ) print 'selected="selected"'; ?>>Arial</option>
					</select>
				</li>
				<li>
					<label for="letterSpacing">Letter spacing</label>
					<select id="letterSpacing" name="letterSpacing">
						<option value="" <?php if ($_COOKIE['userLetterspacing'] == '' || !isset($_COOKIE['userLetterspacing']) || (isset($_POST["previewButton"]) && $_POST["letterSpacing"] == "") ) print 'selected="selected"'; ?>>Standard</option>
						<option value="wide" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userLetterspacing'] == 'wide') || (isset($_POST["previewButton"]) && $_POST["letterSpacing"] == "wide") ) print 'selected="selected"'; ?>>+1</option>
						<option value="wider" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userLetterspacing'] == 'wider') || (isset($_POST["previewButton"]) && $_POST["letterSpacing"] == "wider") ) print 'selected="selected"'; ?>>+2</option>
						<option value="widest" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE['userLetterspacing'] == 'widest') || (isset($_POST["previewButton"]) && $_POST["letterSpacing"] == "widest") ) print 'selected="selected"'; ?>>+3</option>
					</select>
				</li>
			</ul>
		</fieldset>

		<fieldset>
			<legend>Choose your colour preferences</legend>
			<ul>
				<li class="prefStandard colours">
					<label for="default_colour">
						<input name="colourScheme" value="" id="default_colour" type="radio" class="checkbox" <?php if (!isset($_COOKIE["userColourscheme"]) || $_COOKIE["userColourscheme"] == '' || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "") ) print 'checked="checked"'; ?> /><span>Standard</span></label>
				</li>
				<li class="prefContrast colours">
					<label for="highcontrast">
						<input id="highcontrast" name="colourScheme" value="highcontrast" class="checkbox" type="radio" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE["userColourscheme"] == "highcontrast") || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "highcontrast") ) print 'checked="checked"'; ?>  /><span>High contrast</span></label>
				</li>
				<li class="prefCream colours">
					<label for="cream">
					<input id="cream" name="colourScheme" value="cream" class="checkbox" type="radio" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE["userColourscheme"] == "cream") || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "cream") ) print 'checked="checked"'; ?> /><span>Cream</span></label>
				</li>
				<li class="prefBlue colours">
					<label for="blue">
					<input id="blue" name="colourScheme" value="blue" class="checkbox" type="radio" <?php if ((!isset($_POST["previewButton"]) && $_COOKIE["userColourscheme"] == "blue") || (isset($_POST["previewButton"]) && $_POST["colourScheme"] == "blue") ) print 'checked="checked"'; ?> /><span>Contrast blue</span></label>
				</li>
				<li class="clear"></li>
				<li class="centre">
					<input type="submit" value="Use these settings" name="saveButton" class="genericButton grey" />
					<input type="submit" value="Preview" name="previewButton" class="genericButton grey" />
					<input type="submit" name="resetButton" value="Reset" class="genericButton grey" />
				</li>
			</ul>
		</fieldset>
	</form>

	<?php include_once("../includes/user_settings/settings_preview.php"); ?>
	
	<p>Please note, in order to remember your preferences as you navigate through the site, a cookie will be set.</p>
<?php 
	}
?>	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
