<?php include_once("../includes/doctype.php"); ?>
<head>
	<title><?php print encodeHtml($MAST_HEADING); ?> | <?php print encodeHtml(METADATA_GENERIC_NAME); ?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="account, regstration, user, profile, register, change, details, preferences, <?php print encodeHtml(METADATA_GENERIC_KEYWORDS); ?>" />
	<meta name="Description" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> user account change details / preferences" />

	<meta name="DC.title" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> - Change details" />
	<meta name="DC.description" lang="en" content="<?php print encodeHtml(METADATA_GENERIC_NAME); ?> user account change details / preferences" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
</head>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

	<p>Change your account details by simply making the relevant changes and clicking 'Save changes'.</p>
	
<?php
	if ($csrfTokenError) {
?>
	<h2 class="warning">Sorry, your session expired and your details were not saved, please try again</h2>
<?php
	}
	if (count($errors) > 0) {
?>
	<h2 class="warning">Please check details highlighted  with ! are entered correctly</h2>
<?php
		if ($errors['emailInvalid']) {
?>			 
	<h2 class="warning">The email address is not unique</h2>
<?php
		}
	}
?>
	<form class="basic_form xform" name="reg" action="<?php print getSecureSiteRootURL() . buildNonReadableChangeDetailsURL(); ?>" method="post" enctype="multipart/form-data">
	<?php print $csrfToken->renderToken(); ?> 
		<fieldset>
			<legend>Your details</legend>
			<ol>
			<li>
				<label for="email">
					<?php if (isset($errors['email']) || isset($errors['emailInvalid'])) print "<strong>! " ?>
					Email Address
					<?php if (isset($errors['email']) || isset($errors['emailInvalid'])) print "</strong>"; ?>
					<em>(required)</em>
				</label>
				<input id="email" type="text" name="email" value="<?php print encodeHtml($user->email); ?>" />
			</li>
<?php
				if ($registerPreferences->salutation) {
?>
			<li>
				<label for="salutation">
					<?php if (isset($errors['salutation'])) print "<strong>! ";?>
					Salutation
					<?php if (isset($errors['salutation'])) print "</strong>";?>
					<em>(required)</em>
				</label>
				<select id="salutation" name="salutation">
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
					<?php if (isset($errors['forename'])) print "<strong>! ";?>
					Forename
					<?php if (isset($errors['forename'])) print "</strong>";?>
					<em>(required)</em>
				</label>
				<input id="forename" type="text" name="forename" value="<?php print encodeHtml($user->forename); ?>" />
			</li>
<?php
				}
				
				if ($registerPreferences->surname) {
?>
			<li>
				<label for="surname">
					<?php if (isset($errors['surname'])) print "<strong>! ";?>
					Surname
					<?php if (isset($errors['surname'])) print "</strong";?>
					<em>(required)</em>
				</label>
				<input id="surname" type="text" name="surname" value="<?php print encodeHtml($user->surname); ?>" />
			</li>
    <?php
				}
		  
				if ($registerPreferences->birthday) {
?>
			<li>
				<span class="label">
					<?php if (isset($errors['birthday'])) print "<strong>! ";?>
					Date of birth
					<?php if (isset($errors['birthday'])) print "</strong>"; ?>
					<em>(required)</em>
				</span>
				<ol class="dateOfBirth">
					<li>
					<label for="birthday">
						<input type="text" id="birthday" name="birthday" value="<?php print encodeHtml($birthday); ?>" size="2" maxlength="2" />
						dd
					</label>
					</li>
					<li>
					<label for="dob_month">
						<input type="text" id="dob_month" name="dob_month" value="<?php print encodeHtml($dob_month); ?>" size="2" maxlength="2" />
						mm
					</label>
					</li>
					<li>
					<label for="dob_year">
						<input type="text" id="dob_year" name="dob_year" value="<?php print encodeHtml($dob_year); ?>" size="4" maxlength="4" />
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
					<?php if (isset($errors['age'])) print "<strong>! ";?>
					Age
					<?php if (isset($errors['age'])) print "</strong>";?>
					<em>(required)</em>
				</label>
				<input id="age" type="text" name="age" size="3" value="<?php print encodeHtml($user->age); ?>" />
			</li>
<?php
				}
				
				if ($registerPreferences->sex) {
?>
			<li>
				<label for="sex">
					<?php if (isset($errors['sex'])) print "<strong>! ";?>
					Sex
					<?php if (isset($errors['sex'])) print "</strong>";?>
					<em>(required)</em>
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
				<label for="occupation">Occupation</label>
				<input id="occupation" type="text" name="occupation" value="<?php print encodeHtml($user->occupation); ?>" />
			</li>
                        
<?php
				}
				
				if ($registerPreferences->company) {
?>

			<li>
				<label for="company">Company</label>
				<input id="company" type="text" name="company" value="<?php print encodeHtml($user->company); ?>" />
			</li>
    
<?php
				}
				
				if ($registerPreferences->address) {
?>
			<li>
				<label for="address">
					<?php if (isset($errors['address'])) print "<strong>! ";?>
					Address
					<?php if (isset($errors['address'])) print "</strong>";?>
					<em>(required)</em>
				</label>
				<textarea id="address" name="address" cols="2" rows="3"><?php print encodeHtml($user->address); ?></textarea>
			</li> 
<?php
				}
				
				if ($registerPreferences->city) {
?>
			<li>
				<label for="city">
					<?php if (isset($errors['city'])) print "<strong>! ";?>
					Town/City
					<?php if (isset($errors['city'])) print "</strong>";?>
					<em>(required)</em>
					</label>
				<input id="city" type="text" name="city" value="<?php print encodeHtml($user->city); ?>" />
			</li>
<?php
				}
		  
				if ($registerPreferences->county) {
?>			
			<li>
				<label for="county">County/Region</label>
				<input id="county" type="text" name="county" value="<?php print encodeHtml($user->county); ?>" />
			</li>
<?php
				}
		  
				if ($registerPreferences->postcode) {
?>        
			<li>
				<label for="postcode">
					<?php if (isset($errors['postcode'])) print "<strong>! ";?>
					Post code
					<?php if (isset($errors['postcode'])) print "</strong>";?>
					<em>(required)</em>
				</label>
				<input id="postcode" type="text" name="postcode" value="<?php print encodeHtml($user->postcode); ?>" />
			</li>
<?php
			}
			if ($registerPreferences->country) {
?>
			<li>
				<label for="country">
					<?php if (isset($errors['country'])) print "<strong>! ";?>
					Country
					<?php if (isset($errors['country'])) print "</strong>";?>
					<em>(required)</em>
				</label>
				<select id="country" name="country">
<?php
				if ($user->country != "") { 
?>
					<option selected="selected"><?php if (isset($errors['country'])) print "<strong>! ";?><?php print encodeHtml($user->country); ?><?php if (isset($errors['country'])) print "</strong>";?></option>
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
				<label for="telephone">Telephone</label>
				<input id="telephone" type="text" name="telephone" value="<?php print encodeHtml($user->telephone); ?>" />
			</li>
<?php
				}
				if ($registerPreferences->mobile) {
?>
			<li>
				<label for="mobile">Mobile</label>
				<input id="mobile" type="text" name="mobile" value="<?php print encodeHtml($user->mobile); ?>" />
			</li>
<?php
				}
				if ($registerPreferences->fax) {
?>
			<li>
				<label for="fax">Fax</label>
				<input id="fax" type="text" name="fax" value="<?php print encodeHtml($user->fax); ?>" />
			</li>
<?php
				}
				if ($registerPreferences->website) {
?>
			<li>
				<label for="website">Website address</label>
				<input id="website" type="text" name="website" value="<?php print encodeHtml($user->website); ?>" />
			</li>
<?php
				}
				if ($registerPreferences->dataProtection) {
?>
			<li>
				<label for="dataProtection">
					I would like to be contacted regarding news, updates and offers.
				 </label>
					<input type="checkbox" id="dataProtection" name="dataProtection" value="yes"
<?php
					 if ($user->dataProtection == 1) {
						  print " checked ";
					 }
?>
					/>
			</li>
    
<?php
				}
		  
				if ($targetingRules1->question != '') {
?>
				<li>
					<span class="label">
						<?php if (isset($errors['question1'])) print "<strong>! ";?>
						<?php print encodeHtml($targetingRules1->question); ?>
						<?php if (isset($errors['question1'])) print "</strong>";?>
						<em>(required)</em>
					</span>
					<span class="radioButtons">
<?php
    		  		$ans = '1';
					foreach($targetingRules1->answers as $answer) {
						$checked = "";
						$userConditions = $user->getConditions();
						if ($userConditions && in_array($answer->id, $userConditions)) {
							$checked = 'checked="checked"';
						}
						print '<label for="answer' . encodeHtml($ans) . '"><input id="answer' . encodeHtml($ans) . '" type="checkbox" name="answers[1][]" value="' . (int)$answer->id . '" ' . $checked . ' /> ' . encodeHtml($answer->answer) . '</label>';
						$ans++;
					}
?>
					</span>
				</li>
                    
<?php
					} // end of question 1
					if ($targetingRules2->question != '') {
?>
				<li>
					<span class="label">
					<?php if (isset($errors['question2'])) print "<strong>! ";?>
					<?php print encodeHtml($targetingRules2->question); ?>
					<?php if (isset($errors['question1'])) print "</strong>";?>
					<em>(required)</em>
					</span>
					<span class="radioButtons">
<?php
				$ans = '1';
				foreach($targetingRules2->answers as $answer) {
					$checked = "";
					$userConditions = $user->getConditions();
					if ($userConditions && in_array($answer->id, $userConditions)) {
						$checked = 'checked="checked"';
					}
					print '<label for="answer2' . encodeHtml($ans) . '"><input id="answer2' . encodeHtml($ans) . '" type="checkbox" name="answers[2][]" value="' . (int)$answer->id . '" ' . $checked . ' /> ' . encodeHtml($answer->answer) . '</label>';
					$ans++;
				}
?>
					</span>
				</li>
<?php
				}
?>
				<li class="centre">
					<input type="submit" value="Save changes" name="submit" class="genericButton grey" />
				</li>
			</ol>
			</fieldset>  
		</form>
			
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>
