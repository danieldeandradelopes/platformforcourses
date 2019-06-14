<?php 
$courses=ThemexCourse::getCourses(ThemexUser::$data['active_user']['ID']);
$plan=ThemexCourse::getPlan(ThemexUser::$data['active_user']['ID']);

if(ThemexUser::isProfile() && !empty($plan)) {
?>
<h2 class="secondary">
	<?php
	if($plan['period']==0) {
		printf(__('"%s" subscription is active.', 'academy'), '<a href="'.$plan['url'].'">'.get_the_title($plan['ID']).'</a>');
	} else {
		printf(__('"%s" subscription expires in %s.', 'academy'), '<a href="'.$plan['url'].'">'.get_the_title($plan['ID']).'</a>', themex_time($plan['time'])); 
	}
	?>
</h2>
<?php } ?>
<?php if(empty($courses)) { ?>
<h2 class="secondary"><?php _e('No courses yet.', 'academy'); ?></h2>
<?php } else { ?>
<div class="user-courses-listing">
<?php foreach($courses as $ID) { ?>
	<?php ThemexCourse::refresh($ID); ?>
	<div class="course-item <?php if(ThemexCourse::$data['progress']!=100){ ?>started<?php } ?>">
		<div class="course-title">
			<?php if(ThemexCourse::$data['author']['ID']==ThemexUser::$data['active_user']['ID']) { ?>
			<div class="course-status"><?php _e('Author', 'academy'); ?></div>
			<?php } ?>
			<h4 class="nomargin"><a href="<?php echo get_permalink($ID); ?>"><?php echo get_the_title($ID); ?></a></h4>
			<?php if(!in_array(ThemexCourse::$data['progress'], array(0, 100))) { ?>
			<div class="course-progress">
				<span style="width:<?php echo ThemexCourse::$data['progress']; ?>%;"></span>
			</div>
			<?php } ?>
		</div>
		<?php if(!ThemexCore::checkOption('course_rating')) { ?>
		<div class="course-meta">
			<?php get_template_part('module', 'rating'); ?>
		</div>
		<?php } ?>
	</div>
<?php } ?>
</div>
<?php } ?>