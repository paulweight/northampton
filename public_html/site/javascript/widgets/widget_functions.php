<?php
	include_once("websections/JaduHomepages.php");	
	include_once("websections/JaduHomepageWidgets.php");
	include_once("websections/JaduHomepageWidgetsToHomepages.php");

	if (isset($_REQUEST['p'])) {
	
		//validate the request
		$request = unserialize(base64_decode(decodeHtml($_REQUEST['p'])));

		/*
		* $request is an array where -
		*
		* array( 'a' => The function to call
		*		 'b' => Part b takes two forms depending on whether this script is 
		*				called from homepages preview, or a live homepage
		*				
		*				FROM PREVIEW -
		*				base64 encoded string of the format widgetSettings_widgetID.'_'.Hash
		*				where part 'Hash' is an md5 of the pre-placeholder replacement widget
		*				php code and the DOMAIN constant, and part widgetSettings are the serialised
		*				and base64 encoded settings sent to the preview page over POST.					
		*
		*				FROM LIVE -
		*				base64 encoded string of the format widgetToHomepageID.'_'.Hash
		*				where part 'Hash' is an md5 of the pre-placeholder replacement widget
		*				php code and the DOMAIN constant.
		*
		*		 'c' => An md5 of part 'b', the function to call, and the HASH_SALT constant
		*               (chaining hashes 'b' and 'c' together).
		*		 'd' => Indicates whether this request is coming from 'preview' or 'live'
		*				set as those values, respectively
		* )
		*/
		
		//decode and split 'b' into it's parts
		$b_split = explode('_',base64_decode($request['b']));

		if ($request['d'] == 'preview') {
			
			//get the widget from the widget id sent
			$widgetID = intval($b_split[1]);
			$widget = getHomepageWidget($widgetID);

			//get the hash component of 'b'
			$widgetHash = $b_split[2];
			
			//get the widget settings posted over to the preview script
			$settings = unserialize(base64_decode($b_split[0]));			
		}
		else if ($request['d'] == 'live') {
			
			//get the widget from the live widgetToHomepageID
			$widgetToHomepageID = intval($b_split[0]);			
			$widgetToHomepage = getWidgetToHomepage($widgetToHomepageID,true);
			$widget = getHomepageWidget($widgetToHomepage->widgetID);
			
			//get the hash component of 'b'
			$widgetHash = $b_split[1];	
			
			//get the settings from the saved widgetToHomepageID
			$settings = getAllSettingsForHomepageWidget($widgetToHomepageID, true);								
		}

		//if the hash is correct 
		if (md5($widget->contentCode.DOMAIN) == $widgetHash) {
			// if the second check is passed, proceed
			if (md5($request['b'].$request['a'].HASH_SALT) == $request['c']) {			
				//get widget content code and apply settings
				$widgetContent = $widget->contentCode;

				if (!empty($settings)) {
					foreach ($settings as $setting) {
						$setting->value = str_replace("'", "\'", $setting->value);
						if ($setting->name == 'stylesheet') {
							$widgetStyle = $setting->value;
						}
						if (mb_strtolower($setting->name) == 'img_src' && mb_strtolower(mb_substr($setting->value,0,4)) != 'http') {
							$setting->value = 'http://' . $DOMAIN . '/images/' . $setting->value;
						}
						$widgetContent = str_replace('%'.mb_strtoupper($setting->name).'%', $setting->value, $widgetContent);
					}
				}

				//output buffer and clear any output from the widget before eval'ing it
				ob_start();
				$REMOTING_MODE = true;
				eval('?>' . $widgetContent . '<?php ');
				ob_clean();

				//use reflection to get the function we've asked to invoke. Pre-pending 'Remote_'
				//should partly combat invokation of arbitrary php code
				if (function_exists('Remote_'.$request['a'])) {
					$func = new ReflectionFunction('Remote_'.$request['a']);

					//checks that the function is user defined (non-native php or extension), that it was declared in the widget
					//we've just eval'd and that it has only one parameter ($_REQUEST)
					if ($func->isUserDefined() && 
						(strpos($func->getFileName(),'widget_functions.php') !== false) && 
						$func->getNumberOfParameters() == 1) {

						//check that it's doc comment is marked up correctly to allow remote invokation
						if (strpos($func->getDocComment(),'[Jadu(Remotable=true)]') !== false) {
						
							//filter request to get rid of parameter p
							$args = array();
							foreach ($_REQUEST as $key=>$value) {
								if ($key != 'p') {
									$args[$key] = $value;
								}
							}

							$func->invoke($args);
						}
					}
				}
			}
		}
	}
?>
