<?php 
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("websections/JaduHomepageBanners.php");
	include_once("websections/JaduContact.php");

	// for homepages
	include_once("websections/JaduHomepages.php");	
	include_once("websections/JaduHomepageWidgetsToHomepages.php");		
	include_once("websections/JaduHomepageWidgets.php");		
	include_once("websections/JaduHomepageWidgetSettings.php");
		
	// supplements
	include_once("websections/JaduPageSupplements.php");
	include_once("websections/JaduPageSupplementWidgets.php");
	include_once("websections/JaduPageSupplementWidgetPublicCode.php");

	$allIndependantHomepages = getAllHomepagesIndependant();

	$homepage = getHomepage($allIndependantHomepages[0]->id, true);

	if ($homepage->stylesheet != '') {
		$STYLESHEET = $homepage->stylesheet;
	}
	
	$allHomepageContent = getAllWidgetToHomepagesForHomepage($homepage->id, true);
	
	$sections = array();
	foreach($allHomepageContent as $content) {
		if (!isset($sections[$content->positionY])) {
			$sections[$content->positionY] = array();
		}
		if ($content->stackPosition > 0) {
			if (!isset($sections[$content->positionY][$content->positionX])) {
				$sections[$content->positionY][$content->positionX] = array();
			}
			$sections[$content->positionY][$content->positionX][] = $content;
		}
		else {
			$sections[$content->positionY][] = $content;
		}
	}
	
	if (isset($_GET['logout'])) {
		session_unregister("userID");
		unset($userID);
		
		if (defined('PHPBB_INTEGRATION') && PHPBB_INTEGRATION == true) {
		    header("Location: http://$DOMAIN/site/scripts/phpbb_login.php?logout=true");
		    exit();
	    }
	} 
	else if (isset($_SESSION['userID'])) {
		unset($_GET['sign_in']);
	}
	if (isset($_GET['loginFailed'])) {
		$_GET['sign_in'] = 'true';
	}
	
	//	Get the homepage
	$homepages = getAllHomepageBanners (true);
	shuffle($homepages);
	$homepage = $homepages[0];
	
	$address = new Address;
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> Homepage - Telephone: <?php print $address->telephone;?></title>

	<?php include_once("./includes/stylesheets.php"); ?>
	<?php include_once("./includes/metadata.php"); ?>

	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY;?>generic/homepages.css" media="screen" />	
	<link rel="stylesheet" type="text/css" href="<?php print $STYLES_DIRECTORY;?>generic/homepageElements.php?homepageID=<?php print $allIndependantHomepages[0]->id; ?>" media="screen" />

	<meta name="Keywords" content="home, homepage, index, root, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_DESCRIPTION; ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Online - Tel: <?php print $address->telephone;?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_DESCRIPTION; ?>" />

	<link rel="alternate" type="application/rss+xml" title="RSS" href="http://<?php print $DOMAIN;?>/site/scripts/rss.php" />


	<script type="text/javascript" src="http://<?php print $DOMAIN?>/site/javascript/prototype.js"></script>
	<script type="text/javascript" src="http://<?php print $DOMAIN?>/site/javascript/scriptaculous.js"></script>
	<script type="text/javascript" src="http://<?php print $DOMAIN?>/site/javascript/effects.js"></script>
	<script type="text/javascript" src="http://<?php print $DOMAIN?>/site/javascript/carousel.js"></script>
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("./includes/opening.php"); ?>
<!-- ########################## -->
		
<?php
	if (isset($_GET['sign_in'])) {
		if ($_SERVER['QUERY_STRING'] == "logout=true") {
			$action = 'http://'.$DOMAIN."/site/index.php";
		}	
		else {
			$action = $PROTOCOL.$DOMAIN.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
		}
?>
			<!-- Sign in -->
			<form action="<?php print htmlentities($action, ENT_QUOTES);?>" method="post" class="basic_form signin">
<?php
			if (isset($_REQUEST['referer'])) {
?>
				<input type="hidden" value="<?php print htmlentities($_REQUEST['referer'], ENT_QUOTES); ?>" name="referer" />
<?php
			}
?>
				<h2 class="signin"><?php if (isset($loginFailed)) { ?><span class="warning"><?php } ?>Sign-in<?php if (isset($loginFailed)) { ?> failed, please try again</span><?php } ?></h2>
				<fieldset>
					<p><a title="Password reminder" href="http://<?php print $DOMAIN;?>/site/scripts/forgot_password.php">Password reminder</a></p>		
					<p>
						<label for="YourEmail">Email:</label>
						<input size="17" type="text" maxlength="50" name="email" class="field" id="YourEmail" />
					</p>
					<p>
						<label for="YourPassword">Password:</label>
						<input size="17" type="password" name="password" maxlength="22" class="field" id="YourPassword" />
					</p>
					<p class="center">
						<input type="submit" value="Sign-in" class="button" />
					</p>
				</fieldset>
			</form>
		
<?php
	}
	else {
?>
	
			
<?php
			if (isset($_SESSION['userID'])) {
				print "<h2 class=\"welcome\"><span>Hello, <strong>" ;
				if (!empty($user->salutation) && !empty($user->surname)) {
                    print $user->salutation .  " ";
				}

                if (!empty($user->forename)) {
                    print $user->forename . " ";
				}

                if  (!empty($user->surname)) {
                    print $user->surname; 
				}

                if (empty($user->forename) && empty($user->surname)) {
                    print $user->email;
				}
?>
                </strong> - You are signed-in</span></h2>
<?php
			}
?>
			
			
<?php
	}
	foreach ($sections as $row) {
?>
				<div class="row_divider">
<?php
		foreach ($row as $widget) {
			if (!is_array($widget)) {
				$widgetContent = getHomepageWidget($widget->widgetID);
				$widgetContent = $widgetContent->contentCode;
				$settings = getAllSettingsForHomepageWidget($widget->id, true);

				$widgetStyle = '';
				if (!empty($settings)) {
					foreach ($settings as $setting) {
						$setting->value = str_replace("'", '&apos;', $setting->value);
						if ($setting->name == 'stylesheet') {
							$widgetStyle = $setting->value;
						}
						if (strtolower($setting->name) == 'img_src' && strtolower(substr($setting->value,0,4)) != 'http') {
							$setting->value = 'http://' . $DOMAIN . '/images/' . $setting->value;
						}
						$widgetContent = str_replace('%'.strtoupper($setting->name).'%', $setting->value, $widgetContent);
					}
				}
?>
					<div class="new_widget <?php if(isset($widgetStyle)) { print $widgetStyle; } else { print styleLess; } ?> width<?php print $widget->widthPercentage; ?>">
					<div class="widgetPadding">
<?php 
				eval('?>' . $widgetContent . '<?php '); 
			} 
			else {
?>
					<div class="new_widget width<?php print $widget[0]->widthPercentage; ?>">
					<div class="widgetPadding">
<?php
				foreach ($widget as $stack) {
					$widgetContent = getHomepageWidget($stack->widgetID);
					$widgetContent = $widgetContent->contentCode;
					$settings = getAllSettingsForHomepageWidget($stack->id, true);

					$widgetStyle = '';
					if (!empty($settings)) {
						foreach ($settings as $setting) {
							$setting->value = str_replace("'", '&apos;', $setting->value);
							if ($setting->name == 'stylesheet') {
								$widgetStyle = $setting->value;
							}
							if (strtolower($setting->name) == 'img_src' && strtolower(substr($setting->value,0,4)) != 'http') {
								$setting->value = 'http://' . $DOMAIN . '/images/' . $setting->value;
							}
							$widgetContent = str_replace('%'.strtoupper($setting->name).'%', $setting->value, $widgetContent);
						}
					}
?>
						<div class="stacking <?php if(!empty($widgetStyle)) { print $widgetStyle; } else { print styleLess; } ?>">
<?php
					eval('?>' . $widgetContent . '<?php ');
?>
						</div>						
<?php
				}			
			}
?>
					</div>
					</div>
<?php
		}
?>
					<br class="clear" />	
				</div>
<?php
	}
?>

	
		<!-- Bottom Supplements -->
<?php
		// get bottom supplements 
		if (isset($page) || isset($homepage)) {
			if (isset($page)) {
				$bottomSupplements = getAllPageSupplements('', $page->id, '', 'Bottom');
			}
			elseif (isset($homepage)) {
				$bottomSupplements = getAllPageSupplements('', '', $homepage->id, 'Bottom');
			}
			// loop through each supplement
			foreach ($bottomSupplements as $supplement) {
				// include supplement front-end code
				$publicCode = getSupplementPublicCode($supplement->supplementWidgetID, $supplement->locationOnPage);
				$supplementWidget = getPageSupplementWidget($supplement->supplementWidgetID);

				include_once($supplementWidget->classFile);

				$record = new $supplementWidget->className;
				$record->id = $supplement->supplementRecordID;
				$record->get();
				include_once($HOME . '/site/includes/supplements/' . $publicCode->code);
			}
		}
?>
		<!-- End bottom supplements -->
		
			<script type="text/javascript" src="/site/javascript/homepage_javascript.php?homepageID=<?php print $homepage->id; ?>"></script>
		
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("./includes/closing.php"); ?>
