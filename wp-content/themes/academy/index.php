<?php 
if(get_query_var('course_category')) {
	get_template_part('template', 'courses');
} else {
	get_template_part('template', 'posts');
}
?>