<?php
if (!defined('ABSPATH')) {
	exit;
}

global $woocommerce, $post;
?>
<div class="fourcol column">
	<?php $woocommerce->show_messages(); ?>
	<form action="<?php echo esc_url( get_permalink($post->ID) ); ?>" method="post" class="lost_reset_password">
		<?php	if( 'lost_password' == $args['form'] ) : ?>
		<p><?php echo apply_filters( 'woocommerce_lost_password_message', __( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'academy' ) ); ?></p>
		<p class="form-row form-row-first">
			<input class="input-text" type="text" name="user_login" id="user_login" placeholder="<?php _e( 'Username or email', 'academy' ); ?>" />
		</p>
		<?php else : ?>
		<p><?php echo apply_filters( 'woocommerce_reset_password_message', __( 'Enter a new password below.', 'academy') ); ?></p>
		<p class="form-row form-row-first">
			<input type="password" class="input-text" name="password_1" id="password_1" placeholder="<?php _e( 'New password', 'academy' ); ?>" />
		</p>
		<p class="form-row form-row-last">
			<input type="password" class="input-text" name="password_2" id="password_2" placeholder="<?php _e( 'Re-enter new password', 'academy' ); ?>" />
		</p>
		<input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>" />
		<input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>" />
		<?php endif; ?>
		<div class="clear"></div>
		<p class="form-row"><input type="submit" class="button" name="reset" value="<?php echo 'lost_password' == $args['form'] ? __( 'Reset Password', 'academy' ) : __( 'Save', 'academy' ); ?>" /></p>
		<?php $woocommerce->nonce_field( $args['form'] ); ?>
	</form>
</div>