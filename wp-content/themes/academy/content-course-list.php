<article <?php post_class('post clearfix'); ?>>
	<div class="column fivecol post-image">
		<?php get_template_part('content', 'course-grid'); ?>
	</div>
	<div class="course-description column sevencol last">
		<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<?php the_excerpt(); ?>
		<footer class="post-footer">
			<?php get_template_part('module', 'form'); ?>
		</footer>
	</div>
</article>