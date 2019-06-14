<div class="widget aligncenter">
	<div class="widget-title">
		<h1 class="nomargin aligncenter"><?php the_title(); ?></h1>
	</div>
	<div class="plan-preview">
		<?php 
		$price=ThemexCourse::getPlanPrice($post->ID);
		if(!empty($price)) {
		?>
		<div class="plan-price product-price">
			<?php echo $price; ?>
		</div>
		<?php } ?>		
		<div class="plan-description">
			<?php the_content(); ?>	
		</div>
		<?php if(!ThemexWoo::isCheckout()) { ?>
		<footer class="plan-footer">
			<form action="<?php echo themex_url(true, ThemexCore::getURL('register')); ?>" method="POST">				
				<a href="#" class="button submit-button <?php if($post->menu_order!=1) { ?>secondary<?php } ?>"><?php _e('Subscribe Now', 'academy'); ?></a>
				<input type="hidden" name="course_action" value="subscribe_user" />
				<input type="hidden" name="plan_id" value="<?php echo $post->ID; ?>" />
				<input type="hidden" name="user_redirect" value="<?php echo $post->ID; ?>" />
				<input type="hidden" name="nonce" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
				<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_course" />
			</form>
		</footer>
		<?php } ?>
	</div>
</div>