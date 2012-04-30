<?php

	if (!isset($breadcrumb)) {
		$breadcrumb = '';
	}

	// may already have been set in page
	if (!isset($MAST_HEADING) || !isset($MAST_BREADCRUMB) ||
		$MAST_HEADING == '' && $MAST_BREADCRUMB == '') {

			switch($breadcrumb) {
		//404.php
			case '404' :

			$MAST_HEADING = 'Page not found';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Page not found</span></li>';
			break;

		//signin.php
			case 'signin' :

			$MAST_HEADING = 'Sign in to your account';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Your account</span></li>';
			break;

		//signin.php
			case 'mobile' :

			$MAST_HEADING = 'Are you using a handheld device?';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Mobile phones</span></li>';
			break;

		//about_us.php
			case 'accessibility' :

			$MAST_HEADING = 'Accessibility statement';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Accessibility statement</span></li>';
			break;

		//api_apply.php
			case 'apiKeyApply':

			$MAST_HEADING = 'Apply for an API key';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSecureSiteRootURL() . buildUserHomeURL(). '">Your Account</a></li><li class="bc_end"><span>API key application</span></li>';
			break;

		//api_keys.php
			case 'apiKey':

			$MAST_HEADING = 'Your API keys';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSecureSiteRootURL() . buildUserHomeURL(). '">Your Account</a></li><li class="bc_end"><span>Your API key</span></li>';
			break;

		//az_home.php
			case 'azHome' :

			$MAST_HEADING = 'A to Z';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>A to Z of services</span></li>';
			break;

		//az_index.php
			case 'azIndex' :

			$MAST_HEADING = 'A to Z: '. $startsWith ;
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildAToZURL() . '" >A to Z</a></li><li class="bc_end"><span>'. encodeHtml($startsWith) .'</span></li>';
			break;

		//balances.php
			case 'balances' :

			$MAST_HEADING = 'Balances';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSecureSiteRootURL() . buildUserHomeURL() . '">Your Account</a></li><li class="bc_end"><span>Online balance enquiry</span></li>';
			break;

		//blog_index.php
			case 'blogs' :
			$MAST_HEADING = 'Blogs';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li>';
			if (isset($_REQUEST['categoryID'])) {
				$MAST_BREADCRUMB .= '<li><a href="' . buildBlogURL() . '">Blogs</a></li><li class="bc_end">'. encodeHtml($currentCategory->name) .'</li>';
			}
			else {
				$MAST_BREADCRUMB .= '<li class="bc_end">Blogs</li>';
			}
			break;

		//change_details.php
			case 'changeDetails' :

			$MAST_HEADING = 'Change your details';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSecureSiteRootURL() . buildUserHomeURL() . '">Your Account</a></li></li><li class="bc_end"><span>Change your details</span></li>';
			break;

		//change_password.php
			case 'changePassword' :

			$MAST_HEADING = 'Change Password';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSecureSiteRootURL() .buildUserHomeURL() .'">Your Account</a></li><li class="bc_end"><span>Change Password</span></li>';
			break;

		//contact.php
			case 'contactPage' :

			$MAST_HEADING = 'Contact Us';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Contact Us</span></li>';
			break;

		//council_democracy_index.php
			case 'councillorsIndex' :

			$MAST_HEADING = 'Councillors';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Councillors</span></li>';
			break;

		//councillors.php
			case 'councillorsView' :

			$MAST_HEADING = 'Councillors by '. $pageTitle;
			$MAST_BREADCRUMB = ' <li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildCouncillorsURL() .'" >Councillors</a></li><li class="bc_end"><span>Find a Councillor</span></li>';
			break;

		//councillors_info.php
			case 'councillorInfo' :

			$MAST_HEADING = 'Councillor '. $councillor->forename .' '. $councillor->surname;
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildCouncillorsURL() .'">Councillors</a></li><li class="bc_end"><span>' .encodeHtml($councillor->forename .' '. $councillor->surname) .'</span></li>';
			break;

		//directory.php
			case 'directoriesCat' :

			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li>';
			if(count($dirTree) > 0) {
				$MAST_BREADCRUMB .= '<li><a href="' . buildDirectoriesURL() . '">Online directories</a></li>';
			}
			else {
				$MAST_BREADCRUMB .= '<li class="bc_end"><span>Online directories</span></li>';
			}
			$MAST_HEADING = 'Online directories';
			$levelNo = 1;
			$count = 0;
			foreach ($dirTree as $parent) {
				if ($count < sizeof($dirTree) - 1) {
					$MAST_BREADCRUMB .= '<li><a href="' . buildDirectoriesURL($parent->id) . '" >'. encodeHtml($parent->name) .'</a></li>';
				}
				else {
					$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($parent->name) .'</span></li>';
					$MAST_HEADING = $parent->name .' Directories';
				}
				$count++;
				$levelNo++;
			}

			break;

		//directory_info.php
			case 'directoriesInfo' :

			$MAST_HEADING = $directory->name;
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . buildDirectoriesURL() . '">Online directories</a></li><li class="bc_end"><span>'. encodeHtml($directory->name) .'</span></li>';
			break;

		//directory_record.php
			case 'directoryRecord' :
			$MAST_HEADING = $directory->name . ': '. $record->title;
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . buildDirectoriesURL(-1, $directory->id) . '">'.encodeHtml($directory->name).'</a></li>';
			foreach ($dirTree as $cat) {
				$MAST_BREADCRUMB .= '<li><a href="' . buildDirectoryCategoryURL($directory->id, $category->id, $categoryInfo->id) . '">'. encodeHtml($cat->title) .'</a></li>';
			}
			if (isset($_REQUEST['categoryID'])) {
				$MAST_BREADCRUMB .= '<li><a href="' . buildDirectoryCategoryURL($directory->id, $category->id, $categoryInfo->id) . '">'. encodeHtml($category->title) .'</a></li>';
			}
			$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($record->title) .'</span></li>';
			break;
		//directory_submit.php
			case 'directorySubmit' :

			$MAST_HEADING = $directory->name .' - Submit a record';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . buildDirectoriesURL() . '">Online directories</a></li><li class="bc_end"><span><a href="' . buildDirectoriesURL(-1, $directory->id) . '">'. encodeHtml($directory->name) .'</a></span></li><li class="bc_end"><span>submit a record</span></li>';
			break;

		//directory_category.php
			case 'directoryCategory' :

			$MAST_HEADING = $category->title;
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . buildDirectoriesURL(-1, $directory->id) . '">'.encodeHtml($directory->name).'</a></li>';
			foreach ($dirTree as $cat) {
				$MAST_BREADCRUMB .= '<li><a href="' . buildDirectoryCategoryURL($directory->id, $cat->id, $categoryInfo->id) . '">'. encodeHtml($cat->title) .'</a></li>';
			}

			$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($category->title) .'</span></li>';

			break;

		//directoryAZ.php
			case 'directoryAZ' :

			$MAST_HEADING = $directory->name .' A to Z of services';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>'. encodeHtml($directory->name) .' A to Z</span></li>';
			break;

		//directorySearch.php
			case 'directorySearch' :

			$MAST_HEADING = $directory->name .' search';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . buildDirectoriesURL() . '">Online directories</a></li>';
			$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() .buildDirectoriesURL(-1, $directory->id).'">'. encodeHtml($directory->name) .'</a></li><li class="bc_end"><span>search</span></li>';
			break;


		//documents_index.php
			case 'documentsIndex' :

			$MAST_HEADING = 'Online information';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Online information</span></li>';
			break;

		//documents_info.php
			case 'documentsInfo' :

			$MAST_HEADING = $header->title;
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li>';
			foreach ($dirTree as $parent) {
				$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildDocumentsCategoryURL($parent->id) . '" >'. encodeHtml($parent->name) .'</a></li>';
			}
			$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($header->title) .'</span></li>';
			break;

		//documents.php
			case 'documentsCat' :

			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li>';
			$levelNo = 1;
			$count = 0;
			foreach ($dirTree as $parent) {
				if ($count < sizeof($dirTree) - 1) {
					$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildDocumentsCategoryURL($parent->id).'" >'. encodeHtml($parent->name) .'</a></li>';
				}
				else {
					$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($parent->name) .'</span></li>';
					$MAST_HEADING = $parent->name;
				}
				$count++;
				$levelNo++;
			}

			break;

		//download_info.php
			case 'downloadInfo' :

			if ($download->id == '-1'){
				$MAST_HEADING = 'Download not found';
			}
			else {
				$MAST_HEADING = $download->title;
			}
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildDownloadsURL(). '">Downloads</a></li>';
			foreach ($dirTree as $parent) {
				$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildDownloadsURL($parent->id).'" >'. encodeHtml($parent->name) .'</a></li>';
			}
			$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($download->title) .'</span></li>';
			break;

		//downloads_index.php
			case 'downloadsIndex' :

			$MAST_HEADING = 'Document Downloads';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Downloads</span></li>';
			break;

		//downloads.php
			case 'downloadCats' :

			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildDownloadsURL() .'">Downloads</a></li>';
			$levelNo = 1;
			$count = 0;
			foreach ($dirTree as $parent) {
				if ($count < sizeof($dirTree) - 1) {
					$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildDownloadsURL($parent->id) .'" >'. encodeHtml($parent->name) .'</a></li>';
				}
				else {
					$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($parent->name) .'</span></li>';
					$MAST_HEADING = 'Document Downloads: '.$parent->name;
				}
				$count++;
				$levelNo++;
			}
			break;

		//email_friend.php
			case 'emailFriend' :

			$MAST_HEADING = 'Email a friend';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Email a friend</span></li>';
			break;

		//enforcement.php
			case 'enforcement' :

			$MAST_HEADING = 'Enforcement notice search';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Enforcement Notice search</span></li>';
			break;

		//enforcement_list.php
			case 'enforcementList' :

			$MAST_HEADING = 'Enforcement notices';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Enforcement Notices</span></li>';
			break;

		//enforcement_details.php
			case 'enforcementDetails' :

			$MAST_HEADING = $notice->getFormattedValueForField('noticeRef');
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() .'/site/scripts/enforcement.php">Enforcement Notice search</a></li><li class="bc_end"><span>Enforcement Notice details</span></li>';
			break;

		//events_index.php
			case 'eventsIndex' :

			$MAST_HEADING = 'Featured event';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Events</span></li>';
			break;

		//event_categories.php
			case 'eventsCatIndex' :

			$MAST_HEADING = 'Events';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Events</span></li>';
			break;

		//event_new.php
			case 'eventsSubmit' :

			$MAST_HEADING = 'Suggest an event';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildEventsURL() .'">Featured event</a></li><li class="bc_end"><span>Suggest an event</span></li>';
			break;

		//events.php
			case 'eventsCats' :

			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildEventsURL() .'">Featured event</a></li>';
			$levelNo = 1;
			$count = 0;
			foreach ($dirTree as $parent) {
				if ($count < sizeof($dirTree) - 1) {
					$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildEventsURL($parent->id) .'" >'. encodeHtml($parent->name) .'</a></li>';
				}
				else {
					$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($parent->name) .'</span></li>';
					$MAST_HEADING = 'Events: '.$parent->name;
				}
				$count++;
				$levelNo++;
			}
			break;

		//events_info.php
			case 'eventsInfo' :

			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildEventsURL() .'">Events</a></li>';
			if (isset($location)) {
				$MAST_BREADCRUMB .= '<li class="bc_end"><span>' . encodeHtml($location) .'</span></li>';
				$MAST_HEADING = 'Events Listings: '. $location;
			}
			else if (isset($period) && $period == 'full') {
				$MAST_BREADCRUMB .= '<li class="bc_end"><span>All Events</span></li>';
				$MAST_HEADING = 'Events Listings: All Events';
			}
			else if (isset($_GET['eventID'])) {
				$MAST_BREADCRUMB .= '<li class="bc_end"><span>' . encodeHtml($event->title) . '</span></li>';
				$MAST_HEADING = 'Events Listings: '. $event->title;
			}
			else if ($startTimestamp == $endTimestamp) {
				$MAST_BREADCRUMB .= '<li class="bc_end"><span>' . formatDateTime(FORMAT_DATE_MEDIUM, $startTimestamp) . '</span></li>';
				$MAST_HEADING = 'Events Listings: '. formatDateTime(FORMAT_DATE_MEDIUM, $startTimestamp);
			}
			else {
				$MAST_BREADCRUMB .= '<li class="bc_end"><span>Events Listings</span></li>';
				$MAST_HEADING = 'Events Listings';
			}
			break;

		//faq_info.php
			case 'faqInfo' :

			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildFAQURL() .'">FAQs</a></li>';
			$MAST_HEADING = 'Frequently Asked Questions';
			break;

		//faqs_ask.php
			case 'faqAsk' :

			$MAST_HEADING = 'Ask a Question';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a  href="' . getSiteRootURL() . buildFAQURL().'">FAQs</a></li><li class="bc_end"><span>Ask us a Question</span></li>';
			break;

		//faqs_index.php
			case 'faqsIndex' :

			$MAST_HEADING = 'Frequently Asked Questions';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>FAQs</span></li>';
			break;

		//faqs.php
			case 'faqsCats' :

			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildFAQURL() .'">FAQs</a></li>';
			$levelNo = 1;
			$count = 0;
			foreach ($dirTree as $parent) {
				if ($count < sizeof($dirTree) - 1) {
					$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildFAQURL(false, $parent->id) .'" >'. encodeHtml($parent->name) .'</a></li>';
				}
				else {
					$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($parent->name) .'</span></li>';
					$MAST_HEADING = 'Frequently Asked Questions: ' .$parent->name;
				}
				$count++;
				$levelNo++;
			}
			break;

		//feedback.php
			case 'feedback' :

			$MAST_HEADING = 'Your Feedback';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a  href="' . getSiteRootURL() . buildContactURL() .'" >Contact Us</a></li><li class="bc_end"><span>Feedback</span></li>';
			break;
		
	
		//forgot_password.php
			case 'forgotPassword' :

			$MAST_HEADING = 'Password Reminder';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Password Reminder</span></li>';
			break;

		//forms.php
			case 'formCats' :

			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildXFormsURL() .'" >Online forms</a></li>';
			$levelNo = 1;
			$count = 0;
			foreach ($dirTree as $parent) {
				if ($count < sizeof($dirTree) - 1) {
					$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildFormsCategoryURL($parent->id).'" >'. encodeHtml($parent->name) .'</a></li>';
				}
				else {
					$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($parent->name) .'</span></li>';
					$MAST_HEADING = 'Online forms: '. $parent->name ;
				}
				$count++;
				$levelNo++;
			}
			break;

		//google_advanced.php
			case 'googleAdvanced' :

			$MAST_HEADING = 'Advanced search';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Advanced search</span></li>';
			break;

		//google_results.php
			case 'googleResults' :

			$MAST_HEADING = 'Search results';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Search Results</span></li>';
			break;

		//contact details
			case 'index.php' :
			$indexPage = true;
			$MAST_HEADING = METADATA_GENERIC_NAME;
			$MAST_BREADCRUMB = '<li>Home</li>';
			break;

		//home_info.php
			case 'homeInfo' :

			$MAST_HEADING = $homepage->title;
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>'. encodeHtml($homepage->title) .'</span></li>';
			break;

		//links.php
			case 'links' :

			$MAST_HEADING = 'External links and web resources';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Links and Web Resources</span></li>';
			break;

		//location.php
			case 'location' :

			$MAST_HEADING = 'Council Location';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildContactURL() .'" >Contact Us</a></li><li class="bc_end"><span>Location</span></li>';
			break;

		//meetings_committees.php
			case 'meetingsCommittees' :

			$MAST_HEADING = 'Agendas, Reports and Minutes';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a  href="' . getSiteRootURL() . buildMeetingsURL() .'" >Agendas, Reports and Minutes</a></li><li class="bc_end"><span>'. encodeHtml($header->title) .'</span></li>';
			break;

		//meetings_index.php
			case 'meetingsIndex' :

			$MAST_HEADING = 'Agendas, Reports and Minutes';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Agendas, Reports and Minutes</span></li>';
			break;

		//meetings_info.php
			case 'meetingsInfo' :

			$MAST_HEADING = 'Agendas, Reports and Minutes';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildMeetingsURL() .'" >Agendas, Reports and Minutes</a></li><li><a href="' . getSiteRootURL() . buildMeetingsURL(-1, 'committee', $header->id) .'" >'. $header->title .'</a></li><li class="bc_end"><span>'. formatDateTime(FORMAT_DATE_FULL, $meeting->meetingMinutesDate) .'</span></li>';
			break;

		//meetings.php
			case 'meetingsCats' :

			$MAST_HEADING = 'Agendas, Reports and Minutes';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildMeetingsURL(). '" >Agendas, Reports and Minutes</a></li>';
			$levelNo = 1;
			$count = 0;
			foreach ($dirTree as $parent) {
				if ($count < sizeof($dirTree) - 1) {
					$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMeetingsURL($parent->id) .'" >'. encodeHtml($parent->name) .'</a></li>';
				}
				else {
					$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($parent->name) .'</span></li>';
				}
				$count++;
				$levelNo++;
			}
			break;

		//meetings_archive.php
			case 'meetingsArchive' :

			$MAST_HEADING = 'Agendas, Reports and Minutes';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildMeetingsURL(). '" >Agendas, Reports and Minutes</a></li><li class="bc_end"><span>Archive</span></li>';
			break;

		//my_area_lookup.php
			case 'myArea' :

			$MAST_HEADING = 'In my area...';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>In my area</span></li>';
			break;

		//news_article.php
			case 'newsArchive' :

			$MAST_HEADING = 'News Archive';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildNewsURL() .'" >Latest news</a></li><li class="bc_end"><span>News archive</span></li>';
			break;

		//news_article.php
			case 'newsArticle' :

			$MAST_HEADING = $news->title;
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildNewsURL() .'" >Latest news</a></li><li class="bc_end"><span>'. encodeHtml($news->title) .'</span></li>';
			break;

		//news_category.php
			case 'newsCats' :

			$MAST_HEADING = $currentCategory->name .' news';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildNewsURL() .'" >Latest news</a></li>';
			$levelNo = 1;
			$count = 0;
			foreach ($dirTree as $parent) {
				if ($count < sizeof($dirTree) - 1) {
					$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildNewsURL($parent->id) .'" >'. encodeHtml($parent->name) .'</a></li>';
				}
				else {
					$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($parent->name) .'</span></li>';
				}
				$count++;
				$levelNo++;
			}
			break;

		//news_index.php
			case 'newsIndex' :

			$MAST_HEADING = 'Latest news';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Latest news</span></li>';
			break;

		//pageComments.php
			case 'comments' :

			$MAST_HEADING = 'Send us your comments';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Send us your comments</span></li>';
			break;

		//payments.php
			case 'payments' :

			$MAST_HEADING = $homepage->title;
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Online Payments</span></li>';
			break;

		//payments_provider.php
			case 'paymentsProvider' :

			$MAST_HEADING = 'Services Basket';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Online Payments</span></li>';
			break;

		//pid
			case 'pidscript' :

			$MAST_HEADING = 'A to Z';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildAToZURL() .'">A to Z of services</a></li><li class="bc_end"><span>'. encodeHtml($service->title) .'</span></li>';
			break;


		//planx_advsearch.php
			case 'planxAdvSearch' :

			$MAST_HEADING =  'Planning application advanced search';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Planning application advanced search</span></li>';
			break;

		//planx_comment.php
			case 'planxComment' :

			$MAST_HEADING =  'Comment on an application';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Comment on an application</span></li>';
			break;

		//planx_details.php
			case 'planxDetails' :

			$MAST_HEADING =  $app->getFormattedValueForField('applicationNumber');
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>'.$app->getFormattedValueForField('applicationNumber').'</span></li>';
			break;

		//planx_lpindex.php
			case 'planxLpIndex' :

			if (isset($_GET['planID'])) {
				$MAST_HEADING = $plan->title;
			}
			else {
				$MAST_HEADING = 'Planning Policy: Available Online Plans';
			}
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Planning Policy: Available Online Plans</span></li>';
			break;

		//planx_lpmaps.php
			case 'planxLpMap' :

			$MAST_HEADING = $map->title;
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>'.encodeHtml($map->title).'</span></li>';
			break;

		//planx_lppolicy.php
			case 'planxLpPolicy' :

			$MAST_HEADING = $policy->title;
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>'.encodeHtml($policy->title).'</span></li>';
			break;

		//planx_lpsearch_results.php
			case 'planxLpSearchResults' :

			$MAST_HEADING =  'Planning policy search';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Planning policy search</span></li>';
			break;

		//planx_lpsearch.php
			case 'planxLpSearch' :

			$MAST_HEADING =  'Planning policy search';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Planning policy search</span></li>';
			break;

		//planx_results.php
			case 'planxResults' :

			$MAST_HEADING =  'Planning application search';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Planning application search</span></li>';
			break;

		//planx_search.php
			case 'planxSearch' :

			$MAST_HEADING =  'Planning application search';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Planning application search</span></li>';
			break;

		//planx_search.php
			case 'planxSearchResult' :

			$MAST_HEADING =  'Planning application search results';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Planning application search results</span></li>';
			break;

		//planx_track.php
			case 'planxTrack' :

			$MAST_HEADING =  'Track planning applications';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Track planning applications</span></li>';
			break;

		//planx_track.php
			case 'planxWeekly' :

			$MAST_HEADING =  'Application weekly list';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Application weekly list</span></li>';
			break;

		//poll_past_results.php
			case 'pollList' :

			$MAST_HEADING = 'Past Polls';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Past polls</span></li>';
			break;

		//poll_results.php
			case 'pollResults' :

			$MAST_HEADING = 'Poll Results';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Poll results</span></li>';
			break;

		//press_archive.php
			case 'PressReleasesArchive' :

			$MAST_HEADING = 'Press Release Archive';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildPressURL() . '" >Latest Press releases</a></li><li class="bc_end"><span>Press release archive</span></li>';
			break;

		//press_article.php
			case 'PressReleasesArticle' :

			$MAST_HEADING = $pressRelease->title;
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildPressURL() . '" >Latest Press releases</a></li><li class="bc_end"><span>'. encodeHtml($pressRelease->title) .'</span></li>';
			break;

		//press_category.php
			case 'PressReleasesCats' :

			$MAST_HEADING = $categoryViewing->name .' press releases';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildPressURL() . '" >Latest Press releases</a></li>';
			$levelNo = 1;
			$count = 0;
			foreach ($dirTree as $parent) {
				if ($count < sizeof($dirTree) - 1) {
					$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildPressURL($parent->id, $parent->name) .'" >'. encodeHtml($parent->name) .'</a></li>';
				}
				else {
					$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($parent->name) .'</span></li>';
				}
				$count++;
				$levelNo++;
			}

			break;

		//press_index.php
			case 'PressReleasesIndex' :

			$MAST_HEADING = 'Latest Press releases';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Latest Press releases</span></li>';
			break;

		//recruit_details.php
			case 'recruitDetails' :

			$MAST_HEADING = 'Current vacancies';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildJobsURL() . '">Current vacancies</a></li><li class="bc_end"><span>'.encodeHtml($job->title).'</span></li>';
			break;

		//recruit_jobs.php
			case 'recruitJobs' :

			$MAST_HEADING = 'Current vacancies';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Current vacancies</span></li>';
			break;

		//register_accept.php
			case 'registerAccept' :

			$MAST_HEADING = 'Thank you for your registration';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSecureSiteRootURL() . buildUserHomeURL().'">Your Account</a></li><li class="bc_end"><span>Registration accepted</span></li>';
			break;

		//register_authorisation.php
			case 'registerAuthorisation' :

			$MAST_HEADING = 'Registration';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Registration</span></li>';
			break;

		//register.php
			case 'register' :

			$MAST_HEADING = 'Why should I register?';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Register</span></li>';
			break;

		//rss_about.php
			case 'rssAbout' :

			$MAST_HEADING = 'RSS Feed';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildNewsURL() .'">News</a></li><li class="bc_end"><span>RSS news feed</span></li>';
			break;

		//rss_about.php
			case 'rssPodcastAbout' :

			$MAST_HEADING = 'About podcasts';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL() . '">Podcasts</a></li><li class="bc_end"><span>About podcasts</span></li>';
			break;

		//search_index.php
			case 'jaduSearch' :

			$MAST_HEADING = 'Advanced Search';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Advanced search</span></li>';
			break;

		//search_results.php
			case 'jaduSearchResults' :

			$MAST_HEADING = 'Search Results';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildSearchURL() .'">Advanced search</a></li><li class="bc_end"><span>Search results</span></li>';
			break;

		//services_info.php
			case 'servicesInfo' :

			$MAST_HEADING = 'A to Z';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildAToZURL() .'">A to Z of services</a></li><li class="bc_end"><span>'. encodeHtml($service->title) .'</span></li>';
			break;

		//services.php
			case 'serviceCats' :

			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li><a href="' . getSiteRootURL() . buildAToZURL() . '">A to Z of services</a></li>';
			$levelNo = 1;
			$count = 0;
			foreach ($dirTree as $parent) {
				if ($count < sizeof($dirTree) - 1) {
					$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildAZServicesCategoryURL($parent->id, $parent->name) .'" >'. encodeHtml($parent->name) .'</a></li>';
				}
				else {
					$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($parent->name) .'</span></li>';
					$MAST_HEADING = 'Services: '.$parent->name;
				}
				$count++;
				$levelNo++;
			}
			break;

		//services_crawl.php
			case 'servicesCrawl' :

			$MAST_HEADING = 'A to Z';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>A to Z services list</span></li>';
			break;

		//site_map.php
			case 'sitemap' :

			$MAST_HEADING = 'Site Map';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Site map</span></li>';
			break;

		//terms.php
			case 'terms' :

			$MAST_HEADING = 'Terms and Disclaimer';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Terms and disclaimer</span></li>';
			break;

		//thanks.php
			case 'thanks' :

			$MAST_HEADING = 'Contact Us';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Contact us</span></li>';
			break;

		//unsubscribe.php
			case 'unsubscribe' :

			$MAST_HEADING = 'Unsubscribe';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Unsubscribe</span></li>';
			break;

		//user_form_archive.php
			case 'userFormArchive' :

			$MAST_HEADING = 'Online Form archive';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSecureSiteRootURL() . buildUserHomeURL().'">Your Account</a></li><li class="bc_end"><span>Online form archive</span></li>';
			break;

		//user_form_info.php
			case 'userFormInfo' :

			$MAST_HEADING = 'Form archive';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSecureSiteRootURL() . buildUserHomeURL().'">Your Account</a></li><li><a href="' . getSecureSiteRootURL() . buildUserFormURL() .'" >Form Archive</a></li><li class="bc_end"><span>'. encodeHtml($form->title) .'</span></li>';
			break;

		//user_job_archive.php
			case 'userJobArchive' :

			$MAST_HEADING = 'Job application archive';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSecureSiteRootURL() . buildUserHomeURL(). '">Your Account</a></li><li class="bc_end"><span>Job application archive</span></li>';
			break;

		//user_home.php
			case 'userHome' :

			$MAST_HEADING = 'Your Account';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Your account</span></li>';
			break;

		//user_settings.php
			case 'userSettings' :

			$MAST_HEADING = 'Settings for accessibility';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Your settings</span></li>';
			break;

		//view_feeds.php
			case 'viewFeeds' :

			if (isset($viewMode) && $viewMode) {
				$MAST_HEADING = $RSSItem->name;
			}
			else {
				$MAST_HEADING = 'View external feeds';
			}

			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li>';
			if ($viewFeed) {
				$MAST_BREADCRUMB .=  '<li><a href="' . getSiteRootURL(). buildFeedsURL().'">External feeds</a></li><li class="bc_end"><span>'. encodeHtml($feed->name) .'</span></li>';
			}
			else {
				$MAST_BREADCRUMB .= '<li class="bc_end"><span>External feeds</span></li>';
			}
			break;

		//website_statistics_detail.php
			case 'webStatDetails' :

			$MAST_HEADING = 'Website Statistics '. sprintf("%s %s", strftime('%B', mktime(0, 0, 0, $month, 1, $year)), $year);
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/site/" >Home</a></li><li class="bc_end"><span>Website Statistics '. sprintf("%s %s", strftime('%B', mktime(0, 0, 0, $month, 1, $year)), $year) .'</span></li>';
			break;

		//website_statistics.php
			case 'webStat' :

			$MAST_HEADING = 'Website Statistics '. $year;
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Website Statistics : '.encodeHtml($year) .'</span></li>';
			break;

		//whats_new_index.php
			case 'whatsNew' :

			$MAST_HEADING = "What's New";
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>What&#39;s new</span></li>';
			break;

		//whos my councillor post code search
			case 'whosMyCouncillor' :

			$MAST_HEADING = "Who is my Councillor?";
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildCouncillorsURL() .'">Councillors</a></li><li class="bc_end"><span>Who is my Councillor?</span></li>';
			break;

		//xforms form
			case 'xformsForm' :

			$MAST_HEADING = 'Online forms: '. $form->title;
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildXFormsURL().'">Online forms</a></li><li class="bc_end"><span>'. encodeHtml($form->title) .'</span></li>';
			break;

		//xforms_index.php
			case 'xformsIndex' :

			$MAST_HEADING = 'Online Forms';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Online forms</span></li>';
			break;

		//podcast_info.php
			case 'podcastInfo' :

			if ($podcast->id == '-1'){
				$MAST_HEADING = 'Podcast not found';
			}
			else {
				$MAST_HEADING = $podcast->title;
			}
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL() . '">Podcasts</a></li>';
			foreach ($dirTree as $parent) {
				$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL($parent->id) .'" >'. encodeHtml($parent->name) .'</a></li>';
			}
			$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($podcast->title) .'</span></li>';
			break;

		//podcast_episode.php
			case 'podcastEpisode' :

			if ($episode->id == '-1'){
				$MAST_HEADING = 'Podcast episode not found';
			}
			else {
				$MAST_HEADING = $podcast->title;
			}
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL() . '">Podcasts</a></li>';
			foreach ($dirTree as $parent) {
				$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL($parent->id) . '">'. encodeHtml($parent->name) .'</a></li>';
			}
			$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL(-1, $podcast->id) . '">'. encodeHtml($podcast->title) .'</a></li>';
			$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($episode->title) .'</span></li>';

			break;

		//podcasts_index.php
			case 'podcastsIndex' :

			$MAST_HEADING = 'Podcasts';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Podcasts</span></li>';
			break;

		//podcasts.php
			case 'podcastCats' :

			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL() . '">Podcasts</a></li>';
			$levelNo = 1;
			$count = 0;
			foreach ($dirTree as $parent) {
				if ($count < sizeof($dirTree) - 1) {
					$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaPodcastsURL($parent->id) . '" >'. encodeHtml($parent->name) .'</a></li>';
				}
				else {
					$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($parent->name) .'</span></li>';
					$MAST_HEADING = 'Podcasts: '.encodeHtml($parent->name);
				}
				$count++;
				$levelNo++;
			}
			break;

		//gallery_info.php
			case 'galleryInfo' :

			if ($gallery->id == '-1'){
				$MAST_HEADING = 'Gallery not found';
			}
			else {
				$MAST_HEADING = $gallery->title;
			}
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildMultimediaGalleriesURL() . '">Galleries</a></li>';
			foreach ($dirTree as $parent) {
				$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaGalleriesURL($parent->id) .'" >'. encodeHtml($parent->name) .'</a></li>';
			}
			$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($gallery->title) .'</span></li>';
			break;

		//gallery_item.php
			case 'galleryItem' :

			if ($item->id == '-1'){
				$MAST_HEADING = 'Gallery item not found';
			}
			else {
				$MAST_HEADING = $gallery->title;
			}
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildMultimediaGalleriesURL() . '">Galleries</a></li>';
			foreach ($dirTree as $parent) {
				$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaGalleriesURL($parent->id) . '" >'. encodeHtml($parent->name) .'</a></li>';
			}
			$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaGalleriesURL(-1, $gallery->id) . '" >'. encodeHtml($gallery->title) .'</a></li>';
			$MAST_BREADCRUMB .= '<li class="bc_end"><span>'. encodeHtml($item->title) .'</span></li>';

			break;

		//galleries_index.php
			case 'galleriesIndex' :

			$MAST_HEADING = 'Galleries';
			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li class="bc_end"><span>Galleries</span></li>';
			break;

		//galleries.php
			case 'galleryCats' :

			$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'/">Home</a></li><li><a href="' . getSiteRootURL() . buildMultimediaGalleriesURL() . '">Galleries</a></li>';
			$levelNo = 1;
			$count = 0;
			foreach ($dirTree as $parent) {
				if ($count < sizeof($dirTree) - 1) {
					$MAST_BREADCRUMB .= '<li><a href="' . getSiteRootURL() . buildMultimediaGalleriesURL($parent->id) . '" >' . encodeHtml($parent->name) . '</a></li>';
				}
				else {
					$MAST_BREADCRUMB .= '<li class="bc_end"><span>' . encodeHtml($parent->name) . '</span></li>';
					$MAST_HEADING = 'Galleries: ' . encodeHtml($parent->name);
				}
				$count++;
				$levelNo++;
			}
			break;

			default:
			$MAST_HEADING = METADATA_GENERIC_NAME;
			$MAST_BREADCRUMB = '<li>Home</li>';
			break;

			}

	}
?>