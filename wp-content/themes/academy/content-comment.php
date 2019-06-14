<li id="comment-<?php echo $comment->comment_ID; ?>">
	<div class="comment hidden-wrap">
		<div class="avatar-container">
			<div class="bordered-image">
				<a href="<?php echo get_author_posts_url($comment->user_id); ?>"><?php echo get_avatar($comment); ?></a>
			</div>										
		</div>
		<div class="comment-text">
			<header class="comment-header hidden-wrap">
				<h5 class="left comment-author"><a href="<?php echo get_author_posts_url($comment->user_id); ?>"><?php comment_author(); ?></a></h5>
				<time class="comment-time left" datetime="<?php comment_time('Y-m-d'); ?>"><?php comment_time(get_option('date_format')); ?></time>
				<?php comment_reply_link(array('depth' => $GLOBALS['depth'], 'max_depth' => 2)); ?>
			</header>
			<?php comment_text(); ?>
		</div>
	</div>