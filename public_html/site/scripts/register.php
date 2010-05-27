<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php");
	
	include_once("marketing/JaduRegisterPreferences.php");
	include_once("marketing/JaduTargettingRules.php");
	include_once("marketing/JaduUsers.php");
	include_once('marketing/JaduPHPBB3.php');

	if (isset($_SESSION['userID'])) {
		header('Location: ./change_details.php');
		exit;
	}
	
	$registerPreferences = new RegisterPreferences();
	$targettingRules1 = getTargettingRule(1);
	$targettingRules2 = getTargettingRule(2);
	
	if (isset($submit)) {
		$user = new User();
		$user->email = $_POST['reg_email'];
		$user->password = $_POST['reg_password'];
		$user->salutation = $_POST['salutation'];
		$user->forename = $_POST['forename'];
		$user->surname = $_POST['surname'];
		$user->birthday = $_POST['birthday'] . '/' . $_POST['dob_month'] . '/' . $_POST['dob_year'];
		$user->age = $_POST['age'];
		$user->sex = $_POST['sex'];
		$user->occupation = $_POST['occupation'];
		$user->company = $_POST['company'];
		$user->address = $_POST['address'];
		$user->city = $_POST['city'];
		$user->county = $_POST['county'];
		$user->postcode = $_POST['postcode'];
		$user->country = $_POST['country'];
		$user->telephone = $_POST['telephone'];
		$user->mobile = $_POST['mobile'];
		$user->fax = $_POST['fax'];
		$user->website = $_POST['website'];
		if ($_POST['dataProtection'] == 'yes') {
			$user->dataProtection = 1;
		}
		else {
			$user->dataProtection = 0;
		}

		$missingFields = $user->getMissingFields($registerPreferences, $targettingRules1, $targettingRules2, $_POST['answers']);

		unset($missingFields['occupation']);
		unset($missingFields['company']);
		unset($missingFields['county']);
		unset($missingFields['telephone']);
		unset($missingFields['mobile']);
		unset($missingFields['fax']);
		unset($missingFields['question1']);
		unset($missingFields['question2']);

		if ($_POST['reg_email'] != $_POST['email_conf']) {
			$missingFields['emailsNotSame'] = true;
		}

		if ($_POST['reg_password'] != $_POST['password_conf'] || strlen($_POST['reg_password']) < 6 || strlen($_POST['reg_password']) > 30) {
			$missingFields['passwordMismatch'] = true;
		}
	
		if (sizeof($missingFields) == 0) {

			if ($registerPreferences->emailAuthentication == '1') {
				$user->authenticated = NOT_AUTHENTICATED;
			}
			else {
				$user->authenticated = AUTHENTICATED;
			}

			$userID = newUser($user, $answers);
			$user->surname = addslashes($user->surname);
			$user->user_email = addslashes($user->email);

			// create a phpbb user if necessary
			if (defined('PHPBB_INTEGRATION') && PHPBB_INTEGRATION == true) {
			    newPHPBBUser($user);
			}

			if ($user->isAuthenticated()) {
				$_SESSION['userID'] = $userID;
				$address = "http://".$DOMAIN . "/site/scripts/register_accept.php";
				header("Location: $address");
				exit();
			}
			else {
				header("Location: $AUTHENTICATION_URL?email=$user->email&new=true");
				exit();
			}
		}
		else {
			$registrationFailed = true;
		}
	}
	
	$breadcrumb = 'register';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Register | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="account, regstration, user, profile, register, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Registration" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Registration" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> Registration" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript">
		// <!--
		function checkAllCheckBoxes(checkbox, question_num)
		{
			var form = checkbox.form;
			var numChecks = 20;
			for (var i = 0; i < numChecks; i++) {
				form["checks_"+question_num+"_"+i].checked = checkbox.checked;
				if (!form["checks_"+question_num+"_"+(i+1)]) {
					i = numChecks;
				}
			}
		}
		function uncheckEverythingBox(question_num)
		{
			var everything = document.getElementById('selectAll_'+question_num);
			everything.checked = false;
		}
		// -->
	</script>
	
	<script type="text/javascript" src="site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
	$tabIndex = 1;
	if ($registrationFailed) {
?>
	<h2 class="warning">Please check details highlighted <strong>!</strong> are entered correctly</h2>
	<p class="warning">Passwords must be <strong>re-entered</strong> on successive attempts to register</p>


<?php
	}
?>
	<p class="first">When you open your account with us, you can get information about your preferred area every time you log on.
	We'll also be able send you email alerts about these areas, but you can opt out if you prefer.
	We also have many electronic forms available, and by signing in to the site, you'll be able to view 
	those that you've already submitted.</p>
	
	<form class="basic_form" name="register" action="<?php print $SECURE_SERVER;?>/site/scripts/register.php" method="post">
		<!-- Email address and password -->
		<fieldset>
			<legend>Your email address and password</legend>
			<p>
				<label for="reg_email">
					<?php if ($missingFields['emailsNotSame'] || $missingFields['email'] || $missingFields['emailInvalid']) print "<strong>! ";?>
					Email address <em>(required)</em>
					<?php if ($missingFields['emailsNotSame'] || $missingFields['email'] || $missingFields['emailInvalid']) print "</strong>"; ?>
				</label>
				<input id="reg_email" type="text" name="reg_email" value="<?php print $_POST['reg_email'];?>" class="field<?php if ($missingFields['emailsNotSame'] || $missingFields['email'] || $missingFields['emailInvalid']) print " warning";?>" />
			</p>
				
			<p>
				<label for="email_conf">Confirm email address <em>(required)</em></label>
				<input id="email_conf" type="text" name="email_conf" value="<?php print $_POST['email_conf'];?>" class="field" />
			</p>
			
			<p>
				<label for="reg_password">
					<?php if ($missingFields['passwordMismatch'] || $missingFields['password']) print "<strong>! ";?>
					Password <em>(6-20 characters required)</em>
					<?php if ($missingFields['passwordMismatch'] || $missingFields['password']) print "</strong>"; ?>
				</label>
				<input id="reg_password" type="password" name="reg_password" class="field<?php if ($missingFields['passwordMismatch'] || $missingFields['password']) print " warning";?>" maxlength="20" />
			</p>
			
			<p>
				<label for="password_conf">Confirm password <em>(required)</em></label>
				<input id="password_conf" type="password" name="password_conf" class="field" maxlength="20" />
			</p>
		</fieldset>


		<fieldset>
			<legend>Your details</legend>

			<!-- Salutation -->
<?php
		if ($registerPreferences->salutation) { 
?>
			<p>
				<label for="salutation">
					<?php if ($missingFields['salutation']) print "<strong>! ";?>
					Title <em>(required)</em>
					<?php if ($missingFields['salutation']) print "</strong>"; ?>
				</label>
				<select id="salutation" name="salutation" class="select<?php if ($missingFields['salutation']) print " warning";?>">
					<option value="" <?php if ($_POST['salutation'] == "") print 'selected="selected"'; ?>>Select...</option>
					<option value="Mr" <?php if ($_POST['salutation'] == "Mr") print 'selected="selected"'; ?>>Mr</option>
					<option value="Miss" <?php if ($_POST['salutation'] == "Miss") print 'selected="selected"'; ?>>Miss</option>
					<option value="Mrs" <?php if ($_POST['salutation'] == "Mrs") print 'selected="selected"'; ?>>Mrs</option>
					<option value="Ms" <?php if ($_POST['salutation'] == "Ms") print 'selected="selected"'; ?>>Ms</option>
					<option value="Dr" <?php if ($_POST['salutation'] == "Dr") print 'selected="selected"'; ?>>Dr</option>
					<option value="Other" <?php if ($_POST['salutation'] == "Other") print 'selected="selected"'; ?>>Other</option>
				</select>
			</p>

			<!-- Forename -->
<?php 
		} 
		if ($registerPreferences->forename) { 
?>
			<p>
				<label for="forename">
					<?php if ($missingFields['forename']) print "<strong>! ";?>
					First name <em>(required)</em>
					<?php if ($missingFields['forename']) print "</strong>"; ?>
				</label>
				<input id="forename" type="text" name="forename" value="<?php print $_POST['forename'];?>" class="field<?php if ($missingFields['forename']) print " warning";?>" />
			</p>

			<!-- Surname -->
<?php 
		} 
		if ($registerPreferences->surname) { 
?>
			<p>
				<label for="surname">
					<?php if ($missingFields['surname']) print "<strong>! ";?>
					Surname <em>(required)</em>
					<?php if ($missingFields['surname']) print "</strong>"; ?>
				</label>
				<input id="surname" type="text" name="surname" value="<?php print $_POST['surname'];?>" class="field<?php if ($missingFields['surname']) print " warning"; ?>" />
			</p>

			<!-- DOB -->
<?php 
		}
		if ($registerPreferences->birthday) { 
?>
			<p class="date_birth">
				<label>
					<?php if ($missingFields['birthday']) print "<strong>! ";?>
					Date of birth <em>(required)</em>
					<?php if ($missingFields['birthday']) print "</strong>"; ?>
				</label>

				<label for="birthday" class="dobLabel">
					<input type="text" id="birthday" name="birthday" value="<?php print $_POST['birthday'];?>" maxlength="2" class="dob<?php if ($missingFields['birthday']) print " warning"; ?>" />
					<em>dd</em> 
				</label>
				
				<label for="dob_month" class="dobLabel">
					<input type="text" id="dob_month" name="dob_month" value="<?php print $_POST['dob_month'];?>" maxlength="2" class="dob<?php if ($missingFields['birthday']) print " warning"; ?>" />
					<em>mm</em>
				</label>
				
				<label for="dob_year" class="dobLabel">
					<input type="text" id="dob_year" name="dob_year" value="<?php print $_POST['dob_year'];?>" maxlength="4" class="dob<?php if ($missingFields['birthday']) print " warning"; ?>" />
					<em>yyyy</em>
				</label>
			</p>

			<!-- Age -->
<?php 
		}
		if ($registerPreferences->age) { 
?>
			<p>
				<label for="age">
					<?php if ($missingFields['age']) print "<strong>! ";?>
					Age <em>(required)</em>
					<?php if ($missingFields['age']) print "</strong>"; ?>
				</label>
				<input id="age" type="text" name="age" value="<?php print $_POST['age'];?>" class="field<?php if ($missingFields['age']) print " warning";?>" maxlength="3" />
			</p>
			<!-- Sex -->
<?php 
		} 
		if ($registerPreferences->sex) { 
?>
			<p>
				<label for="sex">
				<?php if ($missingFields['sex']) print "<strong>! ";?>
				Sex <em>(required)</em>
				<?php if ($missingFields['sex']) print "</strong>"; ?>
				
				</label>
				<select id="sex" class="select<?php if ($missingFields['sex']) print " warning";?>" name="sex">
					<option value="" <?php if ($_POST['sex'] == "") print 'selected="selected"'; ?>>Select...</option>
					<option value="male" <?php if ($_POST['sex'] == "male") print 'selected="selected"'; ?>>Male</option>
					<option value="female" <?php if ($_POST['sex'] == "female") print 'selected="selected"'; ?>>Female</option>
				</select>
			</p>

			<!-- occupation component -->
<?php 
		}
		if ($registerPreferences->occupation) {
?>
			<p>
				<label for="occupation">Occupation </label>
				<input id="occupation" type="text" name="occupation" value="<?php print $_POST['occupation'];?>" class="field" />
			</p>

			<!-- company component -->
<?php 
		} 
		if ($registerPreferences->company) { 
?>
			<p>
				<label for="company">Company </label>
				<input id="company" type="text" name="company" value="<?php print $_POST['company'];?>" class="field" />
			</p>

<?php 
		}
		if ($registerPreferences->address) { 
?>
		</fieldset>

		<fieldset>
			<legend>Your contact information</legend>
			
			<!-- Address -->
			<p>
				<label for="address">
					<?php if ($missingFields['address']) print "<strong>! ";?>
					Address <em>(required)</em>
					<?php if ($missingFields['address']) print "</strong>"; ?>
				</label>
				<textarea id="address" name="address" class="field<?php if ($missingFields['address']) print " warning";?>" cols="2" rows="3"><?php print $_POST['address'];?></textarea>
			</p>

			<!-- Town -->
<?php 
		}
		if ($registerPreferences->city) { 
?>
			<p>
				<label for="Town">
					<?php if ($missingFields['city']) print "<strong>! ";?>
					Town/City <em>(required)</em>
					<?php if ($missingFields['city']) print "</strong>"; ?>
				</label>
				<input id="Town" type="text" name="city" value="<?php print $_POST['city'];?>" class="field<?php if ($missingFields['city']) print " warning";?>" />
			</p>

			<!-- County -->
<?php 
		} 
		if ($registerPreferences->county) { 
?>
			<p>
				<label>County / Region </label>
				<input type="text" name="county" value="<?php print $_POST['county'];?>" class="field" />
			</p>

			<!-- Postcode component -->
<?php 
		}
		if ($registerPreferences->postcode) { 
?>
			<p>
				<label for="postcode">
					<?php if ($missingFields['postcode']) print "<strong>! ";?>
					Post code <em>(required)</em>
					<?php if ($missingFields['postcode']) print "</strong>"; ?>
				</label>
				<input id="postcode" maxlength="10" type="text" name="postcode" value="<?php print $_POST['postcode'];?>" class="field<?php if ($missingFields['postcode']) print " warning"; ?>" />
			</p>

			<!-- Country -->
<?php 
		}
		if ($registerPreferences->country) { 
?>
			<p>
				<label for="country">
					<?php if ($missingFields['country']) print "<strong>! ";?>
					Country <em>(required)</em>
					<?php if ($missingFields['country']) print "</strong>"; ?>
				</label>
				
				<select class="select<?php if ($missingFields['country']) print " warning";?>" id="country" name="country" >
<?php 
				if ($_POST['country'] != "") {
?>
					<option selected><?php print $_POST['country'];?></option>
<?php
				}
				else { 
?>
					<option value="">Please choose a country</option>
<?php
				}
				include_once('../includes/countries.php');
?>
				</select>
			</p>

			<!-- Telephone -->
<?php
		} 
		if ($registerPreferences->telephone) { 
?>
			<p>
				<label for="telephone">Telephone </label>
				<input id="telephone" type="text" name="telephone" value="<?php print $_POST['telephone'];?>" class="field" />
			</p>

			<!-- Mobile -->
<?php 
		}
		if ($registerPreferences->mobile) { 
?>
			<p>
				<label for="mobile">Mobile </label>
				<input id="mobile" type="text" name="mobile" value="<?php print $_POST['mobile'];?>" class="field" />
			</p>

			<!-- fax -->
<?php 
		}
		if ($registerPreferences->fax) { 
?>
			<p>
				<label for="fax">Fax </label>
				<input id="fax" type="text" name="fax" value="<?php print $_POST['fax'];?>" class="field" />
			</p>

			<!-- website component -->
<?php
		}
		if ($registerPreferences->website) { 
?>
			<p>
				<label for="website">Website address </label>
				<input id="website" type="text" name="website" value="<?php print $_POST['website'];?>" class="field" />
			</p>

<?php 
		}
		if ($registerPreferences->dataProtection) { 
?>
		</fieldset>
			
		<fieldset>
			<legend>...and finally</legend>
			
			<!-- Data protection -->
			<p class="form_text">
				<label for="dataProtection">
					Would you like to be contacted regarding news, updates and offers?
					<input type="checkbox" id="dataProtection" name="dataProtection" value="yes" />
					<span>(Tick for) yes, I would.</span>
				</label>
				<br class="clear" />
			</p>

			<!-- Targeting one  -->
<?php 
		}
		if ($targettingRules1->question != '') { 
?>
			<p class="form_text">
			
				<label>
				<?php if ($missingFields['question1']) print "<strong>! ";?>
				<?php print $targettingRules1->question; ?>
				<?php if ($missingFields['question1']) print "</strong>";?> 
				</label>
<?php
				$count = 0;
				foreach($targettingRules1->answers as $answer) {
					$checked = "";
					if ($answers) {
						if (in_array($answer->id, $_POST['answers'])) {
							$checked = "checked=\"checked\"";
						}
					}
					print "<label for=\"checks_1_$count\"><input type=\"checkbox\" id=\"checks_1_$count\" name=\"answers[]\" value=\"$answer->id\" $checked />$answer->answer</label>";
					$tabIndex++;
					$count++;
				}
?>
				<label for="selectAll_1"><input type="checkbox" name="selectAll_1" id="selectAll_1" value="1" onclick="checkAllCheckBoxes(this, 1)" />All of the Above</label>
				<span class="clear"></span>
			</p>

<?php
			$tabIndex++;
		} // end of question 1 
		if ($targettingRules2->question!='') {
?>

			<!-- Targeting 2 -->
			<p class="form_text">
				<?php if ($missingFields['question2']) print "<strong>! ";?>
				<?php print $targettingRules2->question;?>
				<?php if ($missingFields['question2']) print "</strong>";?>

				<?php
				$count = 0;
				foreach($targettingRules2->answers as $answer) {
					$checked = "";
					if ($answers) {
						if (in_array($answer->id, $_POST['answers'])) {
							$checked = "checked=\"checked\"";
						}
					}
					print "<label for=\"checks_2_$count\"><input  id=\"checks_2_$count\" type=\"checkbox\" name=\"answers[]\" value=\"$answer->id\" $checked />$answer->answer</label>";
					$tabIndex++;
					$count++;
				}
?>
				<label for="selectAll_2"><input type="checkbox" name="selectAll_2" id="selectAll_2" value="1" onclick="checkAllCheckBoxes(this, 2)" />All of the Above</label>
				<span class="clear"></span>
			</p>
			<!-- END Targeting rules  -->
<?php
		}
?>
			<p class="centre">
				<input type="submit" value="Register now" name="submit" class="button" />
			</p>
		</fieldset>
		</form>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
