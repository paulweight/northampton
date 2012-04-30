<?php

	require_once('JaduLibraryFunctions.php');
	
	/**
	* Function used to change the class used on the body when the column is hidden
	* and to decide whether or not to include column
	* @param String $type The type of check to perform, default is to check which class to apply
	*/
	function hideColumn ($type = 'class')
	{
		global $db, $homepagesDateFunctions, $homepage;
		
		/**
		 * The default is to NOT hide the column
		 */
		$hide = false;
		/**
		 * The following array lists any pages that should ALWAYS hide the column on
		 * e.g. $hide_column = array('page1.php', 'page2.php');
		 */
		$hide_column = array('gallery_info.php', 'gallery_item.php', 'podcast_info.php', 'podcast_episode.php', 'resource_schedule.php', 'xforms_form.php');

		if (in_array(basename($_SERVER['PHP_SELF']), $hide_column)) {
			$hide = true;
		}
		// if $homepage is already set then look at the object - cases where the page does not include the homepage ID
		// as a param
		else if (isset($homepage) && is_object($homepage)) {
			if ($homepage->hideTaxonomy == '1') {
				$hide = true;
			}	
		}
		// if it's not being hidden due to page, then check if should hide due
		//to homepage settings
		else if (isset($_REQUEST['homepageID'])) {
			include_once('websections/JaduHomepages.php');
			$homepage = getHomepage($_REQUEST['homepageID']);
			if (isset($homepage) && $homepage->hideTaxonomy == '1') {
				$hide = true;
			}
		}

		return $hide;
	}
?>
