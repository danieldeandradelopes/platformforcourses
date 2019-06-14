<div class="course-rating rating-form">
	<div data-score="<?php echo round(ThemexCourse::$data['rating']); ?>" <?php if(ThemexCourse::isRated()) { ?>data-readonly="true"<?php } ?>></div>
	<?php if(!ThemexCourse::isRated() && !ThemexWoo::isCheckout()) { ?>
	<form class="ajax-form hidden" action="<?php echo AJAX_URL; ?>" method="POST">
		<input type="hidden" name="course_rating" class="rating" value="" />
		<input type="hidden" name="course_id" value="<?php echo ThemexCourse::$data['ID']; ?>" />		
		<input type="hidden" name="course_action" value="update_rating" />
		<input type="hidden" name="nonce" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
		<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_course" />
	</form>
	<?php } ?>
</div>