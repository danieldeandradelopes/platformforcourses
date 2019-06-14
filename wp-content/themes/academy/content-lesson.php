<?php ThemexLesson::refresh($GLOBALS['lesson']->ID); ?>
<div class="lesson-item <?php if($GLOBALS['lesson']->post_parent!=0) { ?>toggle-<?php echo $GLOBALS['lesson']->post_parent; ?> lesson-child<?php } ?> <?php if(ThemexCourse::isMember() && ThemexLesson::$data['progress']!=0) { ?>completed<?php } ?>">
	<div class="lesson-title">
		<?php if(ThemexCore::checkOption('lesson_collapse') && ($GLOBALS['lesson']->post_parent!=0 || isset(ThemexCourse::$data['lessons'][$GLOBALS['index']+1]) && ThemexCourse::$data['lessons'][$GLOBALS['index']+1]->post_parent!==0)) { ?>
		<div class="lesson-toggle toggle-element" data-class="toggle-<?php echo $GLOBALS['lesson']->ID; ?>"></div>
		<?php } ?>
		<?php if(ThemexLesson::$data['status']=='free') { ?>
		<div class="course-status"><?php _e('Free', 'academy'); ?></div>
		<?php } ?>
		<h4 class="nomargin"><a href="<?php echo get_permalink(ThemexLesson::$data['ID']); ?>" class="<?php if(ThemexLesson::$data['status']=='free') { ?>disabled<?php } ?>"><?php echo get_the_title(ThemexLesson::$data['ID']); ?></a></h4>
		<?php if(ThemexCourse::isMember() && !empty(ThemexLesson::$data['quiz']) && !empty(ThemexLesson::$data['progress'])) { ?>
		<div class="course-progress">
			<span style="width:<?php echo ThemexLesson::$data['progress']; ?>%;"></span>
		</div>
		<?php } ?>
	</div>
	<?php if(!empty(ThemexLesson::$data['attachments'])) { ?>
	<div class="lesson-attachments">
		<?php foreach(ThemexLesson::$data['attachments'] as $attachment) { ?>
		<a href="<?php echo $attachment['redirect']; ?>" target="_blank" title="<?php echo $attachment['title']; ?>" class="<?php echo $attachment['type']; ?> <?php if(ThemexLesson::$data['status']=='free') { ?>disabled<?php } ?>"></a>
		<?php } ?>
	</div>
	<?php } else { ?>
	<div class="lesson-title"></div>
	<?php } ?>
</div>