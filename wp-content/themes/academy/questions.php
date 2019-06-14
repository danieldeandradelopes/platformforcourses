<?php 
$questions=ThemexLesson::hasQuestions($post->ID);
if($questions || comments_open()) { 
?>
<div class="questions clearfix" id="comments">
	<h1><?php _e('Questions', 'academy'); ?></h1>
	<?php if($questions) { ?>
	<div class="questions-listing toggles-wrap">
		<ul>
			<?php
			wp_list_comments(array(
				'per_page' => -1,
				'avatar_size' => 75,
				'type' => 'comment',
				'callback' => array('ThemexLesson', 'renderQuestion'),
			)); 
			?>
		</ul>
	</div>
	<?php } ?>
	<?php if(comments_open() && (ThemexCourse::isMember() || ThemexCourse::isAuthor())) { ?>
	<div class="question-form eightcol column last">
		<?php comment_form(); ?>
	</div>
	<?php } ?>
</div>
<!-- questions -->
<?php } ?>