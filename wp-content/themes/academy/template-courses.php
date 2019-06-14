<?php
/*
Template Name: Courses
*/

get_header();

$layout=ThemexCore::getOption('courses_layout', 'fullwidth');
$view=ThemexCore::getOption('courses_view', 'grid');
$columns=intval(ThemexCore::getOption('courses_columns', '4'));

if($layout=='left') {
?>
<aside class="sidebar fourcol column">
	<?php get_sidebar(); ?>
</aside>
<div class="eightcol column last">
<?php } else if($layout=='right') { ?>
<div class="eightcol column">
<?php } else { ?>
<div class="fullwidth-section">
<?php } ?>
	<?php echo category_description(); ?>
	<?php ThemexCourse::queryCourses(); ?>
	<?php if($view=='list') { ?>
	<div class="posts-listing clearfix">
	<?php
	while (have_posts()) {
		the_post();
		get_template_part('content', 'course-list');
	}
	?>
	</div>
	<?php } else { ?>
	<div class="courses-listing clearfix">
	<?php
	$counter=0;
	if(in_array($layout, array('left', 'right'))) {
		$columns=$columns-1;
	}
	
	if($columns==4) {
		$width='three';
	} else if($columns==3) {
		$width='four';
	} else {
		$width='six';
	}
		
	while (have_posts()) {
		the_post();
		$counter++;
		?>
		<div class="column <?php echo $width; ?>col <?php echo $counter==$columns ? 'last':''; ?>">
		<?php get_template_part('content', 'course-grid'); ?>
		</div>
		<?php
		if($counter==$columns) {
			$counter=0;
			echo '<div class="clear"></div>';
		}
	}
	?>
	</div>
	<?php } ?>
	<?php ThemexInterface::renderPagination(); ?>
</div>
<?php if($layout=='right') { ?>
<aside class="sidebar fourcol column last">
	<?php get_sidebar(); ?>
</aside>
<?php } ?>
<?php get_footer(); ?>