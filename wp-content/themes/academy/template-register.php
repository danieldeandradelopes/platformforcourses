<?php
/*
Template Name: Registration
*/
?>
<?php get_header(); ?>
<?php if(get_option('users_can_register')) { ?>
<div class="eightcol column">
	<h1><?php _e('Register','academy'); ?></h1>
	<form class="ajax-form formatted-form" action="<?php echo AJAX_URL; ?>" method="POST">
		<div class="message"></div>
		<div class="sixcol column">
			<div class="field-wrapper">
				<input type="text" name="user_login" placeholder="<?php _e('Username','academy'); ?>" />
			</div>								
		</div>
		<div class="sixcol column last">
			<div class="field-wrapper">
				<input type="text" name="user_email" placeholder="<?php _e('Email','academy'); ?>" />
			</div>
		</div>
		<div class="clear"></div>
		<div class="sixcol column">
			<div class="field-wrapper">
				<input type="password" name="user_password" placeholder="<?php _e('Password','academy'); ?>" />
			</div>
		</div>
		<div class="sixcol column last">
			<div class="field-wrapper">
				<input type="password" name="user_password_repeat" placeholder="<?php _e('Repeat Password','academy'); ?>" />
			</div>
		</div>
		<div class="clear"></div>			
		<?php if(ThemexCore::checkOption('user_captcha')) { ?>
		<div class="form-captcha">
			<img src="<?php echo THEMEX_URI; ?>assets/images/captcha/captcha.php" alt="" />
			<input type="text" name="captcha" id="captcha" size="6" value="" />
		</div>
		<div class="clear"></div>
		<?php } ?>
		<a href="#" class="button submit-button left"><span class="button-icon register"></span><?php _e('Register','academy'); ?></a>
		<div class="form-loader"></div>
		<input type="hidden" name="user_action" value="register_user" />
		<input type="hidden" name="user_redirect" value="<?php echo themex_value($_POST, 'user_redirect'); ?>" />
		<input type="hidden" name="nonce" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
		<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
	</form>
</div>
<?php } ?>
<div class="fourcol column last">
	<?php if(get_option('users_can_register')) { ?>
	<h1><?php _e('Sign In','academy'); ?></h1>
	<?php } ?>
	<form class="ajax-form formatted-form" action="<?php echo AJAX_URL; ?>" method="POST">
		<div class="message"></div>
		<div class="field-wrapper">
			<input type="text" name="user_login" placeholder="<?php _e('Username','academy'); ?>" />
		</div>
		<div class="field-wrapper">
			<input type="password" name="user_password" placeholder="<?php _e('Password','academy'); ?>" />
		</div>			
		<a href="#" class="button submit-button left"><span class="button-icon login"></span><?php _e('Sign In','academy'); ?></a>
		<?php if(ThemexFacebook::isActive()) { ?>
		<a href="<?php echo ThemexFacebook::getURL(); ?>" title="<?php _e('Sign in with Facebook','academy'); ?>" class="button facebook-button left">
			<span class="button-icon facebook"></span>
		</a>
		<?php } ?>
		<div class="form-loader"></div>
		<input type="hidden" name="user_action" value="login_user" />
		<input type="hidden" name="user_redirect" value="<?php echo themex_value($_POST, 'user_redirect'); ?>" />
		<input type="hidden" name="nonce" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
		<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
	</form>			
</div>
<div class="clear"></div>
<?php 
$query=new WP_Query(array(
	'post_type' => 'page',
	'meta_key' => '_wp_page_template',
	'meta_value' => 'template-register.php'
));

if($query->have_posts()) {
	$query->the_post();
	echo '<br />';
	the_content();
}
?>
<?php get_footer(); ?>