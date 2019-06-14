<?php
//Theme Configuration
$config = array (
	
	//Theme Modules
	'modules' => array(
		'ThemexInterface',
		'ThemexShortcode',
		'ThemexSidebar',
		'ThemexForm',
		'ThemexStyle',
		'ThemexUser',
		'ThemexCourse',
		'ThemexLesson',
		'ThemexFacebook',
		'ThemexWoo',
	),
	
	//Theme Components
	'components' => array(
	
		//Supports
		'supports' => array (
			'automatic-feed-links',
			'post-thumbnails',
			'woocommerce',
		),
		
		//Rewrite Rules
		'rewrite_rules' => array (
			'profile' => array(
				'name' => 'profile',
				'rule' => 'author_base',
				'rewrite' => 'profile',
				'position' => 'top',
				'replace' => true,
				'dynamic' => true,
			),
			
			'register' => array(
				'title' => __('Registration', 'academy'),
				'name' => 'register',
				'rule' => 'register/?',
				'rewrite' => 'index.php?register=1',
				'position' => 'top',
				'authorized' => false,
			),
			
			'file' => array(
				'name' => 'file',
				'rule' => 'file/([^/]+)',
				'rewrite' => 'index.php?file=$matches[1]',
				'position' => 'top',
				'dynamic' => true,
				'authorized' => true,
			),
			
			'certificate' => array(
				'name' => 'certificate',
				'title' => __('Certificate', 'academy'),
				'rule' => 'certificate/([^/]+)',
				'rewrite' => 'index.php?certificate=$matches[1]',
				'position' => 'top',
				'dynamic' => true,
			),
			
			'redirect' => array(
				'name' => 'redirect',
				'rule' => 'redirect/([^/]+)',
				'rewrite' => 'index.php?redirect=$matches[1]',
				'position' => 'top',
				'dynamic' => true,
			),
		),
	
		//User Roles
		'user_roles' => array (
			array(
				'role' => 'inactive',
				'name' => __('Inactive', 'academy'),
				'capabilities' => array(),
			),
		),
		
		//Custom Menus
		'custom_menus' => array (
			array(
				'slug' => 'main_menu',
				'name' => __('Main Menu', 'academy'),
			),
			
			array(
				'slug' => 'footer_menu',
				'name' => __('Footer Menu', 'academy'),
			),
		),
		
		//Image Sizes
		'image_sizes' => array (
		
			array(
				'name' => 'normal',
				'width' => 420,
				'height' => 420,
				'crop' => false,
			),
			
			array(
				'name' => 'extended',
				'width' => 738,
				'height' => 738,
				'crop' => false,
			),			
		),
		
		//Editor styles
		'editor_styles' => array(
			'bordered'=>__('Bordered List', 'academy'),
			'checked'=>__('Checked List', 'academy'),
		),
		
		//Admin Styles
		'admin_styles' => array(
			
			//colorpicker
			array(
				'name' => 'wp-color-picker',
			),
			
			//thickbox
			array(	
				'name' => 'thickbox',
			),
			
			//interface
			array(	
				'name' => 'themex-style',
				'uri' => THEMEX_URI.'assets/css/style.css'
			),			
		),
		
		//Admin Scripts
		'admin_scripts' => array(
			
			//colorpicker
			array(
				'name' => 'wp-color-picker',
			),
			
			//thickbox
			array(	
				'name' => 'thickbox',
			),
			
			//uploader
			array(	
				'name' => 'media-upload',
			),
			
			//slider
			array(	
				'name' => 'jquery-ui-slider',
			),
			
			//popup
			array(
				'name' => 'themex-popup',
				'uri' => THEMEX_URI.'assets/js/themex.popup.js',
			),
			
			//interface
			array(
				'name' => 'themex-interface',
				'uri' => THEMEX_URI.'assets/js/themex.interface.js',
			),
		),
		
		//User Styles
		'user_styles' => array(
		
			//general
			array(	
				'name' => 'general',
				'uri' => CHILD_URI.'style.css',				
			),

		),
		
		//User Scripts
		'user_scripts' => array(
			
			//jquery
			array(	
				'name' => 'jquery',
			),
			
			//comment reply
			array(	
				'name' => 'comment-reply',
			),
			
			//hover intent
			array(	
				'name' => 'hover-intent',
				'uri' => THEME_URI.'js/jquery.hoverIntent.min.js',
			),
			
			//placeholder
			array(	
				'name' => 'placeholder',
				'uri' => THEME_URI.'js/jquery.placeholder.min.js',
			),
			
			//player
			array(	
				'name' => 'jplayer',
				'uri' => THEME_URI.'js/jplayer/jquery.jplayer.min.js',
			),
			
			//slider
			array(	
				'name' => 'themex-slider',
				'uri' => THEME_URI.'js/jquery.themexSlider.js',
			),
			
			//raty
			array(	
				'name' => 'raty',
				'uri' => THEME_URI.'js/jquery.raty.min.js',
				'options' => array(
					'templateDirectory' => THEME_URI,
				),
			),

			//general
			array(
				'name' => 'general',
				'uri' => THEME_URI.'js/general.js',
				'options' => array(
					'templateDirectory' => THEME_URI,
				),
			),
		),
		
		//Widget Settings
		'widget_settings' => array (
			'before_widget' => '<div class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-title"><h3 class="nomargin">',
			'after_title' => '</h3></div>',
		),
		
		//Widget Areas
		'widget_areas' => array (
			array(
				'id' => 'course',
				'name' => __('Course', 'academy'),
				'before_widget' => '<div class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<div class="widget-title"><h3 class="nomargin">',
				'after_title' => '</h3></div>',				
			),
			
			array(
				'id' => 'lesson',
				'name' => __('Lesson', 'academy'),
				'before_widget' => '<div class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<div class="widget-title"><h3 class="nomargin">',
				'after_title' => '</h3></div>',				
			),
			
			array(
				'id' => 'footer',
				'name' => __('Footer', 'academy'),
				'before_widget' => '<div class="fourcol column"><div class="widget %2$s">',
				'after_widget' => '</div></div>',
				'before_title' => '<div class="widget-title"><h3 class="nomargin">',
				'after_title' => '</h3></div>',				
			),
		),
		
		//Widgets
		'widgets' => array (
			'ThemexAuthors',
			'ThemexTwitter',
			'ThemexComments',
			'WP_Widget_Search',
			'WP_Widget_Recent_Comments',
		),
		
		//Post Types
		'post_types' => array (
		
			//Plan
			array (
				'id' => 'plan',
				'labels' => array (
					'name' => __('Plans', 'academy'),
					'singular_name' => __( 'Plan', 'academy' ),
					'add_new' => __('Add New', 'academy'),
					'add_new_item' => __('Add New Plan', 'academy'),
					'edit_item' => __('Edit Plan', 'academy'),
					'new_item' => __('New Plan', 'academy'),
					'view_item' => __('View Plan', 'academy'),
					'search_items' => __('Search Plans', 'academy'),
					'not_found' =>  __('No Plans Found', 'academy'),
					'not_found_in_trash' => __('No Plans Found in Trash', 'academy'),
				),
				'public' => true,
				'exclude_from_search' => true,
				'capability_type' => 'post',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'menu_position' => null,
				'show_in_menu' => 'edit.php?post_type=course',
				'supports' => array('title', 'editor', 'page-attributes'),
				'rewrite' => array('slug' => __('plan', 'academy')),
			),
		
			//Course
			array (
				'id' => 'course',
				'labels' => array (
					'name' => __('Courses', 'academy'),
					'singular_name' => __( 'Course', 'academy' ),
					'add_new' => __('Add New', 'academy'),
					'add_new_item' => __('Add New Course', 'academy'),
					'edit_item' => __('Edit Course', 'academy'),
					'new_item' => __('New Course', 'academy'),
					'view_item' => __('View Course', 'academy'),
					'search_items' => __('Search Courses', 'academy'),
					'not_found' =>  __('No Courses Found', 'academy'),
					'not_found_in_trash' => __('No Courses Found in Trash', 'academy'),
				 ),
				'public' => true,
				'exclude_from_search' => false,
				'capability_type' => 'post',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions'),
				'rewrite' => array('slug' => __('course', 'academy')),
			),
			
			//Lesson
			array (
				'id' => 'lesson',
				'labels' => array (
					'name' => __('Lessons', 'academy'),
					'singular_name' => __( 'Lesson', 'academy' ),
					'add_new' => __('Add New', 'academy'),
					'add_new_item' => __('Add New Lesson', 'academy'),
					'edit_item' => __('Edit Lesson', 'academy'),
					'new_item' => __('New Lesson', 'academy'),
					'view_item' => __('View Lesson', 'academy'),
					'search_items' => __('Search Lessons', 'academy'),
					'not_found' =>  __('No Lessons Found', 'academy'),
					'not_found_in_trash' => __('No Lessons Found in Trash', 'academy'),
				 ),
				'public' => true,
				'exclude_from_search' => true,
				'capability_type' => 'post',
				'map_meta_cap' => true,
				'hierarchical' => true,
				'menu_position' => null,
				'supports' => array('title', 'editor', 'author', 'revisions', 'comments', 'page-attributes'),
				'rewrite' => array('slug' => __('lesson', 'academy')),
			),
			
			//Quiz
			array (
				'id' => 'quiz',
				'labels' => array (
					'name' => __('Quizzes', 'academy'),
					'singular_name' => __( 'Quiz', 'academy' ),
					'add_new' => __('Add New', 'academy'),
					'add_new_item' => __('Add New Quiz', 'academy'),
					'edit_item' => __('Edit Quiz', 'academy'),
					'new_item' => __('New Quiz', 'academy'),
					'view_item' => __('View Quiz', 'academy'),
					'search_items' => __('Search Quizzes', 'academy'),
					'not_found' =>  __('No Quizzes Found', 'academy'),
					'not_found_in_trash' => __('No Quizzes Found in Trash', 'academy'),
				 ),
				'public' => true,
				'exclude_from_search' => true,
				'capability_type' => 'post',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'show_in_menu' => 'edit.php?post_type=lesson',
				'menu_position' => null,
				'supports' => array('title', 'editor', 'author'),
				'rewrite' => array('slug' => __('quiz', 'academy')),
			),
			
			//Testimonial
			array (
				'id' => 'testimonial',
				'labels' => array (
					'name' => __('Testimonials', 'academy'),
					'singular_name' => __( 'Testimonial', 'academy' ),
					'add_new' => __('Add New', 'academy'),
					'add_new_item' => __('Add New Testimonial', 'academy'),
					'edit_item' => __('Edit Testimonial', 'academy'),
					'new_item' => __('New Testimonial', 'academy'),
					'view_item' => __('View Testimonial', 'academy'),
					'search_items' => __('Search Testimonials', 'academy'),
					'not_found' =>  __('No Testimonials Found', 'academy'),
					'not_found_in_trash' => __('No Testimonials Found in Trash', 'academy'),
				 ),
				'public' => true,
				'exclude_from_search' => true,
				'capability_type' => 'post',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array('title', 'editor', 'thumbnail'),
				'rewrite' => array('slug' => __('testimonial', 'academy')),
			),
			
			//Slide
			array (
				'id' => 'slide',
				'labels' => array (
					'name' => __('Slides', 'academy'),
					'singular_name' => __( 'Slide', 'academy' ),
					'add_new' => __('Add New', 'academy'),
					'add_new_item' => __('Add New Slide', 'academy'),
					'edit_item' => __('Edit Slide', 'academy'),
					'new_item' => __('New Slide', 'academy'),
					'view_item' => __('View Slide', 'academy'),
					'search_items' => __('Search Slides', 'academy'),
					'not_found' =>  __('No Slides Found', 'academy'),
					'not_found_in_trash' => __('No Slides Found in Trash', 'academy'),
				 ),
				'public' => true,
				'exclude_from_search' => true,
				'capability_type' => 'post',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
				'rewrite' => array('slug' => __('slide', 'academy')),
			),
		),
		
		//Taxonomies
		'taxonomies' => array (
		
			//Course Category
			array(
				'taxonomy' => 'course_category',
				'object_type' => array('course'),
				'settings' => array(
					'hierarchical' => true,
					'show_in_nav_menus' => true,			
					'labels' => array(
	                    'name' => __( 'Course Categories', 'academy'),
	                    'singular_name' => __( 'Course Category', 'academy'),
						'menu_name' => __( 'Categories', 'academy' ),
	                    'search_items' => __( 'Search Course Categories', 'academy'),
	                    'all_items' => __( 'All Course Categories', 'academy'),
	                    'parent_item' => __( 'Parent Course Category', 'academy'),
	                    'parent_item_colon' => __( 'Parent Course Category', 'academy'),
	                    'edit_item' => __( 'Edit Course Category', 'academy'),
	                    'update_item' => __( 'Update Course Category', 'academy'),
	                    'add_new_item' => __( 'Add New Course Category', 'academy'),
	                    'new_item_name' => __( 'New Course Category Name', 'academy'),
	            	),
					'rewrite' => array(
						'slug' => __('courses', 'academy'),
						'hierarchical' => true,
					),
				),
			),
			
			//Testimonial Category
			array(
				'taxonomy' => 'testimonial_category',
				'object_type' => array('testimonial'),
				'settings' => array(
					'hierarchical' => true,
					'show_in_nav_menus' => true,			
					'labels' => array(
	                    'name' => __( 'Testimonial Categories', 'academy'),
	                    'singular_name' => __( 'Testimonial Category', 'academy'),
						'menu_name' => __( 'Categories', 'academy' ),
	                    'search_items' => __( 'Search Testimonial Categories', 'academy'),
	                    'all_items' => __( 'All Testimonial Categories', 'academy'),
	                    'parent_item' => __( 'Parent Testimonial Category', 'academy'),
	                    'parent_item_colon' => __( 'Parent Testimonial Category', 'academy'),
	                    'edit_item' => __( 'Edit Testimonial Category', 'academy'),
	                    'update_item' => __( 'Update Testimonial Category', 'academy'),
	                    'add_new_item' => __( 'Add New Testimonial Category', 'academy'),
	                    'new_item_name' => __( 'New Testimonial Category Name', 'academy'),
	            	),
					'rewrite' => array(
						'slug' => __('testimonials', 'academy'),
						'hierarchical' => true,
					),
				),
			),
		),
		
		//Meta Boxes
		'meta_boxes' => array(
		
			//Page
			array(
				'id' => 'page_metabox',
				'title' =>  __('Page Options', 'academy'),
				'page' => 'page',
				'context' => 'normal',
				'priority' => 'high',
				'options' => array(
					array(	
						'name' => __('Background', 'academy'),
						'id' => 'background',
						'type' => 'uploader',
						'description' => __('Choose background image from WordPress media library', 'academy'),
					),
				),
			),
		
			//Course
			array(
				'id' => 'course_metabox',
				'title' =>  __('Course Options', 'academy'),
				'page' => 'course',
				'context' => 'normal',
				'priority' => 'high',
				'options' => array(
					array(
						'name' => __('Status', 'academy'),
						'id' => 'status',
						'type' => 'select',
						'options' => array(							
							'premium' => __('Premium', 'academy'),
							'private' => __('Private', 'academy'),
							'free' => __('Free', 'academy'),
						),
					),
					
					array(
						'name' => __('Product', 'academy'),
						'id' => 'product',
						'type' => 'select_post',
						'post_type' => 'product',
						'description' => __('Choose WooCommerce product to set the course price', 'academy'),
					),
					
					array(
						'name' => __('Rating', 'academy'),
						'id' => 'rating',
						'type' => 'text',
					),
					
					array(
						'name' => __('Capacity', 'academy'),
						'id' => 'capacity',
						'type' => 'number',
						'description' => __('Set maximum number of students for this course', 'academy'),
					),
					
					array(
						'name' => __('Students', 'academy'),
						'id' => 'users',
						'type' => 'users',
					),
				
					array(	
						'name' => __('Background', 'academy'),
						'id' => 'background',
						'type' => 'uploader',
						'description' => __('Choose background image from WordPress media library', 'academy'),
					),
				),			
			),
			
			//Course Certificate
			array(
				'id' => 'certificate_metabox',
				'title' =>  __('Course Certificate', 'academy'),
				'page' => 'course',
				'context' => 'normal',
				'priority' => 'high',
				'options' => array(					
					array(	
						'name' => __('Content', 'academy'),
						'id' => 'certificate_content',
						'type' => 'textarea',
						'description' => __('Add certificate content, you can use %username%, %title%, %date% and %grade% keywords', 'academy'),
					),
					
					array(	
						'name' => __('Background', 'academy'),
						'id' => 'certificate_background',
						'type' => 'uploader',
						'description' => __('Choose background image from WordPress media library', 'academy'),
					),
				),
			),
			
			//Course Sidebar
			array(
				'id' => 'course_sidebar_metabox',
				'title' =>  __('Course Sidebar', 'academy'),
				'page' => 'course',
				'context' => 'normal',
				'priority' => 'high',
				'options' => array(
					array(	
						'name' => __('Content', 'academy'),
						'id' => 'sidebar',
						'type' => 'textarea',
						'description' => __('Add course sidebar content, use [section] shortcode for widgets', 'academy'),
					),
				),
			),
			
			//Plan
			array(
				'id' => 'plan_metabox',
				'title' =>  __('Plan Options', 'academy'),
				'page' => 'plan',
				'context' => 'normal',
				'priority' => 'high',
				'options' => array(
					array(	
						'name' => __('Category', 'academy'),
						'id' => 'category',
						'type' => 'select_category',
						'taxonomy' => 'course_category',
						'description' => __('Choose course category to add courses to the plan', 'academy'),
					),
					
					array(
						'name' => __('Product', 'academy'),
						'id' => 'product',
						'type' => 'select_post',
						'post_type' => 'product',
						'description' => __('Choose WooCommerce product to set the plan price', 'academy'),
					),
					
					array(
						'name' => __('Period', 'academy'),
						'id' => 'period',
						'type' => 'select',
						'options' => array(
							'0' => __('None', 'academy'),
							'7' => __('Week', 'academy'),
							'31' => __('Month', 'academy'),
							'93' => __('3 Months', 'academy'),
							'186' => __('6 Months', 'academy'),
							'279' => __('9 Months', 'academy'),
							'365' => __('Year', 'academy'),
						),
					),
					
					array(
						'name' => __('Students', 'academy'),
						'id' => 'users',
						'type' => 'users',
					),
				),			
			),
			
			//Lesson
			array(
				'id' => 'lesson_metabox',
				'title' =>  __('Lesson Options', 'academy'),
				'page' => 'lesson',
				'context' => 'normal',
				'priority' => 'high',
				'options' => array(
					array(
						'name' => __('Status', 'academy'),
						'id' => 'status',
						'type' => 'select',
						'options' => array(					
							'premium' => __('Premium', 'academy'),
							'free' => __('Free', 'academy'),
						),
					),
					
					array(
						'name' => __('Course', 'academy'),
						'id' => 'course',
						'type' => 'select_post',
						'post_type' => 'course',
					),
					
					array(
						'name' => __('Prerequisite', 'academy'),
						'id' => 'lesson',
						'type' => 'select_post',
						'post_type' => 'lesson',
					),
					
					array(
						'name' => __('Delay', 'academy'),
						'id' => 'delay',
						'type' => 'number',
						'description' => __('Set delay in days for this lesson', 'academy'),
					),
					
					array(
						'name' => __('Attachments', 'academy'),
						'id' => 'attachments',
						'type' => 'attachments',
					),
				),	
			),
			
			//Lesson Sidebar
			array(
				'id' => 'lesson_sidebar_metabox',
				'title' =>  __('Lesson Sidebar', 'academy'),
				'page' => 'lesson',
				'context' => 'normal',
				'priority' => 'high',
				'options' => array(
					array(	
						'name' => __('Content', 'academy'),
						'id' => 'sidebar',
						'type' => 'textarea',
						'description' => __('Add lesson sidebar content, use [section] shortcode for widgets', 'academy'),
					),
				),
			),
			
			//Quiz
			array(
				'id' => 'quiz_metabox',
				'title' =>  __('Quiz Options', 'academy'),
				'page' => 'quiz',
				'context' => 'normal',
				'priority' => 'high',
				'options' => array(
					array(
						'name' => __('Lesson', 'academy'),
						'id' => 'lesson',
						'type' => 'select_post',
						'post_type' => 'lesson',
					),
					
					array(
						'name' => __('Percentage', 'academy'),
						'id' => 'percentage',
						'type' => 'number',
						'description' => __('Set percentage of right answers required to pass this quiz', 'academy'),
					),
					
					array(
						'name' => __('Questions', 'academy'),
						'id' => 'questions',
						'type' => 'questions',
					),
				),
			),
			
			//Slide
			array(
				'id' => 'slide_metabox',
				'title' =>  __('Slide Options', 'academy'),
				'page' => 'slide',
				'context' => 'normal',
				'priority' => 'high',
				'options' => array(
					array(	
						'name' => __('Link', 'academy'),
						'id' => 'link',
						'type' => 'text',
						'description' => __('Enter URL for the slide image link', 'academy'),
					),
					
					array(	
						'name' => __('Video', 'academy'),
						'id' => 'video',
						'type' => 'textarea',
						'description' => __('Enter embedded video code to replace the slide image', 'academy'),
					),
				),			
			),
		),
		
		//Shortcodes
		'shortcodes' => array(
		
			//Button
			array(
				'id' => 'button',
				'name' => __('Button', 'academy'),
				'shortcode' => '[button color="{{color}}" size="{{size}}" url="{{url}}" target="{{target}}"]{{content}}[/button]',
				'options' => array(
					array(			
						'id' => 'color',
						'name' => __('Color', 'academy'),						
						'type' => 'select',
						'options' => array(
							'primary' => __('Primary', 'academy'),
							'secondary' => __('Secondary', 'academy'),
							'dark' => __('Dark', 'academy'),
						),
					),
				
					array(			
						'id' => 'size',
						'name' => __('Size', 'academy'),						
						'type' => 'select',
						'options' => array(
							'small' => __('Small', 'academy'),
							'medium' => __('Medium', 'academy'),
							'large' => __('Large', 'academy'),
						),
					),
					
					array(		
						'id' => 'url',
						'name' => __('Link', 'academy'),			
						'type' => 'text',
					),
					
					array(			
						'id' => 'target',
						'name' => __('Target', 'academy'),			
						'type' => 'select',
						'options' => array(
							'self' => __('Current Tab', 'academy'),
							'blank' => __('New Tab', 'academy'),
						),
					),
					
					array(
						'id' => 'content',
						'name' => __('Caption', 'academy'),						
						'type' => 'text',
					),
				),
			),
		
			//Columns
			array(
				'id' => 'column',
				'name' => __('Columns', 'academy'),
				'shortcode' => '{{clone}}',
				'clone' => array(
					'shortcode' => '[{{column}}]{{content}}[/{{column}}]',
					'options' => array(
						array(
							'id' => 'column',
							'name' => __('Width', 'academy'),
							'type' => 'select',
							'options' => array(
								'one_sixth' => __('One Sixth', 'academy'),
								'one_sixth_last' => __('One Sixth Last', 'academy'),
								'one_fourth' => __('One Fourth', 'academy'),
								'one_fourth_last' => __('One Fourth Last', 'academy'),
								'one_third' => __('One Third', 'academy'),
								'one_third_last' => __('One Third Last', 'academy'),
								'five_twelfths' => __('Five Twelfths', 'academy'),
								'five_twelfths_last' => __('Five Twelfths Last', 'academy'),
								'one_half' => __('One Half', 'academy'),
								'one_half_last' => __('One Half Last', 'academy'),
								'seven_twelfths' => __('Seven Twelfths', 'academy'),
								'seven_twelfths_last' => __('Seven Twelfths Last', 'academy'),
								'two_thirds' => __('Two Thirds', 'academy'),
								'two_thirds_last' => __('Two Thirds Last', 'academy'),
								'three_fourths' => __('Three Fourths', 'academy'),
								'three_fourths_last' => __('Three Fourths Last', 'academy'),
							),
						),
						
						array(					
							'id' => 'content',
							'name' => __('Content', 'academy'),						
							'type' => 'textarea',
						),
					),
				),
			),
			
			//Courses
			array(
				'id' => 'courses',
				'name' => __('Courses', 'academy'),
				'shortcode' => '[courses number="{{number}}" columns="{{columns}}" order="{{order}}" category="{{category}}"]',
				'options' => array(
					array(
						'id' => 'number',
						'name' => __('Number', 'academy'),
						'value' => '4',
						'type' => 'number',
					),

					array(
						'id' => 'columns',
						'name' => __('Columns', 'academy'),
						'value' => '4',
						'type' => 'select',
						'options' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
						),
					),
					
					array(			
						'id' => 'order',
						'name' => __('Order', 'academy'),			
						'type' => 'select',
						'options' => array(
							'date' => __('Date', 'academy'),
							'rating' => __('Rating', 'academy'),
							'popularity' => __('Popularity', 'academy'),
							'random' => __('Random', 'academy'),
						),
					),
					
					array(
						'id' => 'category',
						'name' => __('Category', 'academy'),			
						'type' => 'select_category',
						'taxonomy' => 'course_category',
					),
				),
			),
			
			//Content
			array(
				'id' => 'content',
				'name' => __('Content', 'academy'),
				'shortcode' => '[content type="{{type}}"]{{content}}[/content]',
				'options' => array(
					array(
						'id' => 'type',
						'name' => __('Type', 'academy'),			
						'type' => 'select',
						'options' => array(
							'public' => __('Public', 'academy'),
							'private' => __('Private', 'academy'),
						),
					),
					
					array(
						'id' => 'content',
						'name' => __('Content', 'academy'),						
						'type' => 'textarea',
					),
				),
			),
			
			//Contact Form
			array(
				'id' => 'contact_form',
				'name' => __('Contact Form', 'academy'),
				'shortcode' => '[contact_form]',
				'options' => array(
		
				),
			),
			
			//Google Map
			array(
				'id' => 'map',
				'name' => __('Google Map', 'academy'),
				'shortcode' => '[map height="{{height}}" latitude="{{latitude}}" longitude="{{longitude}}" zoom="{{zoom}}" description="{{description}}"]',
				'options' => array(
					array(
						'id' => 'height',
						'name' => __('Height', 'academy'),
						'value' => '250',
						'type' => 'number',
					),

					array(
						'id' => 'latitude',
						'name' => __('Latitude', 'academy'),
						'value' => '0',
						'type' => 'text',
					),
					
					array(
						'id' => 'longitude',
						'name' => __('Longitude', 'academy'),
						'value' => '0',
						'type' => 'text',
					),
					
					array(
						'id' => 'zoom',
						'name' => __('Zoom', 'academy'),
						'value' => '10',
						'type' => 'number',
					),
					
					array(					
						'id' => 'description',
						'name' => __('Description', 'academy'),							
						'type' => 'textarea',						
					),
				),
			),
			
			//Image
			array(
				'id' => 'image',
				'name' => __('Image', 'academy'),
				'shortcode' => '[image url="{{url}}"]{{content}}[/image]',
				'options' => array(
					array(
						'id' => 'content',
						'name' => __('Image', 'academy'),						
						'type' => 'text',
					),	
					
					array(			
						'id' => 'url',
						'name' => __('Link', 'academy'),						
						'type' => 'text',
					),		
				),
			),
			
			//Plan
			array(
				'id' => 'plan',
				'name' => __('Plan', 'academy'),
				'shortcode' => '[plan id="{{plan}}"]',
				'options' => array(
					array(
						'id' => 'plan',
						'name' => __('Plan', 'academy'),
						'type' => 'select_post',
						'post_type' => 'plan',
					),
				),
			),
			
			//Player
			array(
				'id' => 'player',
				'name' => __('Player', 'academy'),
				'shortcode' => '[player url="{{url}}"]{{content}}[/player]',
				'options' => array(
					array(
						'id' => 'url',
						'name' => __('File URL', 'academy'),
						'value' => '',
						'type' => 'text',
					),
					
					array(
						'id' => 'content',
						'name' => __('File Title', 'academy'),
						'value' => '',
						'type' => 'text',
					),
				),
			),
			
			//Posts
			array(
				'id' => 'posts',
				'name' => __('Posts', 'academy'),
				'shortcode' => '[posts number="{{number}}" order="{{order}}" category="{{category}}"]',
				'options' => array(
					array(
						'id' => 'number',
						'name' => __('Number', 'academy'),
						'value' => '1',
						'type' => 'number',
					),		
					
					array(
						'id' => 'order',
						'name' => __('Order', 'academy'),			
						'type' => 'select',
						'options' => array(
							'date' => __('Date', 'academy'),
							'random' => __('Random', 'academy'),
						),
					),
					
					array(			
						'id' => 'category',
						'name' => __('Category', 'academy'),			
						'type' => 'select_category',
						'taxonomy' => 'category',
					),
				),
			),
			
			//Section
			array(
				'id' => 'section',
				'name' => __('Section', 'academy'),
				'shortcode' => '[section title="{{title}}"]{{content}}[/section]',
				'options' => array(
					array(			
						'id' => 'title',
						'name' => __('Title', 'academy'),						
						'type' => 'text',
					),				
			
					array(
						'id' => 'content',
						'name' => __('Content', 'academy'),						
						'type' => 'textarea',
					),				
				),
			),
			
			//Slider
			array(
				'id' => 'slider',
				'name' => __('Slider', 'academy'),
				'shortcode' => '[slider pause="{{pause}}" speed="{{speed}}"]{{clone}}[/slider]',
				'options' => array(
					array(
						'id' => 'pause',
						'name' => __('Pause', 'academy'),
						'type' => 'number',
						'value' => '0',
					),
					
					array(
						'id' => 'speed',
						'name' => __('Speed', 'academy'),
						'type' => 'number',
						'value' => '400',
					),
				),
				'clone' => array(
					'shortcode' => '[slide url="{{url}}"]{{content}}[/slide]',
					'options' => array(
						array(
							'id' => 'url',
							'name' => __('Image', 'academy'),						
							'type' => 'text',
						),
						
						array(
							'id' => 'content',
							'name' => __('Caption', 'academy'),							
							'type' => 'textarea',						
						),
					),
				),
			),
			
			//Tabs
			array(
				'id' => 'tabs',
				'name' => __('Tabs', 'academy'),
				'shortcode' => '[tabs type="{{type}}"]{{clone}}[/tabs]',
				'options' => array(
					array(			
						'id' => 'type',
						'name' => __('Type', 'academy'),			
						'type' => 'select',
						'options' => array(
							'horizontal' => __('Horizontal', 'academy'),
							'vertical' => __('Vertical', 'academy'),
						),
					),
				),
				'clone' => array(
					'shortcode' => '[tab title="{{title}}"]{{content}}[/tab]',
					'options' => array(
						array(
							'id' => 'title',
							'name' => __('Title', 'academy'),
							'type' => 'text',
						),
						
						array(					
							'id' => 'content',
							'name' => __('Content', 'academy'),							
							'type' => 'textarea',						
						),
					),
				),
			),
			
			//Testimonials
			array(
				'id' => 'testimonials',
				'name' => __('Testimonials', 'academy'),
				'shortcode' => '[testimonials number="{{number}}" order="{{order}}" category="{{category}}"]',
				'options' => array(
					array(
						'id' => 'number',
						'name' => __('Number', 'academy'),
						'value' => '3',
						'type' => 'number',
					),		
					
					array(		
						'id' => 'order',
						'name' => __('Order', 'academy'),			
						'type' => 'select',
						'options' => array(
							'date' => __('Date', 'academy'),
							'random' => __('Random', 'academy'),
						),
					),
					
					array(			
						'id' => 'category',
						'name' => __('Category', 'academy'),		
						'type' => 'select_category',
						'taxonomy' => 'testimonial_category',
					),
				),
			),
			
			//Toggles
			array(
				'id' => 'toggles',
				'name' => __('Toggles', 'academy'),
				'shortcode' => '[toggles type="{{type}}"]{{clone}}[/toggles]',
				'options' => array(
					array(
						'id' => 'type',
						'name' => __('Type', 'academy'),
						'type' => 'select',
						'options' => array(
							'multiple' => __('Multiple', 'academy'),
							'accordion' => __('Accordion', 'academy'),
						),
					),			
				),
				'clone' => array(
					'shortcode' => '[toggle title="{{title}}"]{{content}}[/toggle]',
					'options' => array(
						array(
							'id' => 'title',
							'name' => __('Title', 'academy'),
							'type' => 'text',
						),		
						
						array(
							'id' => 'content',
							'name' => __('Content', 'academy'),							
							'type' => 'textarea',					
						),
					),
				),
			),

			//Users
			array(
				'id' => 'users',
				'name' => __('Users', 'academy'),
				'shortcode' => '[users number="{{number}}" order="{{order}}"]',
				'options' => array(
					array(
						'id' => 'number',
						'name' => __('Number', 'academy'),
						'value' => '3',
						'type' => 'number',
					),		
					
					array(		
						'id' => 'order',
						'name' => __('Order', 'academy'),			
						'type' => 'select',
						'options' => array(
							'date' => __('Date', 'academy'),
							'name' => __('Name', 'academy'),
							'activity' => __('Activity', 'academy'),
						),
					),			
				),
			),
		),
		
		//Custom Styles
		'custom_styles' => array(
			array(
				'elements' => '.featured-content',
				'attributes' => array(
					array(
						'name' => 'background-image',
						'option' => 'background_image',
					),
				),
			),
			
			array(
				'elements' => 'body, input, select, textarea',
				'attributes' => array(
					array(
						'name' => 'font-family',
						'option' => 'content_font',
					),
				),
			),
			
			array(
				'elements' => 'h1,h2,h3,h4,h5,h6, .header-navigation div > ul > li > a',
				'attributes' => array(
					array(
						'name' => 'font-family',
						'option' => 'heading_font',
					),
				),
			),
			
			array(
				'elements' => 'input[type="submit"], input[type="button"], .button, .jp-play-bar, .jp-volume-bar-value, .free-course .course-price .price-text, .lessons-listing .lesson-attachments a, ul.styled-list.style-4 li:before, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover',
				'attributes' => array(
					array(
						'name' => 'background-color',
						'option' => 'primary_color',
					),
				),
			),
			
			array(
				'elements' => '.free-course .course-price .corner',
				'attributes' => array(
					array(
						'name' => 'border-top-color',
						'option' => 'primary_color',
					),
					
					array(
						'name' => 'border-right-color',
						'option' => 'primary_color',
					),
				),
			),
			
			array(
				'elements' => '.button.secondary, .quiz-listing .question-number, .lessons-listing .lesson-title .course-status, .course-price .price-text, .course-price .corner, .course-progress span, .questions-listing .question-replies, .course-price .corner-background, .user-links a:hover, .payment-listing .expanded .toggle-title:before, .styled-list.style-5 li:before, .faq-toggle .toggle-title:before, .lesson-toggle, ul.styled-list.style-1 li:before, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover',
				'attributes' => array(
					array(
						'name' => 'background-color',
						'option' => 'secondary_color',
					),
				),
			),
			
			array(
				'elements' => 'a, a:hover, a:focus, ul.styled-list li > a:hover',
				'attributes' => array(
					array(
						'name' => 'color',
						'option' => 'secondary_color',
					),
				),
			),
			
			array(
				'elements' => '.button.dark, .jp-gui, .jp-controls a, .jp-video-play-icon, .header-wrap, .header-navigation ul ul, .select-menu, .search-form, .mobile-search-form, .login-button .tooltip-text, .footer-wrap, .site-footer:after, .site-header:after, .widget-title',
				'attributes' => array(
					array(
						'name' => 'background-color',
						'option' => 'background_color',
					),
				),
			),
			
			array(
				'elements' => '.jp-jplayer',
				'attributes' => array(
					array(
						'name' => 'border-color',
						'option' => 'background_color',
					),
				),
			),
			
			array(
				'elements' => '.widget-title',
				'attributes' => array(
					array(
						'name' => 'border-bottom-color',
						'option' => 'background_color',
					),
				),
			),
			
			array(
				'elements' => '::-moz-selection',
				'attributes' => array(
					array(
						'name' => 'background-color',
						'option' => 'primary_color',
					),
				),
			),
			
			array(
				'elements' => '::selection',
				'attributes' => array(
					array(
						'name' => 'background-color',
						'option' => 'primary_color',
					),
				),
			),
		),
		
		//Fonts
		'fonts' => array(
			'ABeeZee' => 'ABeeZee',
			'Abel' => 'Abel',
			'Abril Fatface' => 'Abril Fatface',
			'Aclonica' => 'Aclonica',
			'Acme' => 'Acme',
			'Actor' => 'Actor',
			'Adamina' => 'Adamina',
			'Advent Pro' => 'Advent Pro',
			'Aguafina Script' => 'Aguafina Script',
			'Aladin' => 'Aladin',
			'Aldrich' => 'Aldrich',
			'Alegreya' => 'Alegreya',
			'Alegreya SC' => 'Alegreya SC',
			'Alex Brush' => 'Alex Brush',
			'Alfa Slab One' => 'Alfa Slab One',
			'Alice' => 'Alice',
			'Alike' => 'Alike',
			'Alike Angular' => 'Alike Angular',
			'Allan' => 'Allan',
			'Allerta' => 'Allerta',
			'Allerta Stencil' => 'Allerta Stencil',
			'Allura' => 'Allura',
			'Almendra' => 'Almendra',
			'Almendra SC' => 'Almendra SC',
			'Amaranth' => 'Amaranth',
			'Amatic SC' => 'Amatic SC',
			'Amethysta' => 'Amethysta',
			'Andada' => 'Andada',
			'Andika' => 'Andika',
			'Angkor' => 'Angkor',
			'Annie Use Your Telescope' => 'Annie Use Your Telescope',
			'Anonymous Pro' => 'Anonymous Pro',
			'Antic' => 'Antic',
			'Antic Didone' => 'Antic Didone',
			'Antic Slab' => 'Antic Slab',
			'Anton' => 'Anton',
			'Arapey' => 'Arapey',
			'Arbutus' => 'Arbutus',
			'Architects Daughter' => 'Architects Daughter',
			'Arimo' => 'Arimo',
			'Arizonia' => 'Arizonia',
			'Armata' => 'Armata',
			'Artifika' => 'Artifika',
			'Arvo' => 'Arvo',
			'Asap' => 'Asap',
			'Asset' => 'Asset',
			'Astloch' => 'Astloch',
			'Asul' => 'Asul',
			'Atomic Age' => 'Atomic Age',
			'Aubrey' => 'Aubrey',
			'Audiowide' => 'Audiowide',
			'Average' => 'Average',
			'Averia Gruesa Libre' => 'Averia Gruesa Libre',
			'Averia Libre' => 'Averia Libre',
			'Averia Sans Libre' => 'Averia Sans Libre',
			'Averia Serif Libre' => 'Averia Serif Libre',
			'Bad Script' => 'Bad Script',
			'Balthazar' => 'Balthazar',
			'Bangers' => 'Bangers',
			'Basic' => 'Basic',
			'Battambang' => 'Battambang',
			'Baumans' => 'Baumans',
			'Bayon' => 'Bayon',
			'Belgrano' => 'Belgrano',
			'Belleza' => 'Belleza',
			'Bentham' => 'Bentham',
			'Berkshire Swash' => 'Berkshire Swash',
			'Bevan' => 'Bevan',
			'Bigshot One' => 'Bigshot One',
			'Bilbo' => 'Bilbo',
			'Bilbo Swash Caps' => 'Bilbo Swash Caps',
			'Bitter' => 'Bitter',
			'Black Ops One' => 'Black Ops One',
			'Bokor' => 'Bokor',
			'Bonbon' => 'Bonbon',
			'Boogaloo' => 'Boogaloo',
			'Bowlby One' => 'Bowlby One',
			'Bowlby One SC' => 'Bowlby One SC',
			'Brawler' => 'Brawler',
			'Bree Serif' => 'Bree Serif',
			'Bubblegum Sans' => 'Bubblegum Sans',
			'Buda' => 'Buda',
			'Buenard' => 'Buenard',
			'Butcherman' => 'Butcherman',
			'Butterfly Kids' => 'Butterfly Kids',
			'Cabin' => 'Cabin',
			'Cabin Condensed' => 'Cabin Condensed',
			'Cabin Sketch' => 'Cabin Sketch',
			'Caesar Dressing' => 'Caesar Dressing',
			'Cagliostro' => 'Cagliostro',
			'Calligraffitti' => 'Calligraffitti',
			'Cambo' => 'Cambo',
			'Candal' => 'Candal',
			'Cantarell' => 'Cantarell',
			'Cantata One' => 'Cantata One',
			'Cardo' => 'Cardo',
			'Carme' => 'Carme',
			'Carter One' => 'Carter One',
			'Caudex' => 'Caudex',
			'Cedarville Cursive' => 'Cedarville Cursive',
			'Ceviche One' => 'Ceviche One',
			'Changa One' => 'Changa One',
			'Chango' => 'Chango',
			'Chau Philomene One' => 'Chau Philomene One',
			'Chelsea Market' => 'Chelsea Market',
			'Chenla' => 'Chenla',
			'Cherry Cream Soda' => 'Cherry Cream Soda',
			'Chewy' => 'Chewy',
			'Chicle' => 'Chicle',
			'Chivo' => 'Chivo',
			'Coda' => 'Coda',
			'Coda Caption' => 'Coda Caption',
			'Codystar' => 'Codystar',
			'Comfortaa' => 'Comfortaa',
			'Coming Soon' => 'Coming Soon',
			'Concert One' => 'Concert One',
			'Condiment' => 'Condiment',
			'Content' => 'Content',
			'Contrail One' => 'Contrail One',
			'Convergence' => 'Convergence',
			'Cookie' => 'Cookie',
			'Copse' => 'Copse',
			'Corben' => 'Corben',
			'Cousine' => 'Cousine',
			'Coustard' => 'Coustard',
			'Covered By Your Grace' => 'Covered By Your Grace',
			'Crafty Girls' => 'Crafty Girls',
			'Creepster' => 'Creepster',
			'Crete Round' => 'Crete Round',
			'Crimson Text' => 'Crimson Text',
			'Crushed' => 'Crushed',
			'Cuprum' => 'Cuprum',
			'Cutive' => 'Cutive',
			'Damion' => 'Damion',
			'Dancing Script' => 'Dancing Script',
			'Dangrek' => 'Dangrek',
			'Dawning of a New Day' => 'Dawning of a New Day',
			'Days One' => 'Days One',
			'Delius' => 'Delius',
			'Delius Swash Caps' => 'Delius Swash Caps',
			'Delius Unicase' => 'Delius Unicase',
			'Della Respira' => 'Della Respira',
			'Devonshire' => 'Devonshire',
			'Didact Gothic' => 'Didact Gothic',
			'Diplomata' => 'Diplomata',
			'Diplomata SC' => 'Diplomata SC',
			'Doppio One' => 'Doppio One',
			'Dorsa' => 'Dorsa',
			'Dosis' => 'Dosis',
			'Dr Sugiyama' => 'Dr Sugiyama',
			'Droid Sans' => 'Droid Sans',
			'Droid Sans Mono' => 'Droid Sans Mono',
			'Droid Serif' => 'Droid Serif',
			'Duru Sans' => 'Duru Sans',
			'Dynalight' => 'Dynalight',
			'EB Garamond' => 'EB Garamond',
			'Eater' => 'Eater',
			'Economica' => 'Economica',
			'Electrolize' => 'Electrolize',
			'Emblema One' => 'Emblema One',
			'Emilys Candy' => 'Emilys Candy',
			'Engagement' => 'Engagement',
			'Enriqueta' => 'Enriqueta',
			'Erica One' => 'Erica One',
			'Esteban' => 'Esteban',
			'Euphoria Script' => 'Euphoria Script',
			'Ewert' => 'Ewert',
			'Exo' => 'Exo',
			'Expletus Sans' => 'Expletus Sans',
			'Fanwood Text' => 'Fanwood Text',
			'Fascinate' => 'Fascinate',
			'Fascinate Inline' => 'Fascinate Inline',
			'Federant' => 'Federant',
			'Federo' => 'Federo',
			'Felipa' => 'Felipa',
			'Fjord One' => 'Fjord One',
			'Flamenco' => 'Flamenco',
			'Flavors' => 'Flavors',
			'Fondamento' => 'Fondamento',
			'Fontdiner Swanky' => 'Fontdiner Swanky',
			'Forum' => 'Forum',
			'Francois One' => 'Francois One',
			'Fredericka the Great' => 'Fredericka the Great',
			'Fredoka One' => 'Fredoka One',
			'Freehand' => 'Freehand',
			'Fresca' => 'Fresca',
			'Frijole' => 'Frijole',
			'Fugaz One' => 'Fugaz One',
			'GFS Didot' => 'GFS Didot',
			'GFS Neohellenic' => 'GFS Neohellenic',
			'Galdeano' => 'Galdeano',
			'Gentium Basic' => 'Gentium Basic',
			'Gentium Book Basic' => 'Gentium Book Basic',
			'Geo' => 'Geo',
			'Geostar' => 'Geostar',
			'Geostar Fill' => 'Geostar Fill',
			'Germania One' => 'Germania One',
			'Give You Glory' => 'Give You Glory',
			'Glass Antiqua' => 'Glass Antiqua',
			'Glegoo' => 'Glegoo',
			'Gloria Hallelujah' => 'Gloria Hallelujah',
			'Goblin One' => 'Goblin One',
			'Gochi Hand' => 'Gochi Hand',
			'Gorditas' => 'Gorditas',
			'Goudy Bookletter 1911' => 'Goudy Bookletter 1911',
			'Graduate' => 'Graduate',
			'Gravitas One' => 'Gravitas One',
			'Great Vibes' => 'Great Vibes',
			'Gruppo' => 'Gruppo',
			'Gudea' => 'Gudea',
			'Habibi' => 'Habibi',
			'Hammersmith One' => 'Hammersmith One',
			'Handlee' => 'Handlee',
			'Hanuman' => 'Hanuman',
			'Happy Monkey' => 'Happy Monkey',
			'Henny Penny' => 'Henny Penny',
			'Herr Von Muellerhoff' => 'Herr Von Muellerhoff',
			'Holtwood One SC' => 'Holtwood One SC',
			'Homemade Apple' => 'Homemade Apple',
			'Homenaje' => 'Homenaje',
			'IM Fell DW Pica' => 'IM Fell DW Pica',
			'IM Fell DW Pica SC' => 'IM Fell DW Pica SC',
			'IM Fell Double Pica' => 'IM Fell Double Pica',
			'IM Fell Double Pica SC' => 'IM Fell Double Pica SC',
			'IM Fell English' => 'IM Fell English',
			'IM Fell English SC' => 'IM Fell English SC',
			'IM Fell French Canon' => 'IM Fell French Canon',
			'IM Fell French Canon SC' => 'IM Fell French Canon SC',
			'IM Fell Great Primer' => 'IM Fell Great Primer',
			'IM Fell Great Primer SC' => 'IM Fell Great Primer SC',
			'Iceberg' => 'Iceberg',
			'Iceland' => 'Iceland',
			'Imprima' => 'Imprima',
			'Inconsolata' => 'Inconsolata',
			'Inder' => 'Inder',
			'Indie Flower' => 'Indie Flower',
			'Inika' => 'Inika',
			'Irish Grover' => 'Irish Grover',
			'Istok Web' => 'Istok Web',
			'Italiana' => 'Italiana',
			'Italianno' => 'Italianno',
			'Jim Nightshade' => 'Jim Nightshade',
			'Jockey One' => 'Jockey One',
			'Jolly Lodger' => 'Jolly Lodger',
			'Josefin Sans' => 'Josefin Sans',
			'Josefin Slab' => 'Josefin Slab',
			'Judson' => 'Judson',
			'Julee' => 'Julee',
			'Junge' => 'Junge',
			'Jura' => 'Jura',
			'Just Another Hand' => 'Just Another Hand',
			'Just Me Again Down Here' => 'Just Me Again Down Here',
			'Kameron' => 'Kameron',
			'Karla' => 'Karla',
			'Kaushan Script' => 'Kaushan Script',
			'Kelly Slab' => 'Kelly Slab',
			'Kenia' => 'Kenia',
			'Khmer' => 'Khmer',
			'Knewave' => 'Knewave',
			'Kotta One' => 'Kotta One',
			'Koulen' => 'Koulen',
			'Kranky' => 'Kranky',
			'Kreon' => 'Kreon',
			'Kristi' => 'Kristi',
			'Krona One' => 'Krona One',
			'La Belle Aurore' => 'La Belle Aurore',
			'Lancelot' => 'Lancelot',
			'Lato' => 'Lato',
			'League Script' => 'League Script',
			'Leckerli One' => 'Leckerli One',
			'Ledger' => 'Ledger',
			'Lekton' => 'Lekton',
			'Lemon' => 'Lemon',
			'Lilita One' => 'Lilita One',
			'Limelight' => 'Limelight',
			'Linden Hill' => 'Linden Hill',
			'Lobster' => 'Lobster',
			'Lobster Two' => 'Lobster Two',
			'Londrina Outline' => 'Londrina Outline',
			'Londrina Shadow' => 'Londrina Shadow',
			'Londrina Sketch' => 'Londrina Sketch',
			'Londrina Solid' => 'Londrina Solid',
			'Lora' => 'Lora',
			'Love Ya Like A Sister' => 'Love Ya Like A Sister',
			'Loved by the King' => 'Loved by the King',
			'Lovers Quarrel' => 'Lovers Quarrel',
			'Luckiest Guy' => 'Luckiest Guy',
			'Lusitana' => 'Lusitana',
			'Lustria' => 'Lustria',
			'Macondo' => 'Macondo',
			'Macondo Swash Caps' => 'Macondo Swash Caps',
			'Magra' => 'Magra',
			'Maiden Orange' => 'Maiden Orange',
			'Mako' => 'Mako',
			'Marck Script' => 'Marck Script',
			'Marko One' => 'Marko One',
			'Marmelad' => 'Marmelad',
			'Marvel' => 'Marvel',
			'Mate' => 'Mate',
			'Mate SC' => 'Mate SC',
			'Maven Pro' => 'Maven Pro',
			'Meddon' => 'Meddon',
			'MedievalSharp' => 'MedievalSharp',
			'Medula One' => 'Medula One',
			'Megrim' => 'Megrim',
			'Merienda One' => 'Merienda One',
			'Merriweather' => 'Merriweather',
			'Metal' => 'Metal',
			'Metamorphous' => 'Metamorphous',
			'Metrophobic' => 'Metrophobic',
			'Michroma' => 'Michroma',
			'Miltonian' => 'Miltonian',
			'Miltonian Tattoo' => 'Miltonian Tattoo',
			'Miniver' => 'Miniver',
			'Miss Fajardose' => 'Miss Fajardose',
			'Modern Antiqua' => 'Modern Antiqua',
			'Molengo' => 'Molengo',
			'Monofett' => 'Monofett',
			'Monoton' => 'Monoton',
			'Monsieur La Doulaise' => 'Monsieur La Doulaise',
			'Montaga' => 'Montaga',
			'Montez' => 'Montez',
			'Montserrat' => 'Montserrat',
			'Moul' => 'Moul',
			'Moulpali' => 'Moulpali',
			'Mountains of Christmas' => 'Mountains of Christmas',
			'Mr Bedfort' => 'Mr Bedfort',
			'Mr Dafoe' => 'Mr Dafoe',
			'Mr De Haviland' => 'Mr De Haviland',
			'Mrs Saint Delafield' => 'Mrs Saint Delafield',
			'Mrs Sheppards' => 'Mrs Sheppards',
			'Muli' => 'Muli',
			'Mystery Quest' => 'Mystery Quest',
			'Neucha' => 'Neucha',
			'Neuton' => 'Neuton',
			'News Cycle' => 'News Cycle',
			'Niconne' => 'Niconne',
			'Nixie One' => 'Nixie One',
			'Nobile' => 'Nobile',
			'Nokora' => 'Nokora',
			'Norican' => 'Norican',
			'Nosifer' => 'Nosifer',
			'Nothing You Could Do' => 'Nothing You Could Do',
			'Noticia Text' => 'Noticia Text',
			'Nova Cut' => 'Nova Cut',
			'Nova Flat' => 'Nova Flat',
			'Nova Mono' => 'Nova Mono',
			'Nova Oval' => 'Nova Oval',
			'Nova Round' => 'Nova Round',
			'Nova Script' => 'Nova Script',
			'Nova Slim' => 'Nova Slim',
			'Nova Square' => 'Nova Square',
			'Numans' => 'Numans',
			'Nunito' => 'Nunito',
			'Odor Mean Chey' => 'Odor Mean Chey',
			'Old Standard TT' => 'Old Standard TT',
			'Oldenburg' => 'Oldenburg',
			'Oleo Script' => 'Oleo Script',
			'Open Sans' => 'Open Sans',
			'Open Sans Condensed' => 'Open Sans Condensed',
			'Orbitron' => 'Orbitron',
			'Original Surfer' => 'Original Surfer',
			'Oswald' => 'Oswald',
			'Over the Rainbow' => 'Over the Rainbow',
			'Overlock' => 'Overlock',
			'Overlock SC' => 'Overlock SC',
			'Ovo' => 'Ovo',
			'Oxygen' => 'Oxygen',
			'PT Mono' => 'PT Mono',
			'PT Sans' => 'PT Sans',
			'PT Sans Caption' => 'PT Sans Caption',
			'PT Sans Narrow' => 'PT Sans Narrow',
			'PT Serif' => 'PT Serif',
			'PT Serif Caption' => 'PT Serif Caption',
			'Pacifico' => 'Pacifico',
			'Parisienne' => 'Parisienne',
			'Passero One' => 'Passero One',
			'Passion One' => 'Passion One',
			'Patrick Hand' => 'Patrick Hand',
			'Patua One' => 'Patua One',
			'Paytone One' => 'Paytone One',
			'Permanent Marker' => 'Permanent Marker',
			'Petrona' => 'Petrona',
			'Philosopher' => 'Philosopher',
			'Piedra' => 'Piedra',
			'Pinyon Script' => 'Pinyon Script',
			'Plaster' => 'Plaster',
			'Play' => 'Play',
			'Playball' => 'Playball',
			'Playfair Display' => 'Playfair Display',
			'Podkova' => 'Podkova',
			'Poiret One' => 'Poiret One',
			'Poller One' => 'Poller One',
			'Poly' => 'Poly',
			'Pompiere' => 'Pompiere',
			'Pontano Sans' => 'Pontano Sans',
			'Port Lligat Sans' => 'Port Lligat Sans',
			'Port Lligat Slab' => 'Port Lligat Slab',
			'Prata' => 'Prata',
			'Preahvihear' => 'Preahvihear',
			'Press Start 2P' => 'Press Start 2P',
			'Princess Sofia' => 'Princess Sofia',
			'Prociono' => 'Prociono',
			'Prosto One' => 'Prosto One',
			'Puritan' => 'Puritan',
			'Quantico' => 'Quantico',
			'Quattrocento' => 'Quattrocento',
			'Quattrocento Sans' => 'Quattrocento Sans',
			'Questrial' => 'Questrial',
			'Quicksand' => 'Quicksand',
			'Qwigley' => 'Qwigley',
			'Radley' => 'Radley',
			'Raleway' => 'Raleway',
			'Rammetto One' => 'Rammetto One',
			'Rancho' => 'Rancho',
			'Rationale' => 'Rationale',
			'Redressed' => 'Redressed',
			'Reenie Beanie' => 'Reenie Beanie',
			'Revalia' => 'Revalia',
			'Ribeye' => 'Ribeye',
			'Ribeye Marrow' => 'Ribeye Marrow',
			'Righteous' => 'Righteous',
			'Roboto' => 'Roboto',
			'Roboto Condensed' => 'Roboto Condensed',
			'Rochester' => 'Rochester',
			'Rock Salt' => 'Rock Salt',
			'Rokkitt' => 'Rokkitt',
			'Ropa Sans' => 'Ropa Sans',
			'Rosario' => 'Rosario',
			'Rosarivo' => 'Rosarivo',
			'Rouge Script' => 'Rouge Script',
			'Ruda' => 'Ruda',
			'Ruge Boogie' => 'Ruge Boogie',
			'Ruluko' => 'Ruluko',
			'Ruslan Display' => 'Ruslan Display',
			'Russo One' => 'Russo One',
			'Ruthie' => 'Ruthie',
			'Sail' => 'Sail',
			'Salsa' => 'Salsa',
			'Sanchez' => 'Sanchez',
			'Sancreek' => 'Sancreek',
			'Sansita One' => 'Sansita One',
			'Sarina' => 'Sarina',
			'Satisfy' => 'Satisfy',
			'Schoolbell' => 'Schoolbell',
			'Seaweed Script' => 'Seaweed Script',
			'Sevillana' => 'Sevillana',
			'Shadows Into Light' => 'Shadows Into Light',
			'Shadows Into Light Two' => 'Shadows Into Light Two',
			'Shanti' => 'Shanti',
			'Share' => 'Share',
			'Shojumaru' => 'Shojumaru',
			'Short Stack' => 'Short Stack',
			'Siemreap' => 'Siemreap',
			'Sigmar One' => 'Sigmar One',
			'Signika' => 'Signika',
			'Signika Negative' => 'Signika Negative',
			'Simonetta' => 'Simonetta',
			'Sirin Stencil' => 'Sirin Stencil',
			'Six Caps' => 'Six Caps',
			'Slackey' => 'Slackey',
			'Smokum' => 'Smokum',
			'Smythe' => 'Smythe',
			'Sniglet' => 'Sniglet',
			'Snippet' => 'Snippet',
			'Sofia' => 'Sofia',
			'Sonsie One' => 'Sonsie One',
			'Sorts Mill Goudy' => 'Sorts Mill Goudy',
			'Special Elite' => 'Special Elite',
			'Spicy Rice' => 'Spicy Rice',
			'Spinnaker' => 'Spinnaker',
			'Spirax' => 'Spirax',
			'Squada One' => 'Squada One',
			'Stardos Stencil' => 'Stardos Stencil',
			'Stint Ultra Condensed' => 'Stint Ultra Condensed',
			'Stint Ultra Expanded' => 'Stint Ultra Expanded',
			'Stoke' => 'Stoke',
			'Sue Ellen Francisco' => 'Sue Ellen Francisco',
			'Sunshiney' => 'Sunshiney',
			'Supermercado One' => 'Supermercado One',
			'Suwannaphum' => 'Suwannaphum',
			'Swanky and Moo Moo' => 'Swanky and Moo Moo',
			'Syncopate' => 'Syncopate',
			'Tangerine' => 'Tangerine',
			'Taprom' => 'Taprom',
			'Telex' => 'Telex',
			'Tenor Sans' => 'Tenor Sans',
			'The Girl Next Door' => 'The Girl Next Door',
			'Tienne' => 'Tienne',
			'Tinos' => 'Tinos',
			'Titan One' => 'Titan One',
			'Trade Winds' => 'Trade Winds',
			'Trocchi' => 'Trocchi',
			'Trochut' => 'Trochut',
			'Trykker' => 'Trykker',
			'Tulpen One' => 'Tulpen One',
			'Ubuntu' => 'Ubuntu',
			'Ubuntu Condensed' => 'Ubuntu Condensed',
			'Ubuntu Mono' => 'Ubuntu Mono',
			'Ultra' => 'Ultra',
			'Uncial Antiqua' => 'Uncial Antiqua',
			'UnifrakturCook' => 'UnifrakturCook',
			'UnifrakturMaguntia' => 'UnifrakturMaguntia',
			'Unkempt' => 'Unkempt',
			'Unlock' => 'Unlock',
			'Unna' => 'Unna',
			'VT323' => 'VT323',
			'Varela' => 'Varela',
			'Varela Round' => 'Varela Round',
			'Vast Shadow' => 'Vast Shadow',
			'Vibur' => 'Vibur',
			'Vidaloka' => 'Vidaloka',
			'Viga' => 'Viga',
			'Voces' => 'Voces',
			'Volkhov' => 'Volkhov',
			'Vollkorn' => 'Vollkorn',
			'Voltaire' => 'Voltaire',
			'Waiting for the Sunrise' => 'Waiting for the Sunrise',
			'Wallpoet' => 'Wallpoet',
			'Walter Turncoat' => 'Walter Turncoat',
			'Wellfleet' => 'Wellfleet',
			'Wire One' => 'Wire One',
			'Yanone Kaffeesatz' => 'Yanone Kaffeesatz',
			'Yellowtail' => 'Yellowtail',
			'Yeseva One' => 'Yeseva One',
			'Yesteryear' => 'Yesteryear',
			'Zeyada' => 'Zeyada',
		),	
	),
	
	//Theme Options
	'options' => array(
	
		//General
		array(	
			'name' => __('General', 'academy'),
			'type' => 'section'
		),

			array(	
				'name' => __('Site Favicon', 'academy'),
				'description' => __('Choose an image to replace the default site favicon', 'academy'),
				'id' => 'favicon',
				'type' => 'uploader',
			),

			array(	
				'name' => __('Site Logo', 'academy'),
				'description' => __('Choose an image to replace the default theme logo', 'academy'),
				'id' => 'site_logo',
				'type' => 'uploader',
			),
			
			array(	
				'name' => __('Login Logo', 'academy'),
				'description' => __('Choose an image to replace the default WordPress login logo', 'academy'),
				'id' => 'login_logo',
				'type' => 'uploader',
			),

			array(	
				'name' => __('Copyright Text', 'academy'),
				'description' => __('Enter copyright text to show in the theme footer', 'academy'),
				'id' => 'copyright',
				'type' => 'textarea',
			),

			array(	
				'name' => __('Tracking Code', 'academy'),
				'description' => __('Enter Google Analytics code to track your site visitors', 'academy'),
				'id' => 'tracking',
				'type' => 'textarea',
			),

		//Styling
		array(
			'name' => __('Styling', 'academy'),
			'type' => 'section',
		),	

			array(	
				'name' => __('Primary Color', 'academy'),
				'default' => '#f3715d',
				'id' => 'primary_color',
				'type' => 'color',
			),

			array(	
				'name' => __('Secondary Color', 'academy'),
				'default' => '#5ea5d7',
				'id' => 'secondary_color',
				'type' => 'color',
			),
			
			array(	
				'name' => __('Background Color', 'academy'),
				'default' => '#3d4e5b',
				'id' => 'background_color',
				'type' => 'color',
			),

			array(	
				'name' => __('Background Image', 'academy'),
				'id' => 'background_image',
				'description' => __('Choose background image from WordPress media library', 'academy'),
				'type' => 'uploader',
			),
			
			array(	
				'name' => __('Background Type', 'academy'),
				'id' => 'background_type',
				'type' => 'select',
				'options' => array(
					'fullwidth' => __('Full Width', 'academy'),
					'tiled' => __('Tiled', 'academy'),
				),
			),
			
			array(	
				'name' => __('Heading Font' ,'academy'),					
				'id' => 'heading_font',
				'default' => 'Crete Round',
				'type' => 'select_font',
			),

			array(	
				'name' => __('Content Font', 'academy'),
				'id' => 'content_font',
				'default' => 'Open Sans',
				'type' => 'select_font',
			),

			array(	
				'name' => __('Custom CSS', 'academy'),
				'description' => __('Enter custom CSS code to overwrite the default theme styles', 'academy'),
				'id' => 'css',
				'type' => 'textarea',
			),
			
		//Slider
		array(	
			'name' => __('Header', 'academy'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('Sharing Code', 'academy'),
				'description' => __('Enter social sharing buttons code to show share button in the header', 'academy'),
				'id' => 'sharing',
				'type' => 'textarea',
			),
			
			array(	
				'name' => __('Slider Type', 'academy'),
				'id' => 'slider_type',
				'type' => 'select',
				'options' => array(
					'parallax' => __('Parallax', 'academy'),
					'stretched' => __('Stretched', 'academy'),
					'boxed' => __('Boxed', 'academy'),
				),
			),
					
			array(	
				'name' => __('Slider Pause', 'academy'),
				'default' => '0',
				'id' => 'slider_pause',
				'min_value' => 0,
				'max_value' => 15000,
				'unit'=>'ms',
				'type' => 'slider',
			),
			
			array(	
				'name' => __('Slider Speed', 'academy'),
				'default' => '1000',
				'id' => 'slider_speed',
				'min_value' => 0,
				'max_value' => 1000,
				'unit'=>'ms',
				'type' => 'slider',
			),
			
		//Courses
		array(
			'name' => __('Courses', 'academy'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('Courses Layout', 'academy'),
				'id' => 'courses_layout',
				'type' => 'select_image',
				'options' => array(
					'fullwidth' => THEMEX_URI.'assets/images/layouts/layout-full.png',
					'left' => THEMEX_URI.'assets/images/layouts/layout-left.png',
					'right' => THEMEX_URI.'assets/images/layouts/layout-right.png',				
				),
			),
		
			array(	
				'name' => __('Courses View', 'academy'),
				'id' => 'courses_view',
				'type' => 'select',
				'options' => array(
					'grid' => __('Grid', 'academy'),
					'list' => __('List', 'academy'),
				),
			),
			
			array(	
				'name' => __('Courses Columns', 'academy'),
				'id' => 'courses_columns',
				'type' => 'select',
				'default' => '4',
				'options' => array(
					'3' => '3',
					'4' => '4',
				),
				'parent' => array(
					'id' => 'courses_view',
					'value' => 'grid',
				),
			),
			
			array(	
				'name' => __('Courses Order', 'academy'),
				'id' => 'courses_order',
				'type' => 'select',
				'options' => array(
					'date' => __('Date', 'academy'),
					'rating' => __('Rating', 'academy'),
					'popularity' => __('Popularity', 'academy'),
				),
			),
			
			array(	
				'name' => __('Courses Per Page', 'academy'),
				'id' => 'courses_per_page',
				'type' => 'number',
				'default' => '12',
			),
			
			array(	
				'name' => __('Related Courses Order', 'academy'),
				'id' => 'courses_related_order',
				'type' => 'select',
				'options' => array(				
					'category' => __('Category', 'academy'),
					'rating' => __('Rating', 'academy'),
					'popularity' => __('Popularity', 'academy'),
				),
			),
			
			array(	
				'name' => __('Related Courses Number', 'academy'),
				'id' => 'courses_related_number',
				'type' => 'number',
				'default' => '4',
			),
			
			array(	
				'name' => __('Students Number', 'academy'),
				'id' => 'course_users_number',
				'type' => 'number',
				'default' => '9',
			),
			
			array(	
				'name' => __('Questions Number', 'academy'),
				'id' => 'course_questions_number',
				'type' => 'number',
				'default' => '7',
			),
			
			array(
				'name' => __('Hide Course Author', 'academy'),
				'id' => 'course_author',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Course Rating', 'academy'),
				'id' => 'course_rating',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Students Number', 'academy'),
				'id' => 'course_popularity',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Disable Retaking Courses', 'academy'),
				'id' => 'course_retake',
				'type' => 'checkbox',
			),
			
		//Lessons
		array(
			'name' => __('Lessons', 'academy'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('Lessons Layout', 'academy'),
				'id' => 'lessons_layout',
				'type' => 'select_image',
				'options' => array(
					'left' => THEMEX_URI.'assets/images/layouts/layout-left.png',
					'right' => THEMEX_URI.'assets/images/layouts/layout-right.png',				
				),
			),
			
			array(
				'name' => __('Hide Child Lessons', 'academy'),
				'id' => 'lesson_collapse',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Disable Retaking Lessons', 'academy'),
				'id' => 'lesson_retake',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Prerequisite Content', 'academy'),
				'id' => 'lesson_hide',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Shuffle Quiz Questions', 'academy'),
				'id' => 'quiz_shuffle',
				'type' => 'checkbox',
			),
		
		//Posts
		array(
			'name' => __('Posts', 'academy'),
			'type' => 'section',
		),		
			
			array(	
				'name' => __('Posts Layout', 'academy'),
				'id' => 'posts_layout',
				'type' => 'select_image',
				'options' => array(
					'left' => THEMEX_URI.'assets/images/layouts/layout-left.png',
					'right' => THEMEX_URI.'assets/images/layouts/layout-right.png',				
				),
			),
			
			array(
				'name' => __('Hide Post Author', 'academy'),
				'id' => 'post_author',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Post Date', 'academy'),
				'id' => 'post_date',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Hide Post Image', 'academy'),
				'id' => 'post_image',
				'type' => 'checkbox',
			),
			
		//Registration
		array(
			'name' => __('Registration', 'academy'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('Enable Email Confirmation', 'academy'),
				'id' => 'user_activation',
				'type' => 'checkbox',
			),
		
			array(
				'name' => __('Enable Captcha Protection', 'academy'),
				'id' => 'user_captcha',
				'type' => 'checkbox',
			),
			
			array(
				'name' => __('Enable Login Filter', 'academy'),
				'id' => 'user_filter',
				'type' => 'checkbox',
			),
			
			array(	
				'name' => __('Enable Facebook Login', 'academy'),
				'id' => 'facebook_login',
				'type' => 'checkbox',
			),
			
			array(	
				'name' => __('Facebook Application ID', 'academy'),
				'id' => 'facebook_id',
				'type' => 'text',
				'parent' => array(
					'id' => 'facebook_login',
					'value' => 'true',
				),
			),
			
			array(	
				'name' => __('Facebook Application Secret', 'academy'),
				'id' => 'facebook_secret',
				'type' => 'text',
				'parent' => array(
					'id' => 'facebook_login',
					'value' => 'true',
				),
			),
			
			array(	
				'name' => __('Registration Email', 'academy'),
				'id' => 'email_registration',
				'description' => __('Add registration email text, you can use %username%, %password% and %link% keywords', 'academy'),
				'type' => 'textarea',
			),
			
			array(	
				'name' => __('Password Reset Email', 'academy'),
				'id' => 'email_password',
				'description' => __('Add password reset email text, you can use %username% and %link% keywords', 'academy'),
				'type' => 'textarea',
			),
			
		//Notifications
		array(	
			'name' => __('Notifications', 'academy'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('New Course Email', 'academy'),
				'id' => 'email_course',
				'description' => __('Add notification email text, you can use %username%, %title% and %link% keywords', 'academy'),
				'type' => 'textarea',
			),
			
			array(
				'name' => __('Completed Course Email', 'academy'),
				'id' => 'email_certificate',
				'description' => __('Add notification email text, you can use %username%, %title% and %link% keywords', 'academy'),
				'type' => 'textarea',
			),
		
			array(	
				'name' => __('New Plan Email', 'academy'),
				'id' => 'email_plan',
				'description' => __('Add notification email text, you can use %username%, %title% and %link% keywords', 'academy'),
				'type' => 'textarea',
			),
			
			array(
				'name' => __('New Question Email', 'academy'),
				'id' => 'email_question',
				'description' => __('Add notification email text, you can use %username%, %title% and %link% keywords', 'academy'),
				'type' => 'textarea',
			),
			
		//Profile Form
		array(	
			'name' => __('Profile Form', 'academy'),
			'type' => 'section',
		),
		
			array(	
				'name' => __('Hide Signature', 'academy'),
				'id' => 'profile_signature',
				'type' => 'checkbox',
			),
		
			array(	
				'name' => __('Hide Description', 'academy'),
				'id' => 'profile_description',
				'type' => 'checkbox',
			),
		
			array(	
				'name' => __('Hide Social Links', 'academy'),
				'id' => 'profile_links',
				'type' => 'checkbox',
			),
			
			array(	
				'name' => __('Hide Courses', 'academy'),
				'id' => 'profile_courses',
				'type' => 'checkbox',
			),
		
			array(
				'id' => 'ThemexForm',
				'slug' => 'profile',
				'type' => 'module',
			),
		
		//Contact Form
		array(
			'name' => __('Contact Form', 'academy'),
			'type' => 'section',
		),
			
			array(
				'id' => 'ThemexForm',
				'slug' => 'contact',
				'type' => 'module',
			),
			
		//Checkout Form
		array(	
			'name' => __('Checkout Form', 'academy'),
			'type' => 'section',
		),
		
			array(
				'id' => 'ThemexWoo',
				'type' => 'module',
			),
			
		//Sidebars
		array(	
			'name' => __('Sidebars', 'academy'),
			'type' => 'section',
		),
		
			array(
				'id' => 'ThemexSidebar',
				'type' => 'module',
			),		
	),
	
);