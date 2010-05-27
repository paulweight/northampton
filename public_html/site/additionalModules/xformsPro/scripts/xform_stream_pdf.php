<?php
	session_start();

	if (isset($_SESSION['userID']) && isset($_GET['pdfFilename'])) {
		
		include_once("xforms2/JaduXFormsPDFForms.php");
		
		// stream a file from the directory in which user form atachments are stored.
		
//		if ($userPDFFormExists === XFORMS_PDF_FORM_EXCEPTION_SUCCESSFUL) {
			
			$pdfFilename = XFORMS_PDF_FORMS_DIRECTORY . $pdfFilename;

			$fp = fopen($pdfFilename, "rb");
			$data = fread($fp, filesize($pdfFilename));
			fclose($fp);
		
			$att = " attachment;";
			if (strstr($_SERVER["HTTP_USER_AGENT"],"MSIE 5.5")) {
				$att = "";
			}
			
			header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
			header('Content-Length: '.filesize($pdfFilename)); 
			header('Content-Type: application/force-download');
			header('Content-Disposition:'.$att.' filename="'.basename($pdfFilename).'"');
			header('Content-Transfer-Encoding: binary');
			
			print $data;
//		}
	}
	
	exit();
?>