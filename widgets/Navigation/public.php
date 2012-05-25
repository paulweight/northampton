<div class="navigationWidget">
<?php
	include_once('websections/JaduHomepageWidgetSettings.php');
	include_once('ext/json.php');
	
	if (!function_exists('nav_cmp')) {
		function nav_cmp ($a, $b)
		{
			$a = str_replace('link', '', $a->name);
			$a = str_replace('title', '', $a) * 1;
			$b = str_replace('link', '', $b->name);
			$b = str_replace('title', '', $b) * 1;
			
			if ($a == $b) {
				return 0;
			}
			return ($a < $b ? -1 : 1);
		}
	}
	
	$allWidgetLinks = array();

	if (isset($_POST['preview'])) {
		$newSettings = array();

		$j = 0;
		
		if (!empty($settings)) {
			foreach ($settings as $name => $value) {
				$newSettings[$j] = new stdClass();
				$newSettings[$j]->name = $name;
				$newSettings[$j]->value = $value;
				
				$newSettings[$j]->value= str_replace('_apos_', "'", $newSettings[$j]->value);
				$newSettings[$j]->value = str_replace('_amp_', "&", $newSettings[$j]->value);
				$newSettings[$j]->value = str_replace('_eq_', '=', $newSettings[$j]->value);
				$newSettings[$j]->value = str_replace('_hash_', '#', $newSettings[$j]->value);
				$newSettings[$j]->value = str_replace('_ques_', '?', $newSettings[$j]->value);
				$newSettings[$j]->value = str_replace('_perc_', '%', $newSettings[$j]->value);
				
				$j++;
			}
		}
		
		$settings = $newSettings;
		
	}
	else {
	
		if (isset($widget) && !is_array($widget)) {
			if (isset($_POST['homepageContent'])) {
				$settings = array();
				foreach ($widgetSettings[$widget->id] as $setting) {
					$newSetting = new WidgetSetting();
					$newSetting->name = $setting->name;
					$newSetting->value = $setting->value;
					
					$newSetting->value= str_replace('_apos_', "'", $newSetting->value);
					$newSetting->value = str_replace('_amp_', "&", $newSetting->value);
					$newSetting->value = str_replace('_eq_', '=', $newSetting->value);
					$newSetting->value = str_replace('_hash_', '#', $newSetting->value);
					$newSetting->value = str_replace('_ques_', '?', $newSetting->value);
					$newSetting->value = str_replace('_perc_', '%', $newSetting->value);
					
					$settings[] = $newSetting;
				}
			}
			else if (isset($_POST['action']) && $_POST['action'] == 'getPreviews') {
				$settings = getAllSettingsForHomepageWidget($aWidget->id);
			}
			else {
				$settings = getAllSettingsForHomepageWidget($widget->id, true);
			}
		}
		else {
			if (isset($_POST['homepageContent'])) {
				$settings = array();
				foreach ($widgetSettings[$stack->id] as $setting) {
					$newSetting = new WidgetSetting();
					$newSetting->name = $setting->name;
					$newSetting->value = $setting->value;
					$settings[] = $newSetting;
				}
			}
			else if (isset($_POST['getPreviews'])) {
				$settings = getAllSettingsForHomepageWidget($aWidget->id);
			}
			else {
				$settings = getAllSettingsForHomepageWidget($stack->id, true);
			}
		}
	}
	
	$tempLinks = array();
	$tempTitles = array();
	$nav_widget_title = '';

	if (!empty($settings)) {
		usort($settings, 'nav_cmp');
		foreach ($settings as $value) {
			if (preg_match('/link[0-9]+title/i', $value->name)) {
				$tempTitles[] = $value->value;
			}
			if (preg_match('/link[0-9]+url/i', $value->name)) {
				$tempLinks[] = $value->value;
			}
			
			if ($value->name == 'nav_widget_title') {
				$nav_widget_title = $value->value;
			}
		}
	}
	
	for ($i = 0; $i < sizeof($tempLinks); $i++) {
		$allWidgetLinks[] = array($tempTitles[$i], $tempLinks[$i]);
	}
	
	if ($nav_widget_title != '') {
?>
	<h2><?php print encodeHtml($nav_widget_title); ?></h2>
<?php
	}
	
	if (!empty($allWidgetLinks)) {
?>
	<ul class="list icons links">
<?php
		foreach ($allWidgetLinks as $widgetLink) {
?>
		<li class="long"><a title="<?php print encodeHtml($widgetLink[0]); ?>" href="<?php
		if (strpos($widgetLink[1], 'http://') !== 0 && strpos($widgetLink[1], 'https://') !== 0) {
			print 'http://';
		}
		print encodeHtml($widgetLink[1]);
?>"><?php print encodeHtml($widgetLink[0]); ?></a></li>
<?php
		}
?>
	</ul>
<?php
	}
?>
</div>