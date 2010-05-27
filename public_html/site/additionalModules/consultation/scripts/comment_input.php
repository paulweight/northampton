<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduRegisterPreferences.php");
	include_once("marketing/JaduTargettingRules.php");
	include_once("recruitment/JaduRecruitmentCategories.php");
	include_once("eConsultation/JaduComments.php");
	include_once("eConsultation/JaduCommentFiles.php");
	include_once("JaduUpload.php");
	include_once("JaduConstants.php");
	include_once("eConsultation/JaduConsultations.php");	
	
	if (isset($_GET['consultationID']) &&  is_numeric($_GET['consultationID']) && $_GET['consultationID'] > 0) {
		$consultation = getConsultation($_GET['consultationID']);
		if ($consultation != -1 && $consultation->allowComments) {
			$_POST['topic'] = $consultation->title;
		}
		else {
			header("Location: http://$DOMAIN/site/scripts/consultation_open.php");
			exit;
		}
	}
	else if (!isset($_POST['submit']) && !isset($_POST['addFile']) && !isset($_GET['remove'])) {
		header("Location: http://$DOMAIN/site/scripts/consultation_open.php");
		exit;
	}

	// get the logged in user
	if (!isset($_SESSION['userID'])) {
		header("Location: ".CONSULTATIONS_PUBLIC_FOLDER.$consultation->folderName."/index.php?loginRequired=true");
		exit;
	}
	else {
		$user = getUser($_SESSION['userID']);
	}
		
	if (isset($_GET['commentID']) && is_numeric($_GET['commentID']) && isset($_GET['remove'])) {
	    deleteCommentFile($_GET['remove']);
	    $_POST['commentID'] = $_GET['commentID'];
	    $_POST['topic'] = $_GET['topic'];
	}	
	
	// if this is a new request then create a comment for the user
	if (!isset($_POST['commentID'])) {
	    $_POST['commentID'] = createNewComment($user->id);
	}
	else {
		// otherwise get the comment details
	    $comment = getComment($_POST['commentID']);
	}
	
	// submit the comment
	if (isset($_POST['submit'])){
	    $comment = getComment($_POST['commentID']);
	    $comment->state = PENDING;
	    $comment->comments = $_POST['comments'];
	    $comment->anonymous = $_POST['anonymous'];
	    $comment->allowPublish = $_POST['allowPublish'];
	    $comment->topic = $_POST['topic'];

	    $comment->objectID = $consultationID;
	    $comment->commentTable = CONSULTATIONS_TABLE;
	    
	    updateComment($comment);
	    
	    if (!isUserOverQuota($_SESSION['userID']) &&
	        !isIpAddressOverQuota($_SERVER['REMOTE_ADDR']) &&
	        !isOverallDailyUploadLimitExceeded()) {
		
            if ($commentFile_name != "") {
                
                //	Strip some illegal characters
                $cleanFilename = str_replace(" ", "_", $commentFile_name);
                $cleanFilename = str_replace(",", "_", $cleanFilename);
                $cleanFilename = str_replace("/", "-", $cleanFilename);
                $cleanFilename = str_replace("\\", "-", $cleanFilename);
                $cleanFilename = str_replace("'", "", $cleanFilename);
                $cleanFilename = str_replace("\"", "", $cleanFilename);
    
                $destination = $HOME."comment_files/$cleanFilename";
                $result = uploadFile($commentFile, $destination);
                
                $cFile = new CommentFile();
    
                $cFile->commentID = $_POST['commentID'];
                $cFile->filename = $cleanFilename;
                
                addCommentFile($cFile);
            }
        }

	    header("Location: comment_viewer.php?consultationID=$consultationID&commentsReceived=true");
	    exit;
	}	
	
	// add file has been clicked - add to the list of files for this comment
	if (isset($_POST['addFile'])){
	    if (!isUserOverQuota($_SESSION['userID']) &&
	        !isIpAddressOverQuota($_SERVER['REMOTE_ADDR']) &&
	        !isOverallDailyUploadLimitExceeded()) {
	        
            if ($commentFile_name != "") {
                //	Strip some illegal characters
                $cleanFilename = str_replace(" ", "_", $commentFile_name);
                $cleanFilename = str_replace(",", "_", $cleanFilename);
                $cleanFilename = str_replace("/", "-", $cleanFilename);
                $cleanFilename = str_replace("\\", "-", $cleanFilename);
                $cleanFilename = str_replace("'", "", $cleanFilename);
                $cleanFilename = str_replace("\"", "", $cleanFilename);
    
                $destination = $HOME."comment_files/$cleanFilename";
                $result = uploadFile($commentFile, $destination);
                
                $cFile = new CommentFile();
    
                $cFile->commentID = $_POST['commentID'];
                $cFile->filename = $cleanFilename;
                
                addCommentFile($cFile);
            }
        }
	}
	
	// get any files that may be attached to this comment
	$files = getFilesForComment($_POST['commentID']);
	
	$breadcrumb = 'commentInput';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Make a comment about <?php print $consultation->title; ?></title>
	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="Consultation, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Make a comment about <?php print $consultation->title; ?>" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Make a comment about <?php print $consultation->title; ?>" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Make a comment about <?php print $consultation->title; ?>" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ####################################### -->

<?php
    if (isUserOverQuota($_SESSION['userID']) || isIpAddressOverQuota($_SERVER['REMOTE_ADDR']) || isOverallDailyUploadLimitExceeded()) {		
?>
			<h2 class="warning">Due to security restrictions we cannot accept any further uploaded files.  Please submit your comments using the text area provided.</h2>
<?php
    }
?>
		<p class="first"><a href="http://<?php print $DOMAIN;?>/site/scripts/comment_viewer.php?consultationID=<?php print $consultation->id;?>">Read others comments</a></p>

		<p>Data Protection Act 1998. Your contact information is being collected to compile mailing lists for future information bulletins on this consultation.</p>
		<p>If you wish to remain anonymous, then simply leave the personal details fields blank, or you may wish only to enter one element of information e.g. your e-mail address.</p>
		<p>You can submit your comments in two ways, type or paste your comments into the text box below or upload a file containing your comments.</p>
				
		<form method="post" action="http://<?php print $DOMAIN;?>/site/scripts/comment_input.php" enctype="multipart/form-data" class="basic_form">
			<input type="hidden" name="consultationID" value="<?php print $consultationID; ?>" />
			<input type="hidden" name="commentID" value="<?php print $_POST['commentID']; ?>" />
			<input type="hidden" name="topic" value="<?php print $_POST['topic']; ?>" />
	
			<p>
				<label for="allowPublish">Would you like your comments to be published?</label>
				<select id="allowPublish" name="allowPublish">
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
				<span class="clear"></span>
	    	</p>
	    	<p>
	    		<label for="anonymous">Would you like your comments to be published anonymously?</label>
				<select id="anonymous" name="anonymous">
					<option value="0">No</option>
					<option value="1">Yes</option>
				</select>
				<span class="clear"></span>
			</p>	
			<p>
				<label for="comments">Your comments</label>
				<textarea id="comments" name="comments" rows="10" cols="52" class="field"></textarea>
				<span class="clear"></span>
			</p>
			<p>
				<label for="commentFile">Upload file</label>
		    	<input type="file" id="commentFile" name="commentFile" class="field" />
			    <input type="submit" name="addFile" value="Add File" class="button" />
		    </p>

<?php
			if (sizeof($files) > 0){
				print '<p>';
				foreach ($files as $file) {
?>
		            <?php print $file->filename; ?> | <a href="http://<?php print $DOMAIN;?>/site/scripts/comment_input.php?commentID=<?php print $_POST['commentID']; ?>&remove=<?php print $file->id; ?>&topic=<?php print urlencode($_POST['topic']); ?>">Remove</a><br />
<?php
				}
				print '</p>';
			}
?>	    
				<p class="center">
					<input type="submit" name="submit" value="Submit Comments" class="button" />
				</p>
			</form>
			<p class="note">Please note: all comments are moderated and will not appear on the site immediately</p>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ####################################### -->
<?php include("../includes/closing.php"); ?>