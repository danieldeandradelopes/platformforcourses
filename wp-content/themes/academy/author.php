<?php get_header(); ?>
<div class="sevencol column">
	<div class="user-profile">
		<?php if(ThemexUser::isProfile()) { ?>
		<?php get_template_part('template', 'profile'); ?>
		<?php } else { ?>
		<div class="user-image">
			<div class="bordered-image thick-border">
				<?php echo get_avatar(ThemexUser::$data['active_user']['ID'], 200); ?>
			</div>
			<?php get_template_part('module', 'links'); ?>
		</div>
		<div class="user-description">
			<h1><?php echo ThemexUser::$data['active_user']['profile']['full_name']; ?></h1>
			<div class="signature"><?php echo ThemexUser::$data['active_user']['profile']['signature']; ?></div>
			<?php if(ThemexForm::isActive('profile')) { ?>
			<table class="user-fields">
				<?php 
				ThemexForm::renderData('profile', array(
					'edit' => false,
					'before_title' => '<tr><th>',
					'after_title' => '</th>',
					'before_content' => '<td>',
					'after_content' => '</td></tr>',
				), ThemexUser::$data['active_user']['profile']);
				?>
			</table>
			<?php } ?>
			<?php echo ThemexUser::$data['active_user']['profile']['description']; ?>
		</div>
		<?php } ?>
	</div>
</div>
<?php if(!ThemexCore::checkOption('profile_courses') || ThemexUser::isProfile()) { ?>
<div class="fivecol column last">
	<?php get_sidebar('profile'); ?>
</div>
<?php } ?>
<?php get_footer(); ?>
