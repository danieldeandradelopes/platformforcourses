<?php if(have_comments() || comments_open()) { ?>
<div class="post-comments clearfix" id="comments">
	<h1><?php _e('Comments', 'academy'); ?></h1>
	<?php if(have_comments()) { ?>
	<div class="comments-listing" id="comments">
		<ul>
			<?php
			wp_list_comments(array(
				'per_page' => -1,
				'avatar_size' => 75,
				'type' => 'comment',
				'callback'=>array('ThemexInterface', 'renderComment'),
			));
			?>
		</ul>
	</div>
	<?php } ?>
	<?php if(comments_open()) { ?>
	<div class="comment-form eightcol column last">
		<?php comment_form(); ?>
	</div>
	<?php } ?>
</div>
<?php } ?>