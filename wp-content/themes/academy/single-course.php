<?php get_header(); ?>
<div class="course-content clearfix popup-container">
	<?php if(!empty(ThemexCourse::$data['questions'])) { ?>
	<div class="sevencol column">
	<?php } else { ?>
	<div class="fullwidth-section">
	<?php } ?>
		<?php if(!empty(ThemexCourse::$data['lessons'])) { ?>
		<h1><?php _e('Lessons', 'academy'); ?></h1>
		<?php if(ThemexCourse::isMember()) { ?>
		<div class="course-progress">
			<span style="width:<?php echo ThemexCourse::$data['progress']; ?>%;"></span>
		</div>
		<?php } ?>
		<div class="lessons-listing">
			<?php foreach(ThemexCourse::$data['lessons'] as $index=>$lesson) { ?>
			<?php get_template_part('content', 'lesson'); ?>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
	<?php if(!empty(ThemexCourse::$data['questions'])) { ?>
	<div class="course-questions fivecol column last">	
		<h1><?php _e('Questions', 'academy'); ?></h1>
		<ul class="styled-list style-2 bordered">
		<?php foreach(ThemexCourse::$data['questions'] as $question) {?>
		<li><a href="<?php echo get_comment_link($question->comment_ID); ?>"><?php echo get_comment_meta($question->comment_ID, 'title', true); ?></a></li>
		<?php } ?>
		</ul>	
	</div>
	<?php } ?>
	<?php if((!ThemexCourse::isSubscriber() || !ThemexCourse::isMember()) && !ThemexCourse::isAuthor()) { ?>
	<div class="popup hidden">
		<?php if(!ThemexCourse::isSubscriber()) { ?>
		<h2 class="popup-text"><?php _e('Subscribe to view this content', 'academy'); ?></h2>
		<?php } else { ?>
		<h2 class="popup-text"><?php _e('Take a course to view this content', 'academy'); ?></h2>
		<?php } ?>
	</div>
	<!-- /popup -->
	<?php } ?>
</div>
<!-- /course content -->
<?php get_template_part('module', 'related'); ?>
<?php get_footer(); ?>