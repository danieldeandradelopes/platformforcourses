<?php
if (!defined('ABSPATH')) {
	exit;
}

global $wp_query;
if ($wp_query->max_num_pages<=1) {
	return;
}
?>
<nav class="pagination">
	<?php
		echo paginate_links(apply_filters('woocommerce_pagination_args', array(
			'base' 			=> str_replace(999999999, '%#%', get_pagenum_link(999999999)),
			'format' 		=> '',
			'current' 		=> max(1, get_query_var('paged')),
			'total' 		=> $wp_query->max_num_pages,
			'prev_text' 	=> '',
			'next_text' 	=> '',
			'type'			=> 'plain',
			'end_size'		=> 3,
			'mid_size'		=> 3
		)));
	?>
</nav>