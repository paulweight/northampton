<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>
	
	<meta name="Keywords" content="account, regstration, user, profile, register, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Registration" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Registration" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> Registration" />

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
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<p>When you open your account with us, you can get information about your preferred area in <?php print encodeHtml(METADATA_GENERIC_NAME); ?> every time you log on.</p>
	<p>We'll also be able send you email alerts about these areas, but you can opt out if you prefer.</p>
	<p>We also have many electronic forms available, and by signing in to the site, you'll be able to view those that you've already submitted and track their progress.</p>

<?php
	if ($registrationFailed) {
?>
	<h2 class="warning">Please check details highlighted  with ! are entered correctly</h2>
	<p class="warning">Passwords must be re-entered on successive attempts to register</p>
<?php
	}
	
	if (isset($errors['emailInvalid'])) {
?>
	<p class="warning">A user has already registered with the given e-mail address.
<?php
		if (Jadu_Service_User::getInstance()->canUpdateUserPassword()) {
?>
	If you have <a href="<?php print getSiteRootURL() . buildForgotPasswordURL(); ?>">forgotten your password</a>, you can request it to be emailed to you.
<?php
		}
?>
	</p>
<?php
	}
?>

	<form class="basic_form xform" action="<?php print getSecureSiteRootURL() . buildNonReadableRegisterURL(); ?>" method="post" enctype="multipart/form-data">
		<fieldset>
			<legend>Your email address and password</legend>
			<ol>
				<li>
					<label for="reg_email">
						<?php if (isset($errors['emailsNotSame']) || isset($errors['email']) || isset($errors['emailInvalid'])) print "<strong>! ";?>
						Email address
						<?php if (isset($errors['emailsNotSame']) || isset($errors['email']) || isset($errors['emailInvalid'])) print "</strong>"; ?>
						<em>(required)</em>
					</label>
					<input id="reg_email" type="text" name="reg_email" value="<?php print encodeHtml($user->email); ?>" class="field" autocomplete="off" />
				</li>
				<li>
					<label for="email_conf">Confirm email address <em>(required)</em></label>
					<input id="email_conf" type="text" name="email_conf" value="<?php print isset($_POST['email_conf']) ? encodeHtml($_POST['email_conf']) : ''; ?>" class="field" autocomplete="off" />
				</li>
				<li>
					<label for="reg_password">
						<?php if (isset($errors['passwordMismatch']) || isset($errors['password'])) print "<strong>! ";?>
						Password
						<?php if (isset($errors['passwordMismatch']) || isset($errors['password'])) print "</strong>"; ?>
						<em>(6-30 characters required)</em>
					</label>
					<input id="reg_password" type="password" name="reg_password" class="field" maxlength="30" autocomplete="off" />
				</li>
				<li>
					<label for="password_conf">Confirm password <em>(required)</em></label>
					<input id="password_conf" type="password" name="password_conf" class="field" maxlength="30" autocomplete="off" />
				</li>
			</ol>
		</fieldset>

<?php
		if ($registerPreferences->salutation || $registerPreferences->forename
		|| $registerPreferences->surname || $registerPreferences->birthday
		|| $registerPreferences->age || $registerPreferences->sex
		|| $registerPreferences->occupation || $registerPreferences->company
		|| $registerPreferences->address || $registerPreferences->city
		|| $registerPreferences->county || $registerPreferences->postcode
		|| $registerPreferences->country || $registerPreferences->telephone
		|| $registerPreferences->mobile || $registerPreferences->fax
		|| $registerPreferences->website || $registerPreferences->dataProtection
		|| $targetingRules1->question != '' || $targetingRules2->question != '') {
?>
		<fieldset>
			<legend>Your details</legend>
			<ol>
	<?php
			if ($registerPreferences->salutation) { 
	?>
				<li>
					<label for="salutation">
						<?php if (isset($errors['salutation'])) print "<strong>! ";?>Title <?php if (isset($errors['salutation'])) print "</strong>"; ?> <em>(required)</em>
					</label>
					<select id="salutation" name="salutation" >
						<option value="" <?php if ($user->salutation == "") print 'selected="selected"'; ?>>Select...</option>
						<option value="Mr" <?php if ($user->salutation == "Mr") print 'selected="selected"'; ?>>Mr</option>
						<option value="Miss" <?php if ($user->salutation == "Miss") print 'selected="selected"'; ?>>Miss</option>
						<option value="Mrs" <?php if ($user->salutation == "Mrs") print 'selected="selected"'; ?>>Mrs</option>
						<option value="Ms" <?php if ($user->salutation == "Ms") print 'selected="selected"'; ?>>Ms</option>
						<option value="Dr" <?php if ($user->salutation == "Dr") print 'selected="selected"'; ?>>Dr</option>
						<option value="Other" <?php if ($user->salutation == "Other") print 'selected="selected"'; ?>>Other</option>
					</select>
				</li>
	<?php 
			} 
			if ($registerPreferences->forename) { 
	?>
				<li>
					<label for="forename">
						<?php if (isset($errors['forename'])) print "<strong>! ";?>First name <?php if (isset($errors['forename'])) print "</strong>"; ?> <em>(required)</em>
					</label>
					<input id="forename" type="text" name="forename" value="<?php print encodeHtml($user->forename); ?>" class="field" autocomplete="off" />
				</li>
	<?php 
			} 
			if ($registerPreferences->surname) { 
	?>
				<li>
					<label for="surname">
						<?php if (isset($errors['surname'])) print "<strong>! ";?>Surname <?php if (isset($errors['surname'])) print "</strong>"; ?> <em>(required)</em>
					</label>
					<input id="surname" type="text" name="surname" value="<?php print encodeHtml($user->surname); ?>" class="field" autocomplete="off" />
				</li>
	<?php 
			}
			if ($registerPreferences->birthday) { 
	?>
				<li>
					<span class="label">
						<?php if (isset($errors['birthday'])) print "<strong>! ";?>Date of birth <?php if (isset($errors['birthday'])) print "</strong>"; ?> <em>(required)</em>
					</span>
					<ol class="dateOfBirth">
						<li>
						<label for="birthday">
							<input type="text" id="birthday" name="birthday" value="<?php print isset($_POST['birthday']) ? encodeHtml($_POST['birthday']) : '';?>" maxlength="2" class="dob" autocomplete="off" />
							dd
						</label>
						</li>
						<li>
						<label for="dob_month">
							<input type="text" id="dob_month" name="dob_month" value="<?php print isset($_POST['dob_month']) ? encodeHtml($_POST['dob_month']) : ''; ?>" maxlength="2" class="dob" autocomplete="off" />
							mm
						</label>
						</li>
						<li>
						<label for="dob_year">
							<input type="text" id="dob_year" name="dob_year" value="<?php print isset($_POST['dob_year']) ? encodeHtml($_POST['dob_year']) : ''; ?>" maxlength="4" class="dob" autocomplete="off" />
							yyyy 
						</label>
						</li>
					</ol>
				</li>
	<?php 
			}
			if ($registerPreferences->age) { 
	?>
				<li>
					<label for="age">
						<?php if (isset($errors['age'])) print "<strong>! ";?>Age <?php if (isset($errors['age'])) print "</strong>"; ?> <em>(required)</em>
					</label>
					<input id="age" type="text" name="age" value="<?php print encodeHtml($user->age); ?>" class="field" maxlength="3" autocomplete="off" />
				</li>
	<?php 
			} 
			if ($registerPreferences->sex) { 
	?>
				<li>
					<label for="sex">
						<?php if (isset($errors['sex'])) print "<strong>! ";?>Sex <?php if (isset($errors['sex'])) print "</strong>"; ?> <em>(required)</em>
					</label>
					<select id="sex" name="sex">
						<option value="" <?php if ($user->sex == "") print 'selected="selected"'; ?>>Select...</option>
						<option value="Male" <?php if ($user->sex == "Male") print 'selected="selected"'; ?>>Male</option>
						<option value="Female" <?php if ($user->sex == "Female") print 'selected="selected"'; ?>>Female</option>
					</select>
				</li>
	<?php 
			}
			if ($registerPreferences->occupation) {
	?>
				<li>
					<label for="occupation">Occupation </label>
					<input id="occupation" type="text" name="occupation" value="<?php print encodeHtml($user->occupation); ?>" class="field" autocomplete="off" />
				</li>
	<?php 
			} 
			if ($registerPreferences->company) { 
	?>
				<li>
					<label for="company">Company </label>
					<input id="company" type="text" name="company" value="<?php print encodeHtml($user->company); ?>" class="field" autocomplete="off" />
				</li>
	<?php 
			}
			if ($registerPreferences->address) { 
	?>
			</ol>
		</fieldset>
		
		<fieldset>
			<legend>Your contact information</legend>
			<ol>
				<li>
					<label for="address">
						<?php if (isset($errors['address'])) print "<strong>! ";?>Address <?php if (isset($errors['address'])) print "</strong>"; ?> <em>(required)</em>
					</label>
					<textarea id="address" name="address" cols="2" rows="3"><?php print encodeHtml($user->address); ?></textarea>
				</li>
	<?php 
			}
			if ($registerPreferences->city) { 
	?>
				<li>
					<label for="Town">
						<?php if (isset($errors['city'])) print "<strong>! ";?>Town/City <?php if (isset($errors['city'])) print "</strong>"; ?> <em>(required)</em>
					</label>
					<input id="Town" type="text" name="city" value="<?php print encodeHtml($user->city); ?>" class="field" autocomplete="off" />
				</li>
	<?php 
			} 
			if ($registerPreferences->county) { 
	?>
				<li>
					<label for="county">County/Region </label>
					<input type="text" name="county" id="county" value="<?php print encodeHtml($user->county); ?>" class="field" autocomplete="off" />
				</li>
	<?php 
			}
			if ($registerPreferences->postcode) { 
	?>
				<li>
					<label for="postcode">
						<?php if (isset($errors['postcode'])) print "<strong>! ";?>Post code <?php if (isset($errors['postcode'])) print "</strong>"; ?><em>(required)</em>
					</label>
					<input id="postcode" maxlength="10" type="text" name="postcode" value="<?php print encodeHtml($user->postcode); ?>" class="field" autocomplete="off" />
				</li>
	<?php 
			}
			if ($registerPreferences->country) { 
	?>
				<li>
					<label for="country">
						<?php if (isset($errors['country'])) print "<strong>! ";?>Country <?php if (isset($errors['country'])) print "</strong>"; ?> <em>(required)</em>
					</label>
					<select id="country" name="country" >
	<?php 
					if ($user->country != "") {
	?>
						<option selected><?php print encodeHtml($user->country);?></option>
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
				</li>
	<?php
			} 
			if ($registerPreferences->telephone) { 
	?>
				<li>
					<label for="telephone">Telephone </label>
					<input id="telephone" type="text" name="telephone" value="<?php print encodeHtml($user->telephone); ?>" class="field" autocomplete="off" />
				</li>
	<?php 
			}
			if ($registerPreferences->mobile) { 
	?>
				<li>
					<label for="mobile">Mobile </label>
					<input id="mobile" type="text" name="mobile" value="<?php print encodeHtml($user->mobile); ?>" class="field" autocomplete="off" />
				</li>
	<?php 
			}
			if ($registerPreferences->fax) { 
	?>
				<li>
					<label for="fax">Fax </label>
					<input id="fax" type="text" name="fax" value="<?php print encodeHtml($user->fax); ?>" class="field" autocomplete="off" />
				</li>
	<?php
			}
			if ($registerPreferences->website) { 
	?>
				<li>
					<label for="website">Website address </label>
					<input id="website" type="text" name="website" value="<?php print encodeHtml($user->website); ?>" class="field" autocomplete="off" />
				</li>
	<?php 
			}
			if ($registerPreferences->dataProtection) { 
	?>
			</ol>
		</fieldset>
		
		<fieldset>
			<legend>...and finally</legend>
			<ol>
				<li>
					<label for="dataProtection">
						<input type="checkbox" id="dataProtection" name="dataProtection" value="yes" autocomplete="off" /> I would like to be contacted regarding news, updates and offers.
					</label>					
				</li>
<?php 
		}
		if ($targetingRules1->question != '') { 
?>
				<li>
					<p>
						<?php if (isset($errors['question1'])) print "<strong>! ";?><?php print encodeHtml($targetingRules1->question); ?> <?php if (isset($errors['question1'])) print "</strong>";?> <em>(required)</em>
					</p>
					<ul>
	<?php
					$count = 0;
					foreach($targetingRules1->answers as $answer) {
						$checked = "";
						if (isset($_POST['answers'][1])) {
							if (in_array($answer->id, $_POST['answers'][1])) {
								$checked = "checked=\"checked\"";
							}
						}
						print "<li><label for=\"checks_1_$count\"><input type=\"checkbox\" id=\"checks_1_$count\" name=\"answers[1][]\" value=\"$answer->id\" $checked autocomplete=\"off\" /> " . encodeHtml($answer->answer) . "</label></li>";
						$count++;
					}
	?>
						<li><label for="selectAll_1"><input type="checkbox" name="selectAll_1" id="selectAll_1" value="1" onclick="checkAllCheckBoxes(this, 1)" autocomplete="off" /> All of the Above</label></li>
					</ul>
				</li>

<?php
		} // end of question 1 
		if ($targetingRules2->question!='') {
?>

			<!-- Targeting 2 -->
			<li>
				<p>
					<?php if (isset($errors['question2'])) print "<strong>! ";?><?php print encodeHtml($targetingRules2->question); ?> <?php if (isset($errors['question2'])) print "</strong>";?> <em>(required)</em>
				</p>
				<ul>
<?php
				$count = 0;
				foreach($targetingRules2->answers as $answer) {
					$checked = "";
					if (isset($_POST['answers'][2])) {
						if (in_array($answer->id, $_POST['answers'][2])) {
							$checked = "checked=\"checked\"";
						}
					}
					print "<li><label for=\"checks_2_$count\"><input  id=\"checks_2_$count\" type=\"checkbox\" name=\"answers[2][]\" value=\"$answer->id\" $checked autocomplete=\"off\" /> " . encodeHtml($answer->answer) . "</label></li>";
					$count++;
				}
?>
					<li><label for="selectAll_2"><input type="checkbox" name="selectAll_2" id="selectAll_2" value="1" onclick="checkAllCheckBoxes(this, 2)" autocomplete="off" /> All of the Above</label></li>
				</ul>
			</li>
			<!-- END Targeting rules  -->
<?php
		}
?>
		
<?php
		}
?>
			     <ul>
							<li class="centre">
								<input type="submit" value="Register now" name="submit" class="genericButton grey" />
							</li>
				</ul>
			</ol>
		</fieldset>
	</form>

<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
