<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	include_once("marketing/JaduUsers.php");
	include_once("ePayments/JaduEpaymentsOrders.php");
	include_once("xforms2/JaduXFormsForm.php");
	include_once("xforms2/JaduXFormsUserForms.php");
	include_once("xforms2/JaduXFormsUserQuestionAnswers.php");
	
	if (isset($_SESSION['userID'])) {
		
		$user = getUser($_SESSION['userID']);
		
		if (isset($_GET['userFormID'])) {
			$userForm = getXFormsUserForm($_GET['userFormID']);
			
			if ($userForm->id > 0) {
				if ($userForm->userID != $_SESSION['userID']) {
					header ("Location: $ERROR_REDIRECT_PAGE");
					exit;
				}
				
				$allAnswers = getAllXFormsQuestionAnswersForForm($userForm->id);
				$form = getXFormsForm($userForm->formID, true);
				
				if (isset($_GET['remove']) && $_GET['remove'] == "true") {
					deleteXFormsUserForm($userForm->id);
				}
			}
		}
		
		//	xforms
		$allSubmittedUserForms = getAllXFormsUserFormsForUser($_SESSION['userID'], true);
		$allUnsubmittedUserForms = getAllXFormsUserFormsForUser($_SESSION['userID'], false);
		
		//	ePayments
		$incompleteOrders = getUsersOrdersOfState($_SESSION['userID'], ORDER_STATUS_INCOMPLETE);
		$pendingOrders = getUsersOrdersOfState($_SESSION['userID'], ORDER_STATUS_PENDING);
		$completedOrders = getUsersOrdersOfState($_SESSION['userID'], ORDER_STATUS_COMPLETE);
		
		$loginString = getLastLoginAsString($_SESSION['userID']);
	}
	else {
		header ("Location: $ERROR_REDIRECT_PAGE");
		exit();
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Your account - <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>
	<?php include("../includes/meta.php"); ?>

	<!-- general metadata -->
	<meta name="Keywords" content="account, regstration, user, profile, personal, details, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> User personal details" />

	<!-- Dublin Core Metadata -->
	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Personal details" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> User personal details" />
	
	<!-- IPSV / LGNL Metadata -->
	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council and democracy" />
</head>
<body>
<?php include("../includes/opening.php"); ?>
<!-- #################################### -->
<!-- ########### MAIN CONTENT ########### -->

		<h1>Your account</h1>

		<p>Hello,<strong>	
<?php 
		if ($user->salutation != '' && $user->surname != '') {
			print $user->salutation .  ' ';
		}
		if ($user->forename != '') {
			print $user->forename . ' ';
		}
		if  ($user->surname != '') {
			print $user->surname;
		}
		if ($user->forename == '' && $user->surname == '') {
			print $user->email;
		}
?>
		</strong>- <?php print $loginString;?>.</p>
		
		<p><strong> Be safe online: </strong>We will never ask you to tell us your account details, including your password details. Please do not disclose them to anyone.</p>

		<ul class="ul">
			<li><a href="./change_details.php">Change your details</a> <?php if (isset($detailsChanged)) { ?> - <em>Your details have been updated</em><? } ?></li>
			<li>If you feel that your password is no longer secure, or your account has been compromised then we advise you <a href="./change_password.php">change your password</a> as soon as possible.</li>
		</ul>

		
		<!-- Online Forms -->		
		<h2>Your online forms</h2>
		
<?php 
	if (sizeof($allSubmittedUserForms) > 0) {
?>
		
		<ul class="ul">
			<li><a href="./user_form_archive.php"><?php print sizeof($allSubmittedUserForms) . ' forms submitted online';?></a></li>
		</ul>
<?php
	}

	if (sizeof($allUnsubmittedUserForms) > 0) {
?>
		<h3>Awaiting completion</h3>
		<p>You have <strong><?php print sizeof($allUnsubmittedUserForms);?> recent forms</strong> awaiting completion.</p>
		<ul class="ul">
<?php
		foreach ($allUnsubmittedUserForms as $userForm) {
			$actualForm = getXFormsForm($userForm->formID, false);
?>
			<li>
				<a href="./xforms_form.php?formID=<?php print $actualForm->id;?>"><?php print $actualForm->title;?></a> | 
				<a href="./xforms_form.php?formID=<?php print $actualForm->id;?>"><em>Complete</em></a> | 
				<a href="./user_home.php?userFormID=<?php print $userForm->id;?>&amp;remove=true"><em><span>Remove</span></em></a>
			</li>
<?php
		}
?>
		</ul>
		<p>You have <strong><?php print sizeof($allUnsubmittedUserForms);?> older forms</strong> awaiting completion.</p>
		<ul class="ul">
			<li>
				<a href="./xforms_form.php?formID=<?php print $actualForm->id;?>"><?php print $actualForm->title;?></a> | 
				<a href="./xforms_form.php?formID=<?php print $actualForm->id;?>"><em>Complete</em></a> | 
				<a href="./user_home.php?userFormID=<?php print $userForm->id;?>&amp;remove=true"><em><span>Remove</span></em></a>
			</li>
		</ul>
<?php
	}
	
	if (sizeof($allSubmittedUserForms) == 0 && sizeof($allUnsubmittedUserForms) == 0) {
?>
		<p>You have no online forms in progress or successfully submitted.</p>
<?php
	}
?>
			

		<!--  Online payments -->
		<h2>Your online payments</h2>
	
<?php	
	if (sizeof($incompleteOrders) > 0) {
?>
		<ul class="ul">
			<li><a href="./payments.php">Online payments basket</a></li>
			<li><a href="./balances.php">Online balances</a></li>
		</ul>
<?php
	}

	if (sizeof($completedOrders) > 0) {
?>
		<h3>Online Payments Archive</h3>
		<ul class="ul">
			<li><a href="./user_payments_archive.php"><?php print sizeof($completedOrders);?> payments submitted online</a></li>
		</ul>
<?php
	}
	
	if (sizeof($pendingOrders) > 0) {
?>
		<h3>Online Payments Pending</h3>
		<ul class="ul">
			<li><a href="./user_payments_pending.php"><?php print sizeof($pendingOrders);?> payments pending order</a></li>
		</ul>
<?php
	}				

	if (sizeof($completedOrders) == 0 && sizeof($pendingOrders) == 0 && sizeof($incompleteOrders) == 0) {
?>
		<p>You have not yet used the online payments facility.</p>
<?php
	}
?>
		<!-- END Online Forms -->
		
		<!--  contact box  -->
		<?php include("../includes/contactbox.php"); ?>
		<!--  END contact box  -->
	
<!-- ###################################### -->
<?php include("../includes/closing.php"); ?>
</body>
</html>