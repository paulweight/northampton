<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("planXLive/JaduPlanXComments.php");
	include_once("planXLive/JaduPlanXApplications.php");

	$app = getPlanningApplication($_GET['appID']);

	// used when making sure all mandatory fields have been completed
	$errors = false;
	
	// set to true when comments have been successfully submitted
	$complete = false;

	if (isset($_POST['submit'])) {
	   $comment = new Comment();
	   $comment->applicationID = $_GET['appID'];
       $comment->comments = $_POST['comments'];
       $comment->salutation = $_POST['salutation'];
       $comment->forename = $_POST['forename'];
       $comment->surname = $_POST['surname'];
       $comment->email = $_POST['email'];
       $comment->postcode = $_POST['postcode'];
       $comment->address = $_POST['address'];
       $comment->archived = 0;

       if (empty($_POST['forename']) || empty($_POST['surname']) || empty($_POST['address']) ||
           empty($_POST['postcode']) || empty($_POST['email']) || empty($_POST['comments'])) {
           $errors = true;
       }
       else {
           $comment->address = $_POST['address'] . "\n" . $_POST['postcode'];
           addComment($comment);
           $complete = true;
       }
	}
	elseif (isset($_SESSION['userID'])) {
	   $user = getUser($_SESSION['userID']);

	   $comment->salutation = $user->salutation;
       $comment->forename = $user->forename;
       $comment->surname = $user->surname;
       $comment->email = $user->email;
       $comment->postcode = $user->postcode;
       $comment->address = $user->address . "\n" . $user->city .  "\n" . $user->county;
	}
	
	$breadcrumb = 'planxComment';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php print METADATA_GENERIC_COUNCIL_NAME;?> - Comment</title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="<?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of documents and pages organised within the following categories, Environment, Planning" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> online information | Environment | Planning" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?>s index of documents and pages organised within the following categories, Environment, Planning" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
				

<?php
		if ($complete) {
?>
			<p class="first">Thank you, your comments have been submitted</p>
<?php
		} 
		else {
?>	
			<p class="first">Comment on application number: <?php print $app->getFormattedValueForField('applicationNumber'); ?>.</h3>
<?php
		if ($errors) {
?>
			<h2 class="warning">Please make sure all fields are completed</h2>
<?php
		}
?>
	<form id="plancomments" action="http://<?php print $DOMAIN; ?>/site/scripts/planx_comment.php?appID=<?php print $_GET['appID']; ?>" method="post" class="basic_form">
		<p>
			<label for="salutation">Salutation</label>
			<select name="salutation" id="salutation">
				<option value="Mr" <?php if ($comment->salutation == 'Mr') print 'selected'; ?>>Mr</option>
				<option value="Miss" <?php if ($comment->salutation == 'Miss') print 'selected'; ?>>Miss</option>
				<option value="Mrs" <?php if ($comment->salutation == 'Mrs') print 'selected'; ?>>Mrs</option>
				<option value="Ms" <?php if ($comment->salutation == 'Ms') print 'selected'; ?>>Ms</option>
				<option value="Dr" <?php if ($comment->salutation == 'Dr') print 'selected'; ?>>Dr</option>
			</select>
		</p>
		<p>
			<label for="forename">Forename (required)</label>
			<input id="forename" type="text" name="forename" value="<?php print $comment->forename; ?>" class="field" />
		</p>
		<p>
			<label for="surname">Surname (required)</label>
			<input id="surname" type="text" name="surname" value="<?php print $comment->surname; ?>" class="field" />
		</p>
		<p>
			<label for="address">Address (required)</label>
			<textarea id="address" name="address" cols="2" rows="3" class="field"><?php print $comment->address; ?></textarea>
		</p>
		<p>
			<label for="postcode">Postcode (required)</label>
			<input id="postcode" name="postcode" value="<?php print $comment->postcode; ?>" type="text" class="field" />
		</p>
		<p>
			<label for="email">Email Address (required)</label>
			<input id="email" name="email" value="<?php print $comment->email; ?>" type="text" class="field" />
		</p>
		<p>
			<label for="comments">Your Comments (required)</label>
			<textarea id="comments" name="comments" cols="2" rows="5" class="field"><?php print $comment->comments; ?></textarea>
		</p>
		<p class="center">							
			<input type="submit" value="Submit Comment" name="submit" id="planbutton" class="button" />
		</p>
	</form>
<?php
		}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>