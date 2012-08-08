<?php
header( 'Location: /councilmeetings' ) ;
exit;
?>

<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
	include_once("egov/JaduEGovMeetingMinutes.php");
	include_once("egov/JaduCL.php");
	include_once("../includes/lib.php");

	$mostRecent = getLastXMeetingMinutes(10, true, true);

	$allHeaders = getAllMeetingMinutesHeaders(false);
	
	// Breadcrumb, H1 and Title
	$MAST_HEADING = 'Agendas, Reports and Minutes';
	$MAST_BREADCRUMB = '<li><a href="' . getSiteRootURL() .'" rel="home">Home</a></li><li><span>Agendas, Reports and Minutes</span></li>';
	
	include("meetings_index.html.php");
?>