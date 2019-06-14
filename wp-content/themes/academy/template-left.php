<?php
/*
Template Name: Left Sidebar
*/
?>
<?php get_header(); ?>
<aside class="sidebar column fourcol">
<?php get_sidebar(); ?>
</aside>
<div class="column eightcol last">
<?php the_post(); ?>
<?php the_content(); ?>
</div>
<?php get_footer(); ?>