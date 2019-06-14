<?php the_post(); ?>
<?php ThemexCourse::refresh($post->ID, true); ?>
<div class="threecol column">
<?php get_template_part('content', 'course-grid'); ?>
</div>
<?php if(ThemexCourse::hasMembers() || is_active_sidebar('course') || !empty(ThemexCourse::$data['sidebar'])) { ?>
<div class="sixcol column">
<?php } else { ?>
<div class="ninecol column last">
<?php } ?>
	<div class="course-description widget <?php echo ThemexCourse::$data['status']; ?>-course">
		<div class="widget-title">
			<h4 class="nomargin"><?php _e('Description', 'academy'); ?></h4>
		</div>
		<div class="widget-content">
			<?php the_content(); ?>
			<footer class="course-footer">
				<?php get_template_part('module', 'form'); ?>
			</footer>
		</div>						
	</div>
</div>
<?php if(ThemexCourse::hasMembers() || is_active_sidebar('course') || !empty(ThemexCourse::$data['sidebar'])) { ?>
<aside class="sidebar threecol column last">
	<?php
	echo do_shortcode(themex_html(ThemexCourse::$data['sidebar']));
	
	if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('course'));
	
	if(ThemexCourse::hasMembers()) {
		get_template_part('module', 'users');
	}
	?>
</aside>
<?php } ?>