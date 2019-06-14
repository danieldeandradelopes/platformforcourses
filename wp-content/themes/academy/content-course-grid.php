<?php ThemexCourse::refresh($post->ID); ?>
<div class="course-preview <?php echo ThemexCourse::$data['status']; ?>-course">
	<div class="course-image">
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('normal'); ?></a>
		<?php if(empty(ThemexCourse::$data['plans']) && ThemexCourse::$data['status']!='private') { ?>
		<div class="course-price product-price">
			<div class="price-text"><?php echo ThemexCourse::$data['price']['text']; ?></div>
			<div class="corner-wrap">
				<div class="corner"></div>
				<div class="corner-background"></div>
			</div>			
		</div>
		<?php } ?>
	</div>
	<div class="course-meta">
		<header class="course-header">
			<h5 class="nomargin"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
			<?php if(!ThemexCore::checkOption('course_author')) { ?>
			<a href="<?php echo ThemexCourse::$data['author']['profile_url']; ?>" class="author"><?php echo ThemexCourse::$data['author']['profile']['full_name']; ?></a>
			<?php } ?>
		</header>
		<?php if(!ThemexCore::checkOption('course_popularity') || !ThemexCore::checkOption('course_rating')) { ?>
		<footer class="course-footer clearfix">
			<?php if(!ThemexCore::checkOption('course_popularity')) { ?>
			<div class="course-users left">
				<?php echo ThemexCore::getPostMeta($post->ID, 'course_popularity', '0'); ?>
			</div>
			<?php } ?>
			<?php if(!ThemexCore::checkOption('course_rating')) { ?>
			<?php get_template_part('module', 'rating'); ?>
			<?php } ?>
		</footer>
		<?php } ?>
	</div>
</div>