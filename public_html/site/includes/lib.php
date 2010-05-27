<?php

	require_once('JaduLibraryFunctions.php');
	
	/**
	* Function used to change the class used on the body when the column is hidden
	* and to decide whether or not to include column
	* @param String $type The type of check to perform, default is to check which class to apply
	*/
	function toggleColumn ($type = 'class')
	{
		/**
		 * The default is to NOT hide the column
		 */
		$hide = false;
		$specialColumn = false;
		/**
		 * The following array lists any pages that should ALWAYS hide the column on
		 * e.g. $hide_column = array('page1.php', 'page2.php');
		 */
		$hide_column = array('register.php', 'az_index.php', 'az_home.php', 'services_info.php', 'location.php', 'xforms_index.php', 'forms.php', 'xforms_form.php', 'site_map.php', 'signin.php', 'change_details.php', 'user_settings.php', 'google_advanced.php', 'google_results.php', 'services_crawl.php', 'pid', '404.php', 'search_index.php', 'search_results.php');
		
		$home_column = array( 'index.php', 'home_info.php');
		if (in_array(basename($_SERVER['PHP_SELF']), $home_column)) {
			$specialColumn = true;
		}

		if (in_array(basename($_SERVER['PHP_SELF']), $hide_column)) {
			$hide = true;
		}
		// if it's not being hidden due to page, then check if should hide due
		//to homepage settings
		else if (isset($_REQUEST['homepageID']) || strpos($_SERVER['REQUEST_URI'], '/site/index.php') !== false) {
			include_once('websections/JaduHomepages.php');
			if (isset($_REQUEST['homepageID'])) {
				$homepage = getHomepage($_REQUEST['homepageID']);
			}
			else {
				$allIndependantHomepages = getAllHomepagesIndependant();
				$homepage = getHomepage($allIndependantHomepages[0]->id);
			}
			if ($homepage->hideTaxonomy == '1') {
				$hide = true;
			}
		}
		// decide what to do
		switch($type) {
			case 'class':
				if ($specialColumn) {
					return ' class="full"';
				}
				if (!$hide) {
					return ' class="withWidth"';
				}

			break;
			case 'include':
			default:
				return $hide;
			break;
		}
	}
?>
