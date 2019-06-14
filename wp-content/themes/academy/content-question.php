<li id="comment-<?php echo $comment->comment_ID; ?>">
	<?php if($comment->comment_parent==0) { ?>
	<div class="question-title toggle-title">
		<div class="question-replies"><?php echo get_comments(array('parent' => $comment->comment_ID, 'count' => true)); ?></div>
		<h4 class="nomargin"><?php echo get_comment_meta($comment->comment_ID, 'title', true); ?></h4>
	</div>
	<?php } ?>
	<div class="question-content toggle-content">
		<div class="avatar-container">
			<div class="bordered-image">
				<a href="<?php echo get_author_posts_url($comment->user_id); ?>"><?php echo get_avatar($comment); ?></a>
			</div>
		</div>
		<div class="question-text">
			<header class="question-header hidden-wrap">
				<h5 class="left question-author"><a href="<?php echo get_author_posts_url($comment->user_id); ?>"><?php comment_author(); ?></a></h5>
				<time class="question-time left" datetime="<?php comment_time('Y-m-d'); ?>"><?php comment_time(get_option('date_format')); ?></time>
				<?php comment_reply_link(array('depth' => $GLOBALS['depth'], 'max_depth' => 2)); ?>
			</header>
			<?php comment_text(); ?>
		</div>
	</div>