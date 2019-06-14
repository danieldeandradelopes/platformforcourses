<?php
if (!defined('ABSPATH')) {
	exit;
}

get_header('shop'); 
?>
<div class="woocommerce">
	<?php do_action( 'woocommerce_archive_description' ); ?>
	<?php if ( have_posts() ) : ?>
		<?php do_action( 'woocommerce_before_shop_loop' ); ?>
		<?php woocommerce_product_loop_start(); ?>
			<?php woocommerce_product_subcategories(); ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php woocommerce_get_template_part( 'content', 'product' ); ?>
			<?php endwhile; ?>
		<?php woocommerce_product_loop_end(); ?>
		<?php do_action( 'woocommerce_after_shop_loop' ); ?>
	<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
		<?php woocommerce_get_template( 'loop/no-products-found.php' ); ?>
	<?php endif; ?>
</div>
<!-- /woocommerce -->
<?php get_footer('shop'); ?>