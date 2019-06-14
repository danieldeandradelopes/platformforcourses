<form action="<?php echo themex_url(true, ThemexCore::getURL('register')); ?>" method="POST">
	<?php if(!ThemexCourse::isSubscriber()) { ?>
	<a href="#" class="button medium submit-button left"><?php _e('Subscribe Now', 'academy'); ?></a>
	<input type="hidden" name="course_action" value="subscribe_user" />
	<input type="hidden" name="user_redirect" value="<?php echo intval(reset(ThemexCourse::$data['plans'])); ?>" />
	<?php } else if(!ThemexCourse::isMember()) { ?>
		<?php if(ThemexCourse::$data['status']!='private' && ThemexCourse::$data['capacity']>=0) { ?>
		<a href="#" class="button medium price-button submit-button left">		
			<?php if(ThemexCourse::$data['status']=='premium' && ThemexCourse::$data['product']!=0) { ?>
			<span class="caption"><?php _e('Take This Course', 'academy'); ?></span>
			<span class="price"><?php echo ThemexCourse::$data['price']['text']; ?></span>
			<?php } else { ?>
			<?php _e('Take This Course', 'academy'); ?>
			<?php } ?>
		</a>
		<input type="hidden" name="course_action" value="add_user" />
		<input type="hidden" name="user_redirect" value="<?php echo ThemexCourse::$data['ID']; ?>" />
		<?php } ?>
	<?php } else { ?>
		<?php if(!ThemexCore::checkOption('course_retake')) { ?>
		<a href="#" class="button secondary medium submit-button left"><?php _e('Unsubscribe Now', 'academy'); ?></a>
		<input type="hidden" name="course_action" value="remove_user" />
		<?php } ?>
		<?php if(ThemexCourse::hasCertificate()) { ?>
		<a href="<?php echo ThemexCore::getURL('certificate', themex_encode(ThemexCourse::$data['ID'], ThemexUser::$data['user']['ID'])); ?>" target="_blank" class="button medium certificate-button"><?php _e('View Certificate', 'academy'); ?></a>
		<?php } ?>
	<?php } ?>
	<input type="hidden" name="course_id" value="<?php echo ThemexCourse::$data['ID']; ?>" />
	<input type="hidden" name="plan_id" value="<?php echo intval(reset(ThemexCourse::$data['plans'])); ?>" />	
	<input type="hidden" name="nonce" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
	<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_course" />
</form>