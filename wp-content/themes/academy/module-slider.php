<?php
$type=ThemexCore::getOption('slider_type', 'parallax');
$pause=ThemexCore::getOption('slider_pause', '0');
$speed=ThemexCore::getOption('slider_speed', '1000');

$query=new WP_Query(array(
	'post_type' =>'slide',
	'showposts' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC',
));

if($query->have_posts()) {
if($type=='boxed') {
?>
<div class="row">
	<div class="boxed-slider themex-slider">			
		<ul>
		<?php while($query->have_posts()) { ?>
			<?php	
			$query->the_post(); 
			$video=ThemexCore::getPostMeta($post->ID, 'slide_video'); 
			$link=ThemexCore::getPostMeta($post->ID, 'slide_link');
			?>
			<li>
			<?php if(!empty($video)) { ?>
				<div class="embedded-video">
					<?php echo themex_html($video); ?>
				</div>
			<?php } else { ?>
				<?php if(!empty($link)) { ?>
				<a href="<?php echo $link; ?>"><?php the_post_thumbnail('large'); ?></a>
				<?php } else { ?>
				<?php the_post_thumbnail('large'); ?>
				<?php } ?>
				<div class="caption">
					<?php the_content(); ?>
				</div>
			<?php } ?>
			</li>	
		<?php } ?>
		</ul>
		<?php if($query->post_count>1) { ?>
		<div class="arrow arrow-left"></div>
		<div class="arrow arrow-right"></div>
		<?php } ?>
		<input type="hidden" class="slider-pause" value="<?php echo $pause; ?>" />
		<input type="hidden" class="slider-speed" value="<?php echo $speed; ?>" />
		<input type="hidden" class="slider-effect" value="fade" />
	</div>	
</div>
<?php } else { ?>
<div class="parallax-slider themex-slider">
	<?php if($type=='parallax') { ?>
	<div class="substrate">
		<?php ThemexStyle::renderBackground(); ?>
	</div>
	<?php } ?>
	<ul>
	<?php while($query->have_posts()) { ?>
		<?php $query->the_post(); ?>
		<li>
			<div class="row"><?php the_content(); ?></div>
		</li>
	<?php } ?>
	</ul>
	<?php if($query->post_count>1) { ?>
	<div class="arrow arrow-left"></div>
	<div class="arrow arrow-right"></div>
	<?php } ?>
	<input type="hidden" class="slider-pause" value="<?php echo $pause; ?>" />
	<input type="hidden" class="slider-speed" value="<?php echo $speed; ?>" />
	<input type="hidden" class="slider-effect" value="slide" />	
</div>
<?php } ?>
<?php } ?>