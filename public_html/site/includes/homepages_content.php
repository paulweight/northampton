<?php
	if (isset($homepageSections) && count($homepageSections) > 0) {
		foreach ($homepageSections as $row) {
?>
		<div class="row_divider">
<?php
			$numberOfWidgetsOnRow = sizeof($row);
			$numberOfWidgetMarker = '1';
			foreach ($row as $widget) {
				if (!is_array($widget)) {
					
					$w = new HomepageWidget();
					$w->id = $widget->widgetID;
					$w->mainSite = $widget->isWidgetFromMainSite();
					
					$widgetContent = getHomepageWidget($w->getInternalID());
					
                    if (!$widgetContent->memberOnly || Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
						$widgetContent = $widgetContent->contentCode;
						$settings = $widget->getWidgetSettings();

						// add token for ajax requests
						$xmlHttpToken = base64_encode($widget->id.'_'.md5($widgetContent.DOMAIN));
						$widgetContent = str_replace('%XML_HTTP_TOKEN%', $xmlHttpToken, $widgetContent);

						$widgetStyle = '';
						if (!empty($settings)) {
							foreach ($settings as $setting) {
								if ($setting->name == 'stylesheet') {
									$widgetStyle = $setting->value;
								}
								$widgetContent = str_replace("'%" . mb_strtoupper($setting->name) . "%'", "'" . str_replace("'", "\'", $setting->value) . "'", $widgetContent);
								$widgetContent = str_replace('"%' . mb_strtoupper($setting->name) . '%"', '"' . str_replace('"', '\"', $setting->value) . '"', $widgetContent);
								$widgetContent = str_replace('%'  . mb_strtoupper($setting->name) . '%', $setting->value, $widgetContent);
							}	
						}
?>
						<div class="new_widget <?php if(isset($widgetStyle)) { print encodeHtml($widgetStyle); } else { print 'styleLess'; } ?> width<?php print (int) $widget->widthPercentage; ?> <?php if($numberOfWidgetMarker == $numberOfWidgetsOnRow) {?> lastWidget<?php print (int) $widget->widthPercentage; ?> <?php } ?>">
						<div class="widgetPadding">
<?php 
						eval('?>' . $widgetContent . '<?php ');
?>
						</div>
						</div>
<?php
					}
				} 
				else {
?>
					<div class="new_widget width<?php print (int) $widget[0]->widthPercentage; ?><?php if($numberOfWidgetMarker == $numberOfWidgetsOnRow) {?> lastWidget<?php print (int) $widget[0]->widthPercentage; ?> <?php } ?>" >
					<div class="widgetPadding">
<?php
					foreach ($widget as $stack) {
						$w = new HomepageWidget();
						$w->id = $stack->widgetID;
						$w->mainSite = $stack->isWidgetFromMainSite();
						
						$widgetContent = getHomepageWidget($w->getInternalID());
						
						if (!$widgetContent->memberOnly || Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
							$widgetContent = $widgetContent->contentCode;
							$settings = $stack->getWidgetSettings();
							
							// add token for ajax requests
							$xmlHttpToken = base64_encode($stack->id.'_'.md5($widgetContent));
							$widgetContent = str_replace('%XML_HTTP_TOKEN%', $xmlHttpToken, $widgetContent);

							$widgetStyle = '';
							if (!empty($settings)) {
								foreach ($settings as $setting) {
									if ($setting->name == 'stylesheet') {
										$widgetStyle = $setting->value;
									}									
									$widgetContent = str_replace("'%" . mb_strtoupper($setting->name) . "%'", "'" . str_replace("'", "\'", $setting->value) . "'", $widgetContent);
									$widgetContent = str_replace('"%' . mb_strtoupper($setting->name) . '%"', '"' . str_replace('"', '\"', $setting->value) . '"', $widgetContent);
									$widgetContent = str_replace('%'  . mb_strtoupper($setting->name) . '%', $setting->value, $widgetContent);	
								}
							}
?>
							<div class="stacking <?php if(!empty($widgetStyle)) { print encodeHtml($widgetStyle); } else { print 'styleLess'; } ?>">
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
				$numberOfWidgetMarker++;
			}
?>
				<br class="clear" />
			</div>
<?php
		}
	}
?>