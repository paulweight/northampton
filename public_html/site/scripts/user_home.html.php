<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="account, regstration, user, profile, personal, details, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> User personal details" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Personal details" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> User personal details" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->
						
<?php
	if ($confirmRemove) {
		$app = getApplication($_GET['userAppID']);
		$job = getRecruitmentJob($app->jobID);
?>

		<p>Are you sure you want to <span class="warning">delete</span> your application for <strong><?php print encodeHtml($job->title); ?></strong></p>
		<p>
			<form action="<?php print getSiteRootURL() . buildNonReadableUserHomeURL(); ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="userAppID" value="<?php print encodeHtml($_GET['userAppID']); ?>" />
				<input type="submit" name="confirmRemove" class="button" value="Yes" />
				<input type="submit" name="declineRemove" class="button" value="No" />
			</form>
		<p>

<?php
		unset($app);
		unset($job);
	}
	else {
?>
		
		<h2>Hello, <em><?php print encodeHtml($user->getDisplayName()); ?></em></h2>
					
		<p>Keep track of your activities and details right here.</p>
					
		<!-- Account options -->
		<h2>Your personal details <?php if (isset($detailsChanged)) { ?><em>have been updated.</em><?php } ?></h2>
			<ul class="list">
<?php
			if (Jadu_Service_User::getInstance()->canUpdateUser()) {
?>
				<li><a href="<?php print getSecureSiteRootURL() . buildChangeDetailsURL(); ?>">Change your details</a></li>
<?php
			}
			
			if (Jadu_Service_User::getInstance()->canUpdateUserPassword()) {
?>
				<li><a href="<?php print getSecureSiteRootURL() . buildChangePasswordURL(); ?>">Change your password</a></li>
<?php
			}
?>
				<li><a href="<?php print getSecureSiteRootURL() . buildSignOutURL();?>">Sign out</a></li>
			</ul>
			

		<!-- Online Forms -->		
		<h2>Your online forms</h2>
<?php 
		if (sizeof($allSubmittedUserForms) > 0) {
?>
			<ul class="list">
				<li><a href="<?php print getSecureSiteRootURL() . buildUserFormURL(); ?>"><?php print sizeof($allSubmittedUserForms) . ' forms submitted online';?></a></li>
			</ul>
<?php		
		}

		if (sizeof($allUnsubmittedUserForms) > 0) {
?>	
						
		<h3>Awaiting completion</h3>
		<p>You have <strong><?php print sizeof($allUnsubmittedUserForms);?> recent forms</strong> awaiting completion.</p>
<?php
			foreach ($allUnsubmittedUserForms as $userForm) {
				$actualForm = getXFormsForm($userForm->formID, false);
?>
			<p><a href="<?php print getSecureSiteRootURL() . buildXFormsURL($actualForm->id) ;?>"><?php print encodeHtml($actualForm->title); ?></a></p>
			<ul class="list">
				<li><a href="<?php print getSecureSiteRootURL() . buildXFormsURL($actualForm->id); ?>">Complete</a></li>
				<li><a href="<?php print getSecureSiteRootURL() . buildUserHomeURL(true, $userForm->id); ?>">Remove</a></li>
			</ul>
<?php
			}
?>
			
<?php
		}

		if (sizeof($allSubmittedUserForms) == 0 && sizeof($allUnsubmittedUserForms) == 0) {
?>
			<p>You have <strong>no online forms in progress</strong> or submitted.</p>
<?php
		}
?>
<?php
		if (sizeof($directoryEntries) > 0) {
?>
				<!-- Directories-->
				<h2>Your directory records</h2>
<?php
			$lastDirectoryID = -1;
			foreach ($directoryEntries as $directoryEntry) {
				$directory = getDirectory($directoryEntry->directoryID);
				if ($directoryEntry->directoryID != $lastDirectoryID) {

					if ($lastDirectoryID != -1) {
						print '</ul>';
					}

					print "<h3>" . encodeHtml($directory->name) . "</h3>";
					$lastDirectoryID = $directory->id;
					print '<ul class="list">';
				}

				print "<li>" . encodeHtml($directoryEntry->title);
				$directoryEntryID = $directoryEntry->id;
				$userEntry = false;
				
				if (isset($directoryEntry->liveEntryID)) {
					$userEntry = true;
					print " (not yet approved)";
				}
				print ' - <a href="' . buildDirectoryRecordURL($directoryEntryID, -1, -1, true, $userEntry) . '">Edit</a>';
				print "</li>";
			}

			print '</ul>';
?>
	
				<!-- end directories -->
<?php
		}		

		if (defined('API_ACCESS') && API_ACCESS != 0) {
			include_once('utilities/JaduAPIKeys.php');
			$numAPIKeys = 0;

			if (Jadu_Service_User::getInstance()->isSessionLoggedIn()) {
				$numAPIKeys = getNumAPIKeysForUser(Jadu_Service_User::getInstance()->getSessionUserID());
			}
?>
			<!-- API Keys -->
			<h2>Developer API</h2>
			<ul class="list">
<?php
			if ($numAPIKeys == 0) {
?>
				<li><a href="<?php print buildAPIApplyURL(); ?>">Apply for an API key</a></li>
<?php
			}
			else {
?>
				<li><a href="<?php print buildAPIKeyURL(); ?>">View API key</a></li>
<?php				
			}	
?>			
			</ul>
<?php
		}
	}
?>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>