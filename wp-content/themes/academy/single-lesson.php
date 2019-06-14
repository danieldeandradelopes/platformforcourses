<?php
get_header();

the_post();
ThemexLesson::refresh($post->ID, true);
ThemexCourse::refresh(ThemexLesson::$data['course'], true);
$layout=ThemexCore::getOption('lessons_layout', 'right');

if($layout=='left') {
?>
<aside class="sidebar column fourcol">
	<?php get_sidebar('lesson'); ?>
</aside>
<div class="column eightcol last">
<?php } else { ?>
<div class="column eightcol">
<?php } ?>
	<h1><?php the_title(); ?></h1>
	<?php 
	if(ThemexLesson::$data['prerequisite']['progress']==0 && ThemexLesson::$data['status']!='free' && ThemexCore::checkOption('lesson_hide') && !ThemexCourse::isAuthor()) {
		printf(__('Complete "%s" lesson before taking this lesson.', 'academy'), '<a href="'.get_permalink(ThemexLesson::$data['prerequisite']['ID']).'">'.get_the_title(ThemexLesson::$data['prerequisite']['ID']).'</a>');
	} else {
		the_content();
		comments_template('/questions.php');
	}
	?>
</div>
<?php if($layout=='right') { ?>
<aside class="sidebar fourcol column last">
	<?php get_sidebar('lesson'); ?>
</aside>
<?php } ?>
<?php get_footer(); ?>