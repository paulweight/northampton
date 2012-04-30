	<h2><strong>Closing Date:</strong> <?php print date("l jS F Y", $job->closingDate);?></h2>

	<div class="download_box">
		<h3><?php print $job->title;?> Application</h3>
		<ol class="info_left noList">
			<li><a href="<?php print getSiteRootURL() . buildJobApplicationURL('details', $app->id); ?>">Application home</a></li>
			<li><img src="<?php print getStaticContentRootURL(); ?>/site/images/<?php if ($app->instructionsViewed == 1) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if ($app->instructionsViewed == 1) print "Completed"; else print "Incomplete"; ?>"/> <a href="<?php print getSiteRootURL() . buildJobApplicationURL('instructions', $app->id); ?>">Read Application Notes</a></li>
			<li><img src="<?php print getStaticContentRootURL(); ?>/site/images/<?php if (isPersonalDetailsComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isPersonalDetailsComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> <a href="<?php print getSiteRootURL() . buildJobApplicationURL( 'personal', $app->id); ?>">Personal Details</a></li>
			<li><img src="<?php print getStaticContentRootURL(); ?>/site/images/<?php if (isEqualOpsComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isEqualOpsComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> <a href="<?php print getSiteRootURL() . buildJobApplicationURL('equalOps', $app->id); ?>">Equal Opportunities</a></li>
			<li><img src="<?php print getStaticContentRootURL(); ?>/site/images/<?php if (isEducationComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isEducationComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> <a href="<?php print getSiteRootURL() . buildJobApplicationURL('education', $app->id); ?>">Education</a></li>
			<li><img src="<?php print getStaticContentRootURL(); ?>/site/images/<?php if (isCurrentEmploymentComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isCurrentEmploymentComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> <a href="<?php print getSiteRootURL() . buildJobApplicationURL('employmentCurrent', $app->id); ?>">Employment</a></li>
			<li><img src="<?php print getStaticContentRootURL(); ?>/site/images/<?php if (isExperienceComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (isExperienceComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> <a  href="<?php print getSiteRootURL() . buildJobApplicationURL('experience', $app->id); ?>">Supporting Information</a></li>
			<li><img src="<?php print getStaticContentRootURL(); ?>/site/images/<?php if (areReferencesComplete($app->id)) print "action_check.gif"; else print "action_delete.gif"; ?>"  alt="<?php if (areReferencesComplete($app->id)) print "Completed"; else print "Incomplete"; ?>"/> <a  href="<?php print getSiteRootURL() . buildJobApplicationURL('references', $app->id); ?>">References</a></li>
		</ol>
		<div class="clear"></div>
	</div>
	<!-- END Step / progress box -->

	