<?php
	switch($breadcrumb) {

//404.php
	case '404' :
	
	$MAST_HEADING = 'Page not found';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Page not found</li>';
	break;


//about_us.php
	case 'accessibility' :
	$MAST_HEADING = 'Accessibility statement';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Accessibility statement</li>';
	break;

//az_home.php
	case 'azHome' :
	$MAST_HEADING = 'Council services';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Council services</li>';
	break;

//az_index.php
	case 'azIndex' :
	$MAST_HEADING = 'Council services';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/az_home.php" >Council services</a></li><li class="bc_end">That begin with '. $startsWith .'</li>';
	break;

//balances.php
	case 'balances' :
	
	$MAST_HEADING = 'Balances';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'.$DOMAIN.'/site/scripts/user_home.php">Your Account</a></li><li class="bc_end">Online balance enquiry</li>';
	break;
	
//book_info.php
	case 'bookInfo' :
	
	$MAST_HEADING = $header->title;
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN . '/site/scripts/consultation_open.php">Consultations</a></li>';
	if ($consultation != -1) {
		$MAST_BREADCRUMB .= '<li><a href="'.CONSULTATIONS_PUBLIC_FOLDER.$consultation->folderName.'/index.php">'. $consultation->title.'</a></li>';
	}
	$MAST_BREADCRUMB .=	'<li class="bc_end">'.$header->title.'</li>';

	break;

//book_page.php
	case 'bookPage' :
	
	$MAST_HEADING = $header->title;
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN . '/site/scripts/consultation_open.php">Consultations</a></li>';
	if ($consultation != -1) {
		$MAST_BREADCRUMB .= '<li><a href="'.CONSULTATIONS_PUBLIC_FOLDER.$consultation->folderName.'/index.php">'. $consultation->title.'</a></li>';
	}
	$MAST_BREADCRUMB .=	'<li><a href="http://'. $DOMAIN .'/site/scripts/book_info.php?bookID='.$book->id .'">'.$header->title.'</a></li><li class="bc_end">'.$page->title.'</li>';

	break;

//change_details.php
	case 'changeDetails' :
	
	$MAST_HEADING = 'Change your details';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/user_home.php">Your account</a></li></li><li class="bc_end">Change your details</li>';	
	break;

//change_password.php
	case 'changePassword' :
	
	$MAST_HEADING = 'Change your password';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/user_home.php">Your account</a></li><li class="bc_end">Change your password</li>';	
	break;

//comment_full.php
	case 'commentFull' :
	
	$MAST_HEADING = 'View Comments';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN . '/site/scripts/consultation_open.php">Consultations</a></li><li><a href="'.CONSULTATIONS_PUBLIC_FOLDER.$consultation->folderName.'/index.php">'. $consultation->title.'</a></li><li class="bc_end">Your Comments</li>';
	break;

//comment_input.php
	case 'commentInput' :
	
	$MAST_HEADING = 'View Comments';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN . '/site/scripts/consultation_open.php">Consultations</a></li><li><a href="'.CONSULTATIONS_PUBLIC_FOLDER.$consultation->folderName.'/index.php">'. $consultation->title.'</a></li><li class="bc_end">Your Comments</li>';
	break;

//comment_viewer.php
	case 'commentView' :
	
	$MAST_HEADING = 'View Comments';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN . '/site/scripts/consultation_open.php">Consultations</a></li><li><a href="'.CONSULTATIONS_PUBLIC_FOLDER.$consultation->folderName.'/index.php">'. $consultation->title.'</a></li><li class="bc_end">Your Comments</li>';
	break;

//consultations_closed.php
	case 'consultationClosed' :
	
	$MAST_HEADING = 'Closed consultations';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Consultations</li>';
	break;

//consultation_download_info.php
	case 'consultationDownload' :
	
	$MAST_HEADING = 'Consultation Download';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN . '/site/scripts/consultation_open.php">Consultations</a></li><li><a href="http://'. $DOMAIN . '/site/scripts/consultation_open.php">Consultations</a></li><li><a href="'.CONSULTATIONS_PUBLIC_FOLDER.$consultation->folderName.'/index.php">'. $consultation->title.'</a></li><li class="bc_end">'. $download->title.'</li>';
	break;

//consultation_info.php
	case 'consultationInfo' :
	
	$MAST_HEADING = 'Consulations';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN . '/site/scripts/consultation_open.php">Consultations</a></li><li class="bc_end">'. $consultation->title.'</li>';
	break;

//consultation_notification.php
	case 'consultationNotifications' :
	
	$MAST_HEADING = $consultation->title;
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN . '/site/scripts/consultation_open.php">Consultations</a></li><li><a href="'.CONSULTATIONS_PUBLIC_FOLDER.$consultation->folderName.'/index.php">'. $consultation->title.'</a></li><li class="bc_end">Email alerts</li>';
	break;

//consultations_open.php
	case 'consultationOpen' :
	
	$MAST_HEADING = 'Consultations';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Consultations</li>';
	break;

//consultations/index.php
	case 'consultationHome' :
	
	$MAST_HEADING = $consultation->title;
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/consultation_open.php">Consultations</a></li><li class="bc_end">Consultations</li>';
	break;

//contact.php
	case 'contactPage' :
	
	$MAST_HEADING = 'Contact us';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Contact us</li>';
	break;
		
//council_democracy_index.php
	case 'councillorsIndex' :
	
	$MAST_HEADING = 'Councillors';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">Councillors</li>';
	break;

//councillors.php
	case 'councillorsView' :
	
	$MAST_HEADING = 'Councillors by '. $pageTitle;
	$MAST_BREADCRUMB = ' <li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/council_democracy_index.php" >Councillors</a></li><li class="bc_end">Find a Councillor</li>';
	break;

//councillors_info.php
	case 'councillorInfo' :
	
	$MAST_HEADING = 'Councillor '. $councillor->forename .' '. $councillor->surname;
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/council_democracy_index.php">Councillors</a></li><li class="bc_end">Councillor ' .$councillor->forename .' '. $councillor->surname .'</li>';
	break;

//documents_index.php
	case 'documentsIndex' :
	
	$MAST_HEADING = 'Council information';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Council information</li>';
    break;

//documents_info.php
	case 'documentsInfo' :
	
	$MAST_HEADING = $header->title;
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN . '/site/scripts/documents_index.php">Council information</a></li>';
	if (!empty($dirTree)) {
	    foreach ($dirTree as $parent) { 
			$MAST_BREADCRUMB .= '<li><a href="http://'. $DOMAIN .'/site/scripts/documents.php?categoryID='. $parent->id .'" >'. $parent->name .'</a></li>';
		}
    }
    $MAST_BREADCRUMB .= '<li class="bc_end">'. $header->title .'</li>';
    break;

//documents.php
	case 'documentsCat' :
	
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN . '/site/scripts/documents_index.php">Council information</a></li>';	
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li><a href="http://'. $DOMAIN . '/site/scripts/documents.php?categoryID='. $parent->id .'" >'. $parent->name .'</a></li>';
		}
		else {
            $MAST_BREADCRUMB .= '<li class="bc_end">'. $parent->name .'</li>';
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
		$MAST_HEADING = 'Document downloads';
	}
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/downloads_index.php">Document downloads</a></li>';
	foreach ($dirTree as $parent) { 
		$MAST_BREADCRUMB .= '<li><a href="http://'. $DOMAIN .'/site/scripts/downloads.php?categoryID='. $parent->id .'" >'. $parent->name .'</a></li>';
	}
	$MAST_BREADCRUMB .= '<li class="bc_end">'. $download->title .'</li>';	    
	break;

//downloads_index.php
	case 'downloadsIndex' :
	
	$MAST_HEADING = 'Document downloads';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">Document downloads</li>';	
    break;

//downloads.php
	case 'downloadCats' :
	
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/downloads_index.php">Document downloads</a></li>';	
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li><a href="http://'. $DOMAIN . '/site/scripts/downloads.php?categoryID='. $parent->id .'" >'. $parent->name .'</a></li>';
		}
		else {
            $MAST_BREADCRUMB .= '<li class="bc_end">'. $parent->name .'</li>';
			$MAST_HEADING = 'Document downloads / '.$parent->name;
		}
		$count++;
		$levelNo++;
	} 	
    break;

//email_friend.php
	case 'emailFriend' :
	
	$MAST_HEADING = 'Email a friend';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">Email a friend</li>';	
    break;

//enforcement.php
	case 'enforcement' :
	
	$MAST_HEADING = 'Enforcement notice search';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">Enforcement Notice search</li>';	
    break;

//enforcement_list.php
	case 'enforcementList' :
	
	$MAST_HEADING = 'Enforcement notices';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">Enforcement Notices</li>';	
    break;

//enforcement_details.php
	case 'enforcementDetails' :
	
	$MAST_HEADING = $notice->getFormattedValueForField('noticeRef');
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/enforcement.php">Enforcement Notice search</a></li><li class="bc_end">Enforcement Notice details</li>';	
    break;

//events_index.php
	case 'eventsIndex' :
	
	$MAST_HEADING = 'Featured event';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">Events</li>';
    break;

//event_categories.php
	case 'eventsCatIndex' :
	
	$MAST_HEADING = 'Events';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">Events</li>';
    break;

//events.php
	case 'eventsCats' :
	
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'.$DOMAIN .'/site/scripts/events_index.php">Events</a></li>';
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li><a href="http://'. $DOMAIN . '/site/scripts/events.php?categoryID='. $parent->id .'" >'. $parent->name .'</a></li>';
		}
		else {
            $MAST_BREADCRUMB .= '<li class="bc_end">'. $parent->name .' </li>';
			$MAST_HEADING = $parent->name . ' events ';
		}
		$count++;
		$levelNo++;
	} 	
    break;

//events_info.php
	case 'eventsInfo' :
	
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/events_index.php">Events</a></li>';
	if (isset($location)){
		$MAST_BREADCRUMB .= '<li class="bc_end">' . $location .'</li>';
		$MAST_HEADING = 'Events Listings: '. $location;
	}
	else if(isset($_GET['period']) || $_POST['period'] == "full"){
		$MAST_BREADCRUMB .= '<li class="bc_end">All Events</li>';
		$MAST_HEADING = 'Events Listings: All Events';
	}
	elseif($startDate == $endDate){
		list ($day, $month, $year) = split ('[/.-]', $startDate);
		$date = date("jS M y", mktime(0, 0, 0, $month, $day, $year));
		$MAST_BREADCRUMB .= '<li class="bc_end">'.$date.'</li>';
		$MAST_HEADING = 'Events Listings: '. $date;
	}
	else if (isset($_GET['eventID'])) {
		$MAST_BREADCRUMB .= '<li class="bc_end">'.$event->title.'</li>';
		$MAST_HEADING = 'Events Listings: '. $event->title;
	}
	else {
		list ($day, $month, $year) = split ('[/.-]', $startDate);
		$sdate = date("jS M", mktime(0, 0, 0, $month, $day, $year));
		list ($day, $month, $year) = split ('[/.-]', $endDate);
		$edate = date("jS M y", mktime(0, 0, 0, $month, $day, $year));
		$MAST_BREADCRUMB .= '<li class="bc_end">Events Listings</li>';
		$MAST_HEADING = 'Events Listings';
	}
	break;
	
//faq_info.php
	case 'faqInfo' :
	
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/faqs_index.php">Frequently asked questions</a></li>';
	$MAST_HEADING = 'Frequently asked questions';
	break;

//faqs_ask.php
	case 'faqAsk' :
	
	$MAST_HEADING = 'Ask a us question';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a  href="http://'. $DOMAIN .'/site/scripts/faqs_index.php">Frequently asked questions</a></li><li class="bc_end">Ask us a question</li>';
    break;
    
//faqs_index.php
	case 'faqsIndex' :
	
	$MAST_HEADING = 'Frequently asked questions';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Frequently asked questions</li>';	
    break;

//faqs.php
	case 'faqsCats' :
	
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/faqs_index.php">Frequently asked questions</a></li>';	
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li><a href="http://'. $DOMAIN . '/site/scripts/faqs.php?categoryID='. $parent->id .'" >'. $parent->name .'</a></li>';
		}
		else {
            $MAST_BREADCRUMB .= '<li class="bc_end">'. $parent->name .'</li>';
			$MAST_HEADING = 'Frequently asked questions: ' .$parent->name;
		}
		$count++;
		$levelNo++;
	} 	
    break;

//feedback.php
	case 'feedback' :
	
	$MAST_HEADING = 'Your Feedback';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a  href="http://'. $DOMAIN .'/site/scripts/contact.php" >Contact Us</a></li><li class="bc_end">Feedback</li>';	
	break;

//for_sale_index.php
	case 'forSaleIndex' :
	
	$MAST_HEADING = 'Intranet sales board';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">Intranet sales board</li>';	
	break;

//for_sale_item_admin.php
	case 'forSaleAdmin' :
	
	$MAST_HEADING = 'Add or Edit an Item';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/for_sale_index.php">Intranet sales board</a></li><li class="bc_end">Add or Edit an Item</li>';	
	break;

//for_sale_item_details.php
	case 'forSaleItem' :
	
	$MAST_HEADING = $item->title;
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/for_sale_index.php">Intranet sales board</a></li><li class="bc_end">'.$item->title.'</li>';
	break;

//forgot_password.php
	case 'forgotPassword' :
	
	$MAST_HEADING = 'Password Reminder';
	$MAST_BREADCRUMB = '<li class="bc_end"><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li>Password Reminder</li>';	
	break;

//forms.php
	case 'formCats' :
	
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end"><a href="http://'. $DOMAIN .'/site/scripts/xforms_index.php" >Online forms</a></li>';	
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li class="bc_end"><a href="http://'. $DOMAIN . '/site/scripts/forms.php?categoryID='. $parent->id .'" >'. $parent->name .'</a></li>';
		}
		else {
            $MAST_BREADCRUMB .= '<li>'. $parent->name .'</li>';
			$MAST_HEADING = 'Online forms: '. $parent->name ;
		}
		$count++;
		$levelNo++;
	} 
	break;

//google_advanced.php
	case 'googleAdvanced' :
	
	$MAST_HEADING = 'Advanced search';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">Advanced search</li>';
	break;

//google_results.php
	case 'googleResults' :
	
	$MAST_HEADING = 'Search results';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">Search results</li>';
	break;

//contact details
	case 'index.php' :
	$indexPage = true;
	$MAST_HEADING = METADATA_GENERIC_COUNCIL_NAME;
	$MAST_BREADCRUMB = '<li class="bc_end">Home</li>';
	break;

//home_info.php
	case 'homeInfo' :
	
	$MAST_HEADING = $homepage->title;
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">'. $homepage->title .'</li>';	
	break;

//links.php
	case 'links' :
	
	$MAST_HEADING = 'External links and resources';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">External links and resources</li>';	
	break;

//location.php
	case 'location' :
	
	$MAST_HEADING = 'Council location';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/contact.php" >Contact us</a></li><li class="bc_end">Council location</li>';	
	break;
	
//meetings_committees.php
	case 'meetingsCommittees' :
	
	$MAST_HEADING = 'Agendas, reports and minutes';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a  href="http://'. $DOMAIN .'/site/scripts/meetings_index.php" >Agendas, reports and minutes</a></li><li class="bc_end">'. $header->title .'</li>';
	break;

//meetings_index.php
	case 'meetingsIndex' :
	
	$MAST_HEADING = 'Agendas, reports and minutes';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Agendas, reports and minutes</li>';	
	break;
	
//meetings_info.php
	case 'meetingsInfo' :
	
	$MAST_HEADING = 'Agendas, reports and minutes';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/meetings_index.php" >Agendas, reports and minutes</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/meetings_committees.php?headerID='. $header->id .'" >'. $header->title .'</a></li><li class="bc_end">'. $meeting->getMeetingMinutesDateFormatted("l jS F Y") .'</li>';
	break;

//meetings.php
	case 'meetingsCats' :
	
	$MAST_HEADING = 'Agendas, reports and minutes';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end"><a href="http://'. $DOMAIN .'/site/scripts/meetings_index.php" >Agendas, reports and minutes</a></li>';	
	$levelNo = 1;
	$count = 0;
	foreach ($dirTree as $parent) {
		if ($count < sizeof($dirTree) - 1) {
			$MAST_BREADCRUMB .= '<li class="bc_end"><a href="http://'. $DOMAIN . '/site/scripts/meetings.php?categoryID='. $parent->id .'" >'. $parent->name .'</a></li>';
		}
		else {
            $MAST_BREADCRUMB .= '<li class="bc_end">'. $parent->name .'</li>';
		}
		$count++;
		$levelNo++;
	} 
	break;

//my_area_lookup.php
	case 'myArea' :
	
	$MAST_HEADING = 'In my area...';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">In my area</li>';
	break;

//news_archive.php
	case 'newsArchive' :
	
	$MAST_HEADING = 'News archive';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/news_index.php" >Latest news</a></li><li class="bc_end">News archive</li>';	
	break;
	
//news_article.php
	case 'newsArticle' :
	
	$MAST_HEADING = stripslashes($news->title);
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/news_index.php" >Latest news</a></li><li class="bc_end">'. stripslashes($news->title) .'</li>';	
	break;

//news_category.php
	case 'newsCats' :
	
	$MAST_HEADING = $categoryViewing->name .' news';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/news_index.php" >Latest news</a></li><li class="bc_end">'. $categoryViewing->name .' news</li>';
	break;

//news_index.php
	case 'newsIndex' :
	
	$MAST_HEADING = 'Latest news';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">Latest news</li>';
	break;

//pageComments.php
	case 'comments' :
	
	$MAST_HEADING = 'Send us your comments';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Send us your comments</li>';
	break;

//payments.php
	case 'payments' :
	
	$MAST_HEADING = stripslashes($homepage->title);
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Online Payments</li>';
	break;

//payments_provider.php
	case 'paymentsProvider' :
	
	$MAST_HEADING = 'Services Basket';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Online Payments</li>';
	break;

//personnel_add.php
	case 'personnelAdd' :
	
	$MAST_HEADING =  'Addition to the people directory';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/personnel_index.php">People Directory</a></li><li class="bc_end">Addition to the people directory</li>';
	break;

//personnel_index.php
	case 'personnelIndex' :
	
	$MAST_HEADING = 'People Directory';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">People Directory</li>';
	break;

//personnel.php
	case 'personnel' :
	
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end"><a href="http://'. $DOMAIN .'/site/scripts/personnel_index.php">People Directory</a></li>';
	if($_GET['viewBy'] == 'name') {
		$MAST_BREADCRUMB .= '<li class="bc_end">View by name</li>';
		$MAST_HEADING = 'People Directory: View by name';
	}
	else {
		$MAST_BREADCRUMB .= '<li class="bc_end">View by department</li>';	
		$MAST_HEADING = 'People Directory: View by department';
	}
	break;


//personnel_info.php
	case 'personnelInfo' :
	
	$MAST_HEADING =  $person->forename.' '.$person->surname ;
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/personnel_index.php">People Directory</a></li><li class="bc_end">'. $person->forename.' '.$person->surname .'</li>';
	break;
	
//pid
	case 'pidscript' :
	
	$MAST_HEADING = 'Council services';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/az_home.php">Council services</a></li><li class="bc_end">'. $service->title .'</li>';
	break;

//planning.php
	case 'planning' :
	
	$MAST_HEADING =  'Planning Application Search';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Planning application search</li>';
	break;

//planning_details.php
	case 'planningDetails' :
	
	$MAST_HEADING =  'Planning Applications';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/planning.php">Planning application search</a></li><li class="bc_end">Planning applications</li>';
	break;
	
//planx_advsearch.php
	case 'planxAdvSearch' :
	
	$MAST_HEADING =  'Planning application advanced search';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Planning application advanced search</li>';
	break;

//planx_comment.php
	case 'planxComment' :
	
	$MAST_HEADING =  'Comment on an application';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Comment on an application</li>';
	break;
	
//planx_details.php
	case 'planxDetails' :
	
	$MAST_HEADING =  $app->getFormattedValueForField('applicationNumber');
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">'.$app->getFormattedValueForField('applicationNumber').'</li>';
	break;

//planx_lpindex.php
	case 'planxLpIndex' :
	
    if (isset($_GET['planID'])) {
    	$MAST_HEADING = $plan->title;
	}
	else {
		$MAST_HEADING = 'Planning Policy: Available Online Plans';
	}
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Planning Policy: Available Online Plans</li>';
	break;

//planx_lpmaps.php
	case 'planxLpMap' :
	
    $MAST_HEADING = $map->title;
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">'.$map->title.'</li>';
	break;

//planx_lppolicy.php
	case 'planxLpPolicy' :
	
    $MAST_HEADING = $policy->title;
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">'.$policy->title.'</li>';
	break;

//planx_lpsearch_results.php
	case 'planxLpSearchResults' :
	
	$MAST_HEADING =  'Planning policy search';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Planning policy search</li>';
	break;

//planx_lpsearch.php
	case 'planxLpSearch' :
	
	$MAST_HEADING =  'Planning policy search';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Planning policy search</li>';
	break;

//planx_results.php
	case 'planxResults' :
	
	$MAST_HEADING =  'Planning application search';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Planning application search</li>';
	break;

//planx_search.php
	case 'planxSearch' :
	
	$MAST_HEADING =  'Planning application search';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Planning application search</li>';
	break;
	
//planx_search.php
	case 'planxSearchResult' :
	
	$MAST_HEADING =  'Planning application search results';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Planning application search results</li>';
	break;
	
//planx_track.php
	case 'planxTrack' :
	
	$MAST_HEADING =  'Track planning applications';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Track planning applications</li>';
	break;

//planx_track.php
	case 'planxWeekly' :
	
	$MAST_HEADING =  'Application weekly list';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Application weekly list</li>';
	break;

//poll_past_results.php
	case 'pollList' :
	
	$MAST_HEADING = 'Past polls';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/poll_results.php">Poll results</a></li><li class="bc_end">Past polls</li>';	
	break;

//poll_results.php
	case 'pollResults' :
	
	$MAST_HEADING = 'Poll results';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">Poll results</li>';	
	break;

//press_archive.php
	case 'PressReleasesArchive' :
	
	$MAST_HEADING = 'Press release archive';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/press_index.php">Press releases</a></li><li class="bc_end">Press release archive</li>';	
	break;
	
//press_article.php
	case 'PressReleasesArticle' :
	
	$MAST_HEADING = $PressReleases->title;
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/press_index.php">Press releases</a></li><li class="bc_end">'. $PressReleases->title .'</li>';	
	break;

//press_category.php
	case 'PressReleasesCats' :
	
	$MAST_HEADING = $categoryViewing->name .' press releases';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/press_index.php">Press releases</a></li><li class="bc_end">'. $categoryViewing->name .' press releases</li>';
	break;

//press_index.php
	case 'PressReleasesIndex' :
	
	$MAST_HEADING = 'Press releases';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">Press releases</li>';
	break;

//recruit_details.php
	case 'recruitDetails' :
	
	$MAST_HEADING = 'Vacancy details';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'.$DOMAIN.'/site/scripts/recruit_jobs.php">Current vacancies</a></li><li class="bc_end">Vacancy details for '.$job->title.'</li>';	
	break;

//recruit_jobs.php
	case 'recruitJobs' :
	
	$MAST_HEADING = 'Current vacancies';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Current vacancies</li>';	
	break;

//register_accept.php
	case 'registerAccept' :
	
	$MAST_HEADING = 'Thank you for your registration';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/user_home.php">Your Account</a></li><li class="bc_end">Registration completed</li>';	
	break;

//register_authorisation.php
	case 'registerAuthorisation' :
	
	$MAST_HEADING = 'Registration confirmation';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Registration confirmation</li>';
	break;

//register.php
	case 'register' :
	
	$MAST_HEADING = 'Create an account';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Create an account</li>';
	break;

//room_search.php
	case 'roomSearch' :
	
	$MAST_HEADING = 'Room search';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li class="bc_end">Room search</li>';	
	break;

//room_availability.php
	case 'roomAvailability' :
	
	$MAST_HEADING = 'Room availability';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/room_search.php">Room search</a></li><li class="bc_end">Room availability</li>';	
	break;

//room_booking.php
	case 'roomBooking' :
	
	$MAST_HEADING = 'Room booking';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/room_search.php">Room search</a></li><li class="bc_end">Room booking</li>';	
	break;

//rss_about.php 
	case 'rssAbout' :
	
	$MAST_HEADING = 'RSS feed';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/news_index.php">News</a></li><li class="bc_end">RSS news feed</li>';	
	break;

//search_index.php
	case 'jaduSearch' :
	
	$MAST_HEADING = 'Advanced search';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Advanced search</li>';
	break;

//search_results.php
	case 'jaduSearchResults' :
	
	$MAST_HEADING = 'Search results';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/search_index.php">Advanced search</a></li><li class="bc_end">Search results</li>';	
	break;

//services_info.php
	case 'servicesInfo' :
	
	$MAST_HEADING = 'Council services';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/az_home.php">Council services</a></li><li class="bc_end">'. $service->title .'</li>';
	break;

//signin.php
	case 'signIn' :
	
	$MAST_HEADING = 'Sign in to your account';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Sign in to your account</li>';;
	break;


//services_crawl.php
	case 'servicesCrawl' :
	
	$MAST_HEADING = 'Council services';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Council services</li>';
	break;

//site_map.php
	case 'sitemap' :
	
	$MAST_HEADING = 'Site map';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Site map</li>';
	break;

//### terms.php
	case 'terms' :
	$MAST_HEADING = 'Terms and disclaimer';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Terms and disclaimer</li>';	
	break;

//thanks.php
	case 'thanks' :
	
	$MAST_HEADING = 'Thank you  for your feedback';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/contact.php">Contact us</a></li><li class="bc_end">Thank you  for your feedback</li>';	
	break;

//unsubscribe.php
	case 'unsubscribe' :
	
	$MAST_HEADING = 'Unsubscribe';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Unsubscribe</li>';	
	break;

//user_form_archive.php
	case 'userFormArchive' :
	
	$MAST_HEADING = 'Online form archive';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/user_home.php">Your account</a></li><li class="bc_end">Online form archive</li>';	
	break;

//user_form_info.php
	case 'userFormInfo' :
	
	$MAST_HEADING = 'Form archive';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/user_home.php">Your account</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/user_form_archive.php" >Form archive</a></li><li class="bc_end">'. $form->title .'</li>';	
	break;

//user_job_archive.php
	case 'userJobArchive' :
	
	$MAST_HEADING = 'Job application archive';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/user_home.php">Your account</a></li><li class="bc_end">Job application archive</li>';	
	break;

//user_home.php
	case 'userHome' :
	
	$MAST_HEADING = 'Your account';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Your account</li>';	
	break;

//user_settings.php
	case 'userSettings' :
	
	$MAST_HEADING = 'Settings for accessibility';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Settings for accessibility</li>';	
	break;

//view_feeds.php
	case 'viewFeeds' :
	
	if ($viewMode) {	
		$MAST_HEADING = $RSSItem->name;
	} 
	else {
		$MAST_HEADING = 'RSS feeds';	
	}
	
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'.$DOMAIN.'/site/scripts/news_index.php">Latest news</a></li>';
	if ($viewMode) {
		$MAST_BREADCRUMB .=  '<li><a href="http://'.$DOMAIN.'/site/scripts/view_feeds.php">RSS feeds</a></li><li class="bc_end">'. $RSSItem->name .'</li>';
	} 
	else {
		$MAST_BREADCRUMB .= '<li class="bc_end">RSS feeds</li>';
	}	
	break;

//website_statistics_detail.php
	case 'webStatDetails' :
	
	$MAST_HEADING = 'Website statistics '. sprintf("%s %s", date("F", mktime(0, 0, 0, $month, 1, $year)), $year);
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/" >Home</a></li><li><a href="http://'.$DOMAIN.'/site/scripts/website_statistics.php">Website statistics</a></li><li class="bc_end">Website statistics '. sprintf("%s %s", date("F", mktime(0, 0, 0, $month, 1, $year)), $year) .'</li>';
	break;
	
//website_statistics.php
	case 'webStat' :
	
	$MAST_HEADING = 'Website statistics, '. $year;
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Website statistics, '.$year .'</li>';	
	break;

//whats_new_index.php
	case 'whatsNew' :
	
	$MAST_HEADING = "What&#39;s new on site";
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">What&#39;s new on site</li>';	
	break;
	
//whos my councillor post code search
	case 'whosCouncillor' :
	
	$MAST_HEADING = "Find a councillor by postcode";
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'.$DOMAIN.'/site/scripts/council_democracy_index.php">Councillors</a></li><li class="bc_end">Find a councillor by postcode</li>';	
	break;
	
//xforms form
	case 'xformsForm' :
	
	$MAST_HEADING = 'Online form, '. $form->title;
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li><a href="http://'. $DOMAIN .'/site/scripts/xforms_index.php">Online forms</a></li><li class="bc_end">'. $form->title .'</li>';	
	break;

//xforms_index.php
	case 'xformsIndex' :
	
	$MAST_HEADING = 'Online forms';
	$MAST_BREADCRUMB = '<li><a href="http://'. $DOMAIN .'/site/index.php">Home</a></li><li class="bc_end">Online forms</li>';		
	break;

	default:
	$indexPage = true;
	$MAST_HEADING = METADATA_GENERIC_COUNCIL_NAME;
	$MAST_BREADCRUMB = '<li class="bc_end">Home</li>';
	break;
	
	}
?>