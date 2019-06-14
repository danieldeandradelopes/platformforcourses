<?php
if (!defined('ABSPATH')) {
	exit;
}

global $woocommerce;
?>
<div class="fourcol column">
	<?php $woocommerce->show_messages(); ?>
	<form action="<?php echo esc_url( get_permalink(woocommerce_get_page_id('change_password')) ); ?>" method="post">
		<p class="form-row">
			<input type="password" class="input-text" name="password_1" id="password_1" placeholder="<?php _e( 'New password', 'academy' ); ?>" />
		</p>
		<p class="form-row">
			<input type="password" class="input-text" name="password_2" id="password_2" placeholder="<?php _e( 'Re-enter new password', 'academy' ); ?>" />
		</p>
		<div class="clear"></div>
		<p><input type="submit" class="button" name="change_password" value="<?php _e( 'Save', 'academy' ); ?>" /></p>
		<?php $woocommerce->nonce_field('change_password')?>
		<input type="hidden" name="action" value="change_password" />
	</form>
</div>