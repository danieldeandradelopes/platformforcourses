<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
<?php wp_head(); ?>
</head>
<body <?php body_class('single-certificate'); ?>>
<?php $ID=ThemexCore::getRewriteRule('certificate'); ?>
<?php $certificate=ThemexCourse::getCertificate(themex_decode($ID), themex_decode($ID, true)); ?>
<?php if(isset($certificate['user'])) { ?>
	<div class="certificate-wrap">
		<?php if(!empty($certificate['background'])) { ?>
		<div class="substrate">
			<img src="<?php echo $certificate['background']; ?>" class="fullwidth" alt="" />
		</div>
		<?php } ?>
		<div class="certificate-text">
			<?php echo $certificate['content']; ?>		
		</div>
	</div>
	<?php if($certificate['user']==get_current_user_id()) { ?>
	<a href="#" class="button print-button"><?php _e('Print Certificate', 'academy'); ?></a>
	<?php } ?>
<?php } else { ?>
<div class="certificate-error">
	<h1><?php _e('Certificate not found', 'academy'); ?>.</h1>
</div>
<?php } ?>
<?php wp_footer(); ?>
</body>
</html>