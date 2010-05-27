<?php
	include_once("utilities/JaduStatus.php");
	include_once("JaduStyles.php"); 
		
	if (!isset($_SESSION['userID'])) {
		header("Location: $ERROR_REDIRECT_PAGE");
		exit();
	}

	include_once("marketing/JaduRegisterPreferences.php");
	include_once("marketing/JaduTargettingRules.php");

	$user = getUser($_SESSION['userID']);
	$tmp = $user;

	$registerPreferences=new RegisterPreferences();
	$targettingRules1 = getTargettingRule(1);
	$targettingRules2 = getTargettingRule(2);
	if (isset($_POST['submit'])) {
		$user = new User();
		$user->email = $_POST['email'];
		$user->id = $_SESSION['userID'];
		$user->password = $tmp->password;
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
		if ($_POST['dataProtection'] == "yes") {
			$user->dataProtection = 1;
		}
		else {
			$user->dataProtection = 0;
		}

		$missingFields = $user->getMissingFieldsNoEmailCheck($registerPreferences, $targettingRules1,$targettingRules2, $_POST['answers']);
		
		unset($missingFields['occupation']);
		unset($missingFields['company']);
		unset($missingFields['county']);
		unset($missingFields['telephone']);
		unset($missingFields['mobile']);
		unset($missingFields['fax']);

		if (sizeof($missingFields) == 0) {
			updateUser($user);
			updateUserTargettingRules($user, $_POST['answers']);
			$url = "http://" . $DOMAIN . "/site/scripts/user_home.php?detailsChanged=true";
			header("Location: $url");
			exit();
		}
		else {
			$registrationFailed = true;
		}
	}

	$dob_array = explode('/', $user->birthday);
	$birthday = $dob_array[0];
	$dob_month = str_replace('/', '', $dob_array[1]);
	$dob_year = str_replace('/', '', $dob_array[2]);
	
	$breadcrumb = 'changeDetails';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Change your details | <?php print METADATA_GENERIC_COUNCIL_NAME;?></title>

	<?php include_once("../includes/stylesheets.php"); ?>
	<?php include_once("../includes/metadata.php"); ?>

	<meta name="Keywords" content="account, regstration, user, profile, register, change, details, preferences, <?php print METADATA_GENERIC_COUNCIL_KEYWORDS;?>" />
	<meta name="Description" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> user account change details / preferences" />

	<meta name="DC.title" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> - Change details" />
	<meta name="DC.description" lang="en" content="<?php print METADATA_GENERIC_COUNCIL_NAME;?> user account change details / preferences" />

	<meta name="DC.subject" lang="en" scheme="eGMS.IPSV" content="Local government;Government, politics and public administration" />
	<meta name="DC.subject" lang="en" content="Council, government and democracy" />
	
	<script type="text/javascript" src="<?php print $PROTOCOL.$DOMAIN;?>/site/javascript/global.js"></script>
</head>
<body>
<!-- ########## MAIN STRUCTURE ######### -->
<?php include("../includes/opening.php"); ?>
<!-- ########################## -->

<?php
if ($registrationFailed) {
?>
	<h2 class="warning">Please check details highlighted <strong>!</strong> are entered correctly</h2>
<?php
	 if ($missingFields['emailInvalid']) {
?>			 
	<h2 class="warning">The email address is not unique</h2>
<?php
		}
  }
?>

	<form name="reg" action="http://<?php print $DOMAIN; ?>/site/scripts/change_details.php" method="post" class="basic_form">
		<fieldset>
			<legend>Your details</legend>
			<p>
				<label for="email">
					<?php if ($missingFields['email']) print "<strong>! "; if ($missingFields['emailInvalid']) print "<strong>! "; ?>
					Email Address <em>(required)</em>
					<?php if ($missingFields['email']) print "</strong>"; if ($missingFields['emailInvalid']) print "</strong>"; ?>
				</label>
				<input id="email" type="text" name="email" value="<?php print $user->email; ?>" class="field<?php if ($missingFields['email']) print " warning"; if ($missingFields['emailInvalid']) print " warning"; ?>" />
			</p>
<?php
			 if ($registerPreferences->salutation) {
?>
			<p>
				<label for="salutation">
					<?php if ($missingFields['salutation']) print "<strong>! ";?>
					Salutation <em>(required)</em>
					<?php if ($missingFields['salutation']) print "</strong>";?>
				</label>
				<select id="salutation" name="salutation" class="select<?php if ($missingFields['salutation']) print " warning";?>">
					<option value="Mr" <?php if ($user->salutation == "Mr") print 'selected="selected"'; ?>>Mr</option>
					<option value="Miss" <?php if ($user->salutation == "Miss") print 'selected="selected"'; ?>>Miss</option>
					<option value="Mrs" <?php if ($user->salutation == "Mrs") print 'selected="selected"'; ?>>Mrs</option>
					<option value="Ms" <?php if ($user->salutation == "Ms") print 'selected="selected"'; ?>>Ms</option>
					<option value="Dr" <?php if ($user->salutation == "Dr") print 'selected="selected"'; ?>>Dr</option>
					<option value="Other" <?php if ($user->salutation == "Other") print 'selected="selected"'; ?>>Other</option>
				</select>
			</p>
			<!-- forename component -->
<?php
			}		
			if ($registerPreferences->forename) {
?>
				
			<p>
				<label for="forename">
					<?php if ($missingFields['forename']) print "<strong>! ";?>
					Forename <em>(required)</em>
					<?php if ($missingFields['forename']) print "</strong>";?>
				</label>
				<input id="forename" type="text" name="forename" value="<?php print $user->forename?>" class="field<?php if ($missingFields['forename']) print " warning";?>" />
			</p>
			<!-- surname component -->
<?php
			}
			
			if ($registerPreferences->surname) {
?>
			<p>
				<label for="surname">
					<?php if ($missingFields['surname']) print "<strong>! ";?>
					Surname <em>(required)</em>
					<?php if ($missingFields['surname']) print "</strong";?>
				</label>
				<input id="surname" type="text" name="surname" value="<?php print $user->surname?>" class="field<?php if ($missingFields['surname']) print " warning";?>" />
			</p>

			<!-- DOB component -->
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
					<input type="text" id="birthday" name="birthday" value="<?php print $birthday;?>" maxlength="2" class="dob<?php if ($missingFields['birthday']) print " warning"; ?>" />
					<em>dd</em> 
				</label>
				
				<label for="dob_month" class="dobLabel">
					<input type="text" id="dob_month" name="dob_month" value="<?php print $dob_month;?>" maxlength="2" class="dob<?php if ($missingFields['birthday']) print " warning"; ?>" />
					<em>mm</em>
				</label>
				
				<label for="dob_year" class="dobLabel">
					<input type="text" id="dob_year" name="dob_year" value="<?php print $dob_year;?>" maxlength="4" class="dob<?php if ($missingFields['birthday']) print " warning"; ?>" />
					<em>yyyy</em>
				</label>
			</p>
	
			<!-- age component -->
<?php
			}
	  
			if ($registerPreferences->age) {
?>
			<p>
				<label for="age">
					<?php if ($missingFields['age']) print "<strong>! ";?>
					Age <em>(required)</em>
					<?php if ($missingFields['age']) print "</strong>";?>
				</label>
				<input id="age" type="text" name="age" size="3" value="<?php print $user->age; ?>" class="field<?php if ($missingFields['age']) print " warning";?>" />
			</p>

			<!-- sex component -->
<?php
			}
			
			if ($registerPreferences->sex) {
?>
			<p>
				<label for="sex">
					<?php if ($missingFields['sex']) print "<strong>! ";?>
					Sex <em>(required)</em>
					<?php if ($missingFields['sex']) print "</strong>";?>
				</label>
				<select id="sex" class="select<?php if ($missingFields['sex']) print " warning";?>" name="sex">
					<option value="" <?php if ($user->sex == "") print 'selected="selected"'; ?>>Select...</option>
					<option value="Male" <?php if ($user->sex == "Male") print 'selected="selected"'; ?>>Male</option>
					<option value="Female" <?php if ($user->sex == "Female") print 'selected="selected"'; ?>>Female</option>
				</select>
			</p>

			<!-- occupation component -->
<?php
			}
	  
			if ($registerPreferences->occupation) {
?>

			<p>
				<label for="occupation">Occupation</label>
				<input id="occupation" type="text" name="occupation" value="<?php print $user->occupation; ?>" class="field" />
			</p>
					
			<!-- company component -->
<?php
			}
			
			if ($registerPreferences->company) {
?>

			<p>
				<label for="company">Company</label>
				<input id="company" type="text" name="company" value="<?php print $user->company; ?>" class="field" />
			</p>

		  <!-- Address component -->
<?php
			}
			
			if ($registerPreferences->address) {
?>
				
			<!-- contact details -->
			<p>
				<label for="address">
					<?php if ($missingFields['address']) print "<strong>! ";?>
					Address <em>(required)</em>
					<?php if ($missingFields['address']) print "</strong>";?>
				</label>
				<textarea id="address" name="address" class="field<?php if ($missingFields['address']) print " warning";?>" cols="2" rows="3"><?php print $user->address; ?></textarea>
			</p>
 
			<!-- town component -->
<?php
			}
			
			if ($registerPreferences->city) {
?>
			<p>
				<label for="city">
					<?php if ($missingFields['city']) print "<strong>! ";?>
					Town/City <em>(required)</em>
					<?php if ($missingFields['city']) print "</strong>";?>
					</label>
				<input id="city" type="text" name="city" value="<?php print $user->city; ?>" class="field<?php if ($missingFields['city']) print " warning";?>" />
			</p>
 
			<!-- county component -->
<?php
			}
	  
			if ($registerPreferences->county) {
?>
			
			<p>
				<label for="county">County / Region</label>
				<input id="county" type="text" name="county" value="<?php print $user->county; ?>" class="field" />
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
					<?php if ($missingFields['postcode']) print "</strong>";?>
				</label>
				<input id="postcode" type="text" name="postcode" value="<?php print $user->postcode; ?>" class="field<?php if ($missingFields['postcode']) print " warning";?>" />
			</p>

			<!-- Country component -->
<?php
			}
			if ($registerPreferences->country) {
?>

			<p>
				<label for="country">
					<?php if ($missingFields['country']) print "<strong>! ";?>
					Country <em>(required)</em>
					<?php if ($missingFields['country']) print "</strong>";?>
				</label>
				<select id="country" name="country" class="select<?php if ($missingFields['country']) print " warning";?>">
<?php
				if ($user->country != "") { 
?>
					<option selected><?php if ($missingFields['country']) print "<strong>! ";?><?php print $user->country;?><?php if ($missingFields['country']) print "</strong>";?></option>
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
		 
			<!-- Telephone component -->
<?php
			}
			
			if ($registerPreferences->telephone) {
?>

			<p>
				<label for="telephone">Telephone</label>
				<input id="telephone" type="text" name="telephone" value="<?php print $user->telephone?>" class="field" />
			</p>

			<!-- Mobile component -->
<?php
			}
			if ($registerPreferences->mobile) {
?>

			<p>
				<label for="mobile">Mobile</label>
				<input id="mobile" type="text" name="mobile" value="<?php print $user->mobile?>" class="field" />
			</p>

			<!-- fax component -->
<?php
			}
			if ($registerPreferences->fax) {
?>

			<p>
				<label for="fax">Fax</label>
				<input id="fax" type="text" name="fax" value="<?php print $user->fax?>" class="field" />
			</p>

			<!-- Website component -->
<?php
			}
			if ($registerPreferences->website) {
?>

			<p>
				<label for="website">Website address</label>
				<input id="website" type="text" name="website" value="<?php print $user->website; ?>" class="field" />
			</p>
	
			<!-- Data protection question -->
<?php
			}
			if ($registerPreferences->dataProtection) {
?>

			<p class="form_text">
				<label for="dataProtection">
					Would like to be contacted regarding news, updates and offers?	
					<input id="dataProtection"
<?php
					 if ($user->dataProtection == 1) {
						  print " checked ";
					 }
?>
				type="checkbox" name="dataProtection" value="yes" />
				<span>(Tick for) yes, I would.</span>
				</label>
				<br class="clear" />
			</p>

			<!-- Targeting rules  -->
<?php
			}
	  
			if ($targettingRules1->question != '') {
?>

			<!-- Targeting one -->
			<p class="form_text">
				<?php if ($missingFields['question1']) print "<strong>! ";?>
				<?php print $targettingRules1->question?>
				<?php if ($missingFields['question1']) print "</strong>";?>
				<em>(required)</em>
<?php
				$ans = '1';
				foreach($targettingRules1->answers as $answer) {
					 $checked = "";
					 $userConditions = $user->getConditions();
					 if ($userConditions && in_array($answer->id, $userConditions)) {
						  $checked = "checked=\"checked\"";
						}
					 print "<label for=\"answer$ans\"><input id=\"answer$ans\" type=\"checkbox\" name=\"answers[]\" value=\"$answer->id\" $checked /> $answer->answer</label>";
					$ans++;
				}
?>
				<span class="clear"></span>
			</p>
				
<?php
				} // end of question 1
				if ($targettingRules2->question!='') {
?>

			<!-- Targeting two  -->                    
			<p class="form_text">
				<?php if ($missingFields['question2']) print "<strong>! ";?>
				<?php print $targettingRules2->question;?>
				<?php if ($missingFields['question1']) print "</strong>";?>
				<em>(required)</em>
<?php
			$ans = '1';
			foreach($targettingRules2->answers as $answer) {
				$checked = "";
				$userConditions = $user->getConditions();
				if ($userConditions && in_array($answer->id, $userConditions)) {
					$checked = "checked=\"checked\"";
				}
				print "<label for=\"answer2$ans\"><input id=\"answer2$ans\" type=\"checkbox\" name=\"answers[]\" value=\"$answer->id\" $checked /> $answer->answer</label>";
					 $ans++;
				  }
?>
			</p>
				
<?php
				}
?>

			<p class="centre">
				<input type="submit" value="Save changes" name="submit" class="button" />
			</p>
		</fieldset>  
	</form>

	<!-- The Contact box -->
	<?php include("../includes/contactbox.php"); ?>
	<!-- END of the Contact box -->
	
<!-- ################ MAIN STRUCTURE ############ -->
<?php include("../includes/closing.php"); ?>