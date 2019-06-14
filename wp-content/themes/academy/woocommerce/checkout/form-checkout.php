<?php
if (!defined('ABSPATH')) {
	exit;
}

global $woocommerce;
$product=reset($woocommerce->cart->get_cart());
$related=ThemexWoo::getRelatedPost($product['product_id'], array('course_product', 'plan_product'), true);

if(!empty($related)) {
$get_checkout_url = apply_filters('woocommerce_get_checkout_url', $woocommerce->cart->get_checkout_url());
wc_print_notices();
do_action('woocommerce_before_checkout_form', $checkout);

$query=new WP_Query(array(
	'post__in' => array($related->ID),
	'post_type' => $related->post_type,
));
?>
<form name="checkout" method="post" class="checkout course-checkout" action="<?php echo esc_url($get_checkout_url); ?>">
	<div class="threecol column">
		<?php
		$query->the_post();		
		if($related->post_type=='course') {
			get_template_part('content', 'course-grid');
		} else {
			get_template_part('content', 'plan');
		}
		?>
	</div>
	<?php if(sizeof($checkout->checkout_fields ) > 0) : ?>
	<div class="column fourcol">
		<h3><?php _e('Billing Details', 'academy'); ?></h3>
		<div class="billing-details">
			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
			<?php do_action( 'woocommerce_checkout_billing' ); ?>
			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
			<?php do_action('woocommerce_before_order_notes', $checkout); ?>
			<?php do_action('woocommerce_after_order_notes', $checkout); ?>
			<?php if (woocommerce_get_page_id('terms')>0) : ?>
			<p class="form-row terms">
				<input type="checkbox" class="input-checkbox" name="terms" <?php if (isset($_POST['terms'])) echo 'checked="checked"'; ?> id="terms" />
				<label for="terms" class="checkbox"><?php _e('I accept the', 'academy'); ?> <a href="<?php echo esc_url( get_permalink(woocommerce_get_page_id('terms')) ); ?>" target="_blank"><?php _e('terms &amp; conditions', 'academy'); ?></a></label>
			</p>
			<?php endif; ?>
			<input id="shiptobilling-checkbox" type="hidden" name="shiptobilling" value="1" />
		</div>
	</div>
	<?php endif; ?>
	<div class="fivecol column last">
		<?php do_action('woocommerce_checkout_order_review'); ?>
	</div>
	<div class="clear"></div>
</form>
<?php
do_action('woocommerce_after_checkout_form');
} else if(file_exists(ABSPATH.'wp-content/plugins/woocommerce/templates/checkout/form-checkout.php')) {
	include(ABSPATH.'wp-content/plugins/woocommerce/templates/checkout/form-checkout.php');
}
?>