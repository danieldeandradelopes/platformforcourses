<?php
if (!defined('ABSPATH')) {
	exit;
}

if($order) {
$related=ThemexWoo::getRelatedPost($order->id, array('course_product', 'plan_product'), true);

if(!empty($related)) {
$query=new WP_Query(array(
	'post__in' => array($related->ID),
	'post_type' => $related->post_type,
));
?>
<div class="column threecol">
	<?php
	$query->the_post();	
	if($related->post_type=='course') {
		get_template_part('content', 'course-grid');
	} else {
		get_template_part('content', 'plan');
	}
	?>
</div>
<div class="column fivecol nomargin">
	<?php if(in_array($order->status, array('failed'))) : ?>
		<h3><?php _e('Failure in Processing the Payment.', 'academy'); ?></h3>
		<p><?php _e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'academy'); ?></p>
		<p><?php echo __('Please attempt your purchase', 'academy').' '.esc_url( $order->get_checkout_payment_url() ).' '.__('again or go to your account page.', 'academy'); ?></p>
	<?php else : ?>
		<h3><?php _e('Thank you. Your order has been received.', 'academy'); ?></h3>		
	<?php endif; ?>
	<ul class="order_details">
		<li class="order">
			<strong><?php _e('Order:', 'academy'); ?></strong>
			<?php echo $order->get_order_number(); ?>
		</li>
		<li class="order">
			<strong><?php _e('Email:', 'academy'); ?></strong>
			<?php echo $order->billing_email; ?>
		</li>
		<li class="date">
			<strong><?php _e('Date:', 'academy'); ?></strong>
			<?php echo date_i18n(get_option('date_format'), strtotime($order->order_date)); ?>
		</li>
		<li class="total">
			<strong><?php _e('Total:', 'academy'); ?></strong>
			<?php echo $order->get_formatted_order_total(); ?>
		</li>
		<?php if ($order->payment_method_title) : ?>
		<li class="method">
			<strong><?php _e('Payment method:', 'academy'); ?></strong>
			<?php echo $order->payment_method_title; ?>
		</li>
		<?php endif; ?>
	</ul>
	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
</div>
<div class="column fourcol last"></div>
<?php
	} else if(file_exists(ABSPATH.'wp-content/plugins/woocommerce/templates/checkout/thankyou.php')) {
		include(ABSPATH.'wp-content/plugins/woocommerce/templates/checkout/thankyou.php');
	}
}
?>