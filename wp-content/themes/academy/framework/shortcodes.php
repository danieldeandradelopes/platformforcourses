<?php
//Columns
add_shortcode('one_sixth', 'themex_one_sixth');
function themex_one_sixth($atts, $content = null) {
   return '<div class="twocol column">'.do_shortcode($content).'</div>';
}

add_shortcode('one_sixth_last', 'themex_one_sixth_last');
function themex_one_sixth_last($atts, $content = null) {
   return '<div class="twocol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('one_fourth', 'themex_one_fourth');
function themex_one_fourth($atts, $content = null) {
   return '<div class="threecol column">'.do_shortcode($content).'</div>';
}

add_shortcode('one_fourth_last', 'themex_one_fourth_last');
function themex_one_fourth_last($atts, $content = null) {
   return '<div class="threecol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('one_third', 'themex_one_third');
function themex_one_third($atts, $content = null) {
   return '<div class="fourcol column">'.do_shortcode($content).'</div>';
}

add_shortcode('one_third_last', 'themex_one_third_last');
function themex_one_third_last($atts, $content = null) {
   return '<div class="fourcol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('five_twelfths', 'themex_five_twelfths');
function themex_five_twelfths($atts, $content = null) {
   return '<div class="fivecol column">'.do_shortcode($content).'</div>';
}

add_shortcode('five_twelfths_last', 'themex_five_twelfths_last');
function themex_five_twelfths_last($atts, $content = null) {
   return '<div class="fivecol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('one_half', 'themex_one_half');
function themex_one_half($atts, $content = null) {
   return '<div class="sixcol column">'.do_shortcode($content).'</div>';
}

add_shortcode('one_half_last', 'themex_one_half_last');
function themex_one_half_last($atts, $content = null) {
   return '<div class="sixcol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('seven_twelfths', 'themex_seven_twelfths');
function themex_seven_twelfths($atts, $content = null) {
   return '<div class="sevencol column">'.do_shortcode($content).'</div>';
}

add_shortcode('seven_twelfths_last', 'themex_seven_twelfths_last');
function themex_seven_twelfths_last($atts, $content = null) {
   return '<div class="sevencol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('two_thirds', 'themex_two_thirds');
function themex_two_thirds($atts, $content = null) {
   return '<div class="eightcol column">'.do_shortcode($content).'</div>';
}

add_shortcode('two_thirds_last', 'themex_two_thirds_last');
function themex_two_thirds_last($atts, $content = null) {
   return '<div class="eightcol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

add_shortcode('three_fourths', 'themex_three_fourths');
function themex_three_fourths($atts, $content = null) {
   return '<div class="ninecol column">'.do_shortcode($content).'</div>';
}

add_shortcode('three_fourths_last', 'themex_three_fourths_last');
function themex_three_fourths_last($atts, $content = null) {
   return '<div class="ninecol column last">'.do_shortcode($content).'</div><div class="clear"></div>';
}

//Button
add_shortcode('button','themex_button');
function themex_button($atts, $content=null) {	
	extract(shortcode_atts(array(
		'url'     	 => '#',
		'target' => 'self',
		'color'   => 'primary',
		'size'	=> '',
    ), $atts));
	
	$out='<a href="'.$url.'" target="_'.$target.'" class="button '.$size.' '.$color.'">'.do_shortcode($content).'</a>';
	
	return $out;
}

//Contact Form
add_shortcode('contact_form', 'themex_contact_form');
function themex_contact_form($atts, $content=null) {
	$out='<form action="'.AJAX_URL.'" method="POST" class="formatted-form ajax-form">';
	$out.='<div class="message"></div>';
	
	ob_start();
	ThemexForm::renderData('contact');
	$out.=ob_get_contents();
	ob_end_clean();	
	
	$out.='<div class="clear"></div><a class="submit-button button" href="#">'.__('Submit', 'academy').'</a>';
	$out.='<div class="form-loader"></div>';
	$out.='<input type="hidden" name="slug" value="contact" />';
	$out.='<input type="hidden" class="action" value="'.THEMEX_PREFIX.'form_submit" /></form>';	
	
	return $out;
}

//Content
add_shortcode('content', 'themex_content');
function themex_content($atts, $content=null) {
	extract(shortcode_atts(array(
		'type' => 'public',
    ), $atts));
	
	$out='';	
	if(isset(ThemexCourse::$data)) {
		if(($type=='private' && ThemexCourse::isMember()) || ($type=='public' && !ThemexCourse::isMember())) {
			$out.=do_shortcode($content);
		}
	} else if(($type=='private' && is_user_logged_in()) || ($type=='public' && !is_user_logged_in())) {
		$out.=do_shortcode($content);
	}
	
    return $out;
}

//Courses
add_shortcode('courses', 'themex_courses');
function themex_courses($atts, $content=null) {
	extract(shortcode_atts(array(
		'number' => '4',
		'columns' => '4',
		'order' => 'date',
		'category' => '0',
		'status' => '',
		'id' => '0',
    ), $atts));
	
	if($order=='random') {
		$order='rand';
	}
	
	$width='three';
	switch($columns) {
		case '1': $width='twelve'; break;
		case '2': $width='six'; break;
		case '3': $width='four'; break;
	}	
	
	$columns=intval($columns);
	$counter=0;
	
	$args=array(
		'post_type' => 'course',
		'showposts' => $number,	
		'orderby' => $order,
		'order' => 'DESC',
		'meta_query' => array(
			array(
				'key' => '_thumbnail_id',
			),
		),
	);
	
	if($id!=0) {
		$args['post__in']=array($id);
		$width='twelve';
		$columns=1;
	}
	
	if(!empty($status)) {
		$args['meta_query'][]=array(
            'key' => '_course_status',
			'value' => $status,
        );
	}
	
	if(!empty($category)) {
		$args['tax_query'][]=array(
            'taxonomy' => 'course_category',
            'terms' => $category,
            'field' => 'term_id',
        );
	}
		
	if (in_array($order, array('rating', 'popularity'))) {
		$args['orderby']='meta_value_num';
		$args['meta_key']='_course_'.$order;
	} else if ($order=='title') {
		$args['order']='ASC';
	}
	
	$query=new WP_Query($args);

	$out='<div class="courses-listing clearfix">';
	while($query->have_posts()){
		$query->the_post();	
		$counter++;
		
		$class='';
		if($counter==$columns) {
			$class='last';
		}
		
		$out.='<div class="column '.$width.'col '.$class.'">';		
		ob_start();
		get_template_part('content', 'course-grid');
		$out.=ob_get_contents();
		ob_end_clean();		
		$out.='</div>';
		
		if($counter==$columns) {
			echo '<div class="clear"></div>';
			$counter=0;						
		}
	}
	$out.='</div><div class="clear"></div>';
	
	wp_reset_query();
	return $out;
}

//Google Map
add_shortcode('map', 'themex_google_map');
function themex_google_map($atts, $content=null) {
    extract(shortcode_atts(array(
		'latitude' => '40.714',
		'longitude' => '-74',
		'zoom' => '16',
		'height' => '165',
		'description' => '',
    ), $atts));
	
	wp_enqueue_script('google-map', 'http://maps.google.com/maps/api/js?sensor=false');
	
	$out='<div class="google-map-container"><div class="google-map" id="google-map" style="height:'.$height.'px"></div><input type="hidden" class="map-latitude" value="'.$latitude.'" />';
	$out.='<input type="hidden" class="map-longitude" value="'.$longitude.'" /><input type="hidden" class="map-zoom" value="'.$zoom.'" /><input type="hidden" class="map-description" value="'.$description.'" /></div>';
   
    return $out;
}

//Image
add_shortcode('image', 'themex_image');
function themex_image($atts, $content=null) {
	extract(shortcode_atts(array(
		'url' => '',
    ), $atts));
	
	$out='';
	if(!empty($content)) {
		$out.='<img src="'.urldecode($content).'" alt="" />';
		
		if($url!='') {
			$out='<a href="'.$url.'">'.$out.'</a>';
		}
		
		$out='<div class="bordered-image thick-border inner-image">'.$out.'</div>';
	}
	
	return $out;
}

//Plan
add_shortcode('plan', 'themex_plan');
function themex_plan($atts, $content=null) {
	extract(shortcode_atts(array(
		'id' => '0',
    ), $atts));
	
	$query = new WP_Query(array(
		'post_type' => 'plan',
		'showposts' => 1,
		'post__in' => array(intval($id)),
	));

	$out='';
	while($query->have_posts()){
		$query->the_post();	
		ob_start();
		get_template_part('content', 'plan');
		$out.=ob_get_contents();
		ob_end_clean();
	}
	
	wp_reset_query();
	return $out;
}

//Player
add_shortcode('player', 'themex_player');
function themex_player($atts, $content=null) {
	extract(shortcode_atts(array(
		'url' => '',
    ), $atts));
	
	$GLOBALS['file']=array();
	$GLOBALS['file']['title']=$content;
	$GLOBALS['file']['url']=explode(',', $url);
	$GLOBALS['file']['type']='video';
	
	if(pathinfo($GLOBALS['file']['url'][0], PATHINFO_EXTENSION)=='mp3') {
		$GLOBALS['file']['type']='audio';
	}
	
	ob_start();
	get_template_part('module', 'player');
	$out=ob_get_contents();
	ob_end_clean();	
	
	return $out;
}

//Posts
add_shortcode('posts', 'themex_posts');
function themex_posts($atts, $content=null) {
	extract(shortcode_atts(array(
		'number' => '1',
		'order' => 'date',
		'category' => '0',
    ), $atts));
	
	if($order=='random') {
		$order='rand';
	}
	
	$args= array(
		'post_type' => 'post',
		'showposts' => intval($number),	
		'orderby' => $order,		
	);
	
	if(intval($category)!=0) {
		$args['category__in']=array(intval($category));
	}
	
	$query = new WP_Query($args);
	
	$out='<div class="posts-listing">';
	while($query->have_posts()){
		$query->the_post();	
		
		ob_start();
		the_excerpt();
		$GLOBALS['content']=ob_get_contents();
		ob_end_clean();
		
		$GLOBALS['content']=themex_sections($GLOBALS['content'], 1);
		$GLOBALS['content']=do_shortcode($GLOBALS['content']);
		
		ob_start();
		get_template_part('content', 'post');
		$out.=ob_get_contents();
		ob_end_clean();
	
	}
	$out.='</div>';	

	wp_reset_query();
	return $out;
}

//Section
add_shortcode('section', 'themex_section');
function themex_section($atts, $content=null) {
	extract(shortcode_atts(array(
		'title' => '',
    ), $atts));
	
	$out='<div class="widget">';
	$out.='<div class="widget-title"><h3 class="nomargin">'.$title.'</h3></div>';
	$out.='<div class="widget-content">'.do_shortcode($content).'</div></div>';
	
    return $out;
}

//Slider
add_shortcode('slider', 'themex_slider');
function themex_slider($atts, $content=null) {
	extract(shortcode_atts(array(
		'pause' => '0',
		'speed' => '400',
    ), $atts));

    $out='<div class="boxed-slider themex-slider"><ul>'.do_shortcode($content).'</ul>';
	$out.='<input type="hidden" class="slider-pause" value="'.intval($pause).'" />';
	$out.='<input type="hidden" class="slider-speed" value="'.intval($speed).'" />';
	$out.='<div class="arrow arrow-left"></div><div class="arrow arrow-right"></div></div>';
	
    return $out;
}

add_shortcode('slide', 'themex_slide');
function themex_slide($atts, $content=null) {
	extract(shortcode_atts(array(
		'url' => '',
    ), $atts));
	
	$out='';
	if($url!='') {
		$out='<li><img src="'.$url.'" alt="" />';
		
		if($content!='') {
			$out.='<div class="caption">'.do_shortcode($content).'</div>';			
		}
		
		$out.='</li>';
	}
	
    return $out;
}

//Testimonials
add_shortcode('testimonials', 'themex_testimonials');
function themex_testimonials($atts, $content=null) {
	extract(shortcode_atts(array(
		'number' => '4',
		'order' => 'date',
		'category' => '0',
    ), $atts));
	
	if($order=='random') {
		$order='rand';
	}
	
	$args=array(
		'post_type' => 'testimonial',
		'showposts' => $number,
		'orderby' => $order,
	);
	
	if(!empty($category)) {
		$args['tax_query'][]=array(
            'taxonomy' => 'testimonial_category',
            'terms' => $category,
            'field' => 'term_id',
        );
	}
		
	$query=new WP_Query($args);
	
	$out='<div class="testimonials">';
	while($query->have_posts()){
		$query->the_post();
		
		ob_start();
		get_template_part('content', 'testimonial');
		$out.=ob_get_contents();
		ob_end_clean();
	}
	$out.='</div>';
	
	wp_reset_query();
	return $out;
}

//Tabs
add_shortcode('tabs', 'themex_tabs');
function themex_tabs($atts, $content=null) {
	extract(shortcode_atts(array(
		'type' => 'horizontal',
		'titles' => '',
    ), $atts));
	
	$out='<div class="tabs-container '.$type.'-tabs clearfix">';
	
	if($type=='vertical') {
		$out.='<div class="column threecol tabs"><ul>';
	} else {
		$out.='<ul class="tabs clearfix">';
	}	
	
	$tabs=explode('][', $content);
	$upgrade=true;
	if(!empty($titles)) {
		$tabs=explode(',', $titles);
		$upgrade=false;
	}

	foreach($tabs as $tab) {
		$title='';
		if($upgrade) {			
			preg_match('/tab\s{1,}title=\"(.*)\"/', $tab, $matches);			
			if(isset($matches[1])) {
				$title=$matches[1];
			}
		} else {
			$title=$tab;
		}
				
		if(!empty($title)) {
			$out.='<li><h5 class="nomargin"><a href="#'.themex_sanitize_key($title).'">'.$title.'</a></h5></li>';
		}
	}
	
	if($type=='vertical') {
		$out.='</ul></div><div class="panes column ninecol last">';
	} else {
		$out.='</ul><div class="panes">';
	}

	$out.=do_shortcode($content);
    $out.= '</div></div>';
	
    return $out;
}

add_shortcode('tab', 'themex_tabs_panes');
function themex_tabs_panes($atts, $content=null) {
	extract(shortcode_atts(array(
		'title' => '',
    ), $atts));
	
	$out='<div class="pane" id="'.themex_sanitize_key($title).'-tab">'.do_shortcode($content).'</div>';	
    return $out;
}

//Toggle
add_shortcode('toggle', 'themex_toggle');
function themex_toggle($atts, $content=null) {
    extract(shortcode_atts(array(
		'title' => '',
    ), $atts));
	
	$out='<div class="toggle-container faq-toggle">';
	$out.='<div class="toggle-title"><h4 class="nomargin">'.$title.'</h4></div>';
	$out.='<div class="toggle-content"><p>'.do_shortcode($content).'</p></div></div>';
	
	return $out;
}

add_shortcode('toggles', 'themex_toggles');
function themex_toggles($atts, $content=null) {
	extract(shortcode_atts(array(
		'type' => 'multiple',
    ), $atts));
	
	$out='<div class="toggles-wrap '.$type.'">'.do_shortcode($content).'</div>';	
    return $out;
}

//Users
add_shortcode('users','themex_users');
function themex_users( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'number' => '3',
		'order' => 'date',
		'role' => '',
		'id' => '',
    ), $atts));
	
	$orderby='registered';
	$orderdir='ASC';
	switch($order) {
		case 'activity':
			$orderby='post_count';
			$orderdir='DESC';
		break;
		
		case 'name':
			$orderby='display_name';
		break;
		
		case 'date':
			$orderby='registered';
			$orderdir='DESC';
		break;
	}
	
	$args=array(
		'number' => intval($number),
		'orderby' => $orderby,
		'order' => $orderdir,
	);
	
	if(!empty($id)) {
		$args['include']=explode(',', $id);		
	}
	
	if(!empty($role)) {
		$args['role']=$role;
	}
	
	$users=ThemexCourse::getAuthors($args);
	
	$out='<div class="experts">';
	foreach($users as $user) {
		$GLOBALS['user']=$user;
		
		ob_start();
		get_template_part('content', 'profile');
		$out.=ob_get_contents();
		ob_end_clean();
	}
	$out.='</div>';
	
	return $out;
}