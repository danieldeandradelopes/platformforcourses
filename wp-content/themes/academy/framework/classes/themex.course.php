<?php
/**
 * Themex Course
 *
 * Handles courses data
 *
 * @class ThemexCourse
 * @author Themex
 */
 
class ThemexCourse {

	/** @var array Contains module data. */
	public static $data;

	/**
	 * Adds actions and filters
     *
     * @access public
     * @return void
     */
	public static function init() {
	
		//update course
		add_action('wp', array(__CLASS__, 'updateCourse'), 99);
		add_action('wp_ajax_themex_update_course', array(__CLASS__, 'updateCourse'));
		add_action('wp_ajax_nopriv_themex_update_course', array(__CLASS__, 'updateCourse'));
		add_filter('save_post',  array(__CLASS__, 'updateAdminCourse'));
		
		//paginate courses
		add_action('pre_get_posts', array(__CLASS__, 'filterCourses'));

		//redirect course
		add_action('template_redirect', array(__CLASS__, 'redirectCourse'), 1);
		
		//add statistics
		add_action('admin_menu', array(__CLASS__, 'addStatistics'));
		
		//delete user
		add_action('delete_user', array(__CLASS__, 'deleteUser'));
	}
	
	/**
	 * Refreshes module data
     *
     * @access public	 
     * @return void
     */
	public static function refresh($ID=0, $extended=false) {
		if(!isset(self::$data['ID']) || $ID!=self::$data['ID'] || ($extended && !self::$data['extended'])) {
			self::$data=self::getCourse($ID, $extended);
		}
	}
	
	/**
	 * Gets course
     *
     * @access public
	 * @param int $ID
	 * @param bool $extended
     * @return array
     */
	public static function getCourse($ID, $extended=false) {
		$course['ID']=intval($ID);
		$course['extended']=$extended;
		$course['status']=ThemexCore::getPostMeta($ID, 'course_status', 'free');
		
		$course['author']=self::getAuthor($ID);
		$course['progress']=self::getProgress($ID, get_current_user_id());
		$course['users']=ThemexCore::getUserRelations(0, $ID, 'course');
		
		$course['capacity']=intval(ThemexCore::getPostMeta($ID, 'course_capacity', '0'));
		if($course['capacity']>0) {
			$course['capacity']=$course['capacity']-count($course['users'])-1;
		}
		
		$course['plans']=self::getPlans($ID);
		if(!isset(self::$data['ID'])) {
			$course['plan']=self::getPlan(get_current_user_id());
		} else {
			$course['plan']=self::$data['plan'];
		}
		
		$course['product']=0;
		$course['price']['text']=__('Free', 'academy');
		if($course['status']=='premium') {
			$course['product']=ThemexCore::getPostRelations($ID, 0, 'course_product', true);
			$course['price']=ThemexWoo::getPrice($course['product']);
		}
		
		if(!ThemexCore::checkOption('course_rating')) {
			$course['rating']=floatval(ThemexCore::getPostMeta($ID, 'course_rating', '0'));
			$course['ratings']=ThemexCore::getUserRelations(0, $ID, 'rating');
		}
		
		if($extended) {
			$course['sidebar']=ThemexCore::getPostMeta($ID, 'course_sidebar');
			$course['lessons']=self::getLessons($ID, $course['author']['ID']);
			$course['questions']=self::getQuestions($ID);
		}
		
		return $course;
	}
	
	/**
	 * Gets courses
     *
     * @access public
	 * @param int $user
     * @return array
     */
	public static function getCourses($user) {
		global $wpdb;
		
		$courses=themex_implode(ThemexCore::getUserRelations($user, 0, 'course'));		
		$courses=$wpdb->get_results("
			SELECT ID FROM ".$wpdb->posts." 
			WHERE post_status = 'publish' 
			AND post_type = 'course' 
			AND (post_author = ".intval($user)." 
			OR ID IN (".$courses.")) 
			ORDER BY post_date DESC
		");
		
		$courses=wp_list_pluck($courses, 'ID');		
		return $courses;
	}
	
	/**
	 * Completes course
     *
     * @access public
     * @return void
     */
	public static function completeCourse() {	
		if(self::isMember() && self::$data['progress']==100) {
			ThemexCore::addUserRelation(get_current_user_id(), self::$data['ID'], 'certificate', current_time('timestamp'));
			
			$message=ThemexCore::getOption('email_certificate');
			if(!empty($message)) {
				$data=wp_get_current_user();				
				
				if(self::hasCertificate()) {
					$link=ThemexCore::getURL('certificate', themex_encode(self::$data['ID'], get_current_user_id()));
				} else {
					$link=get_permalink(self::$data['ID']);
				}
				
				$keywords=array(
					'username' => $data->user_login,
					'title' => get_the_title(self::$data['ID']),
					'link' => $link,
				);
				
				themex_mail($data->user_email, __('Course Completed', 'academy'), themex_keywords($message, $keywords));
			}
		}
	}
	
	/**
	 * Uncompletes course
     *
     * @access public
     * @return void
     */
	public static function uncompleteCourse() {
		if(self::isMember() && !ThemexCore::checkOption('course_retake')) {
			ThemexCore::removeUserRelation(get_current_user_id(), self::$data['ID'], 'certificate');
		}
	}
	
	/**
	 * Counts courses
     *
     * @access public
     * @return int
     */
	public static function countCourses() {
		global $wpdb;
		
		$results=$wpdb->get_results("
			SELECT COUNT(comment_post_ID) AS courses FROM ".$wpdb->comments." 
			WHERE comment_type = 'user_certificate' 
			AND comment_content <> '' 
			GROUP BY comment_post_ID 
			LIMIT 1 
		");

		$results=wp_list_pluck($results, 'courses');
		$courses=intval(reset($results));
	
		return $courses;
	}
	
	/**
	 * Counts member courses
     *
     * @access public
     * @return int
     */
	public static function countMemberCourses() {
		global $wpdb;
		
		$results=$wpdb->get_results("
			SELECT ROUND(AVG(courses)) as courses FROM 
			(SELECT COUNT(comment_post_ID) AS courses FROM ".$wpdb->comments." 
			WHERE comment_type = 'user_course' 
			GROUP BY user_id) as users 
			LIMIT 1 
		");
		
		$results=wp_list_pluck($results, 'courses');
		$courses=reset($results);
	
		return $courses;
	}
	
	/**
	 * Updates course
     *
     * @access public	 
     * @return void
     */
	public static function updateCourse() {
		$data=$_POST;
		if(isset($_POST['data'])) {	
			parse_str($_POST['data'], $data);
			$data['nonce']=$_POST['nonce'];
			check_ajax_referer(THEMEX_PREFIX.'nonce', 'nonce');			
		}
		
		if(is_user_logged_in() && isset($data['course_action']) && wp_verify_nonce($data['nonce'], THEMEX_PREFIX.'nonce')) {
			if(isset($data['course_id'])) {
				self::refresh($data['course_id']);
			}
			
			switch(sanitize_key($data['course_action'])) {
				case 'add_user':
					self::addUser($data['course_id'], get_current_user_id());
					wp_redirect(themex_url());
					exit();
				break;
				
				case 'remove_user':
					self::removeUser($data['course_id'], get_current_user_id());
					wp_redirect(themex_url());
					exit();
				break;
				
				case 'subscribe_user':
					self::subscribeUser($data['plan_id'], get_current_user_id());
					wp_redirect(themex_url());
					exit();
				break;
				
				case 'complete_course':
					self::completeCourse();
					if(!get_query_var('quiz')) {
						wp_redirect(themex_url());
						exit();
					}
				break;
				
				case 'uncomplete_course':
					self::uncompleteCourse();
					wp_redirect(themex_url());
					exit();
				break;
				
				case 'update_rating':
					self::updateRating($data['course_rating']);
				break;
			}
		}
	}
	
	/**
	 * Updates admin course
     *
     * @access public	 
	 * @param int $ID
     * @return void
     */
	public static function updateAdminCourse($ID) {
		global $post;

		if(current_user_can('edit_posts')) {
			if(isset($_POST['add_user']) && isset($_POST['add_user_id'])) {
				if($post->post_type=='course') {
					self::addUser($ID, intval($_POST['add_user_id']), true);
				} else if($post->post_type=='plan') {
					self::subscribeUser($ID, intval($_POST['add_user_id']), true);
				}
			} else if(isset($_POST['remove_user']) && isset($_POST['remove_user_id'])) {
				if($post->post_type=='course') {
					self::removeUser($ID, intval($_POST['remove_user_id']), true);
				} else if($post->post_type=='plan') {
					self::unsubscribeUser($ID, intval($_POST['remove_user_id']), true);
				}
			}
		}
	}
		
	/**
	 * Queries courses
     *
     * @access public	 
     * @return void
     */
	public static function queryCourses() {
		global $wp_query;
		
		$args=array(
			'post_type' => 'course',
			'posts_per_page' => ThemexCore::getOption('courses_per_page', '12'),
			'paged' => themex_paged(),
			'meta_query' => array(
				array(
					'key' => '_thumbnail_id',
				),
			),
		);
		
		if(get_query_var('course_category')) {
			$args['tax_query'][]=array(
				'taxonomy' => 'course_category',
				'field' => 'slug',
				'terms' => get_query_var('course_category'),				
			);
		}
		
		$order=ThemexCore::getOption('courses_order', 'date');
		if(in_array($order, array('rating', 'popularity'))) {
			$args['orderby']='meta_value_num';
			$args['meta_key']='_course_'.$order;
		}
		
		query_posts($args);
	}
	
	/**
	 * Queries related courses
     *
     * @access public	 
     * @return void
     */
	public static function queryRelatedCourses() {
		$limit=intval(ThemexCore::getOption('courses_related_number', '4'));
		$order=ThemexCore::getOption('courses_related_order', 'date');
				
		$args=array(
			'post_type' => 'course',
			'numberposts' => $limit,
			'post__not_in' => array(self::$data['ID']),
			'fields' => 'ids',
			'meta_query' => array(
				array(
					'key' => '_thumbnail_id',
				),
			),
		);		
		
		if($order=='category') {
			$terms=get_the_terms(self::$data['ID'], 'course_category');
			if(!empty($terms)) {
				$terms=array_values(wp_list_pluck($terms, 'term_id'));
				$args['tax_query']=array(array(
					'taxonomy' => 'course_category',
					'operator' => 'IN',
					'field' => 'id',
					'terms' => $terms,
				));
			}
		} else if(in_array($order, array('rating', 'popularity'))) {
			$args['orderby']='meta_value_num';
			$args['meta_key']='_course_'.$order;
		}
		
		$posts=array(0);
		if($limit!=0) {
			$posts=get_posts($args);
			if($limit>count($posts)) {			
				$posts=array_merge($posts, get_posts(array(
					'post_type' => 'course',
					'meta_key' => '_thumbnail_id',
					'numberposts' => $limit-count($posts),
					'post__not_in' => array_merge(array(self::$data['ID']), $posts),
					'fields' => 'ids',
				)));
			}
		}
		
		query_posts(array(
			'post_type' => 'course',
			'showposts' => -1,
			'orderby' => 'post__in',
			'post__in' => $posts,
		));
	}
	
	/**
	 * Filters courses query
     *
     * @access public
	 * @param mixed $query
     * @return mixed
     */
	public static function filterCourses($query) {	
		if(!is_admin() && $query->is_main_query() && $query->is_tax('course_category')) {
			$number=intval(ThemexCore::getOption('courses_per_page', '12'));
			$query->set('posts_per_page', $number);
		}
		
		return $query;
	}	
	
	/**
	 * Redirects course or plan
     *
     * @access public
     * @return void
     */
	public static function redirectCourse() {
		$ID=ThemexCore::getRewriteRule('redirect');
		
		if(!empty($ID)) {
			$post=get_post($ID);
			
			if(!empty($post)) {
				if($post->post_type=='plan') {
					self::subscribeUser($post->ID, get_current_user_id());					
				} else if($post->post_type=='course') {
					self::addUser($post->ID, get_current_user_id());
				}
			}
			
			wp_redirect(SITE_URL);
			exit();
		}
	}
	
	
	/**
	 * Gets plan
     *
     * @access public
	 * @param int $user
     * @return array
     */
	public static function getPlan($user) {
		global $wpdb;
		
		$ID=ThemexCore::getUserRelations($user, 0, 'plan', true);
		$subscriptions=get_user_meta(get_current_user_id(), $wpdb->prefix.'woocommerce_subscriptions', true);
		$plan=array();
		
		if($ID!=0) {
			$plan['ID']=$ID;
			$plan['time']=ThemexCore::getUserRelations($user, $ID, 'plan', true)-time();
			$plan['period']=intval(ThemexCore::getPostMeta($ID, 'plan_period', 7));
			
			if(is_array($subscriptions) && !empty($subscriptions)) {
				$plan['product']=ThemexCore::getPostRelations($ID, 0, 'plan_product', true);				
				if($plan['product']!=0) {
					foreach($subscriptions as $key=>$subscription) {
						if($subscription['product_id']==$plan['product']) {
							$time=strtotime($subscription['expiry_date']);

							if($time!==false) {
								$plan['time']=$time-time();
							} else {
								$time=wp_next_scheduled('scheduled_subscription_payment', array(
									'user_id' => $user,
									'subscription_key' => $key,
								));
								
								if($time!=false) {
									$plan['time']=$time-time();
								}
							}
							
							break;
						}
					}
				}
			}

			if($plan['time']>0 || $plan['period']==0) {
				$category=ThemexCore::getPostRelations($ID, 0, 'plan_category', true);
				$plan['url']=get_term_link($category, 'course_category');
				if(is_wp_error($plan['url'])) {
					$plan['url']=get_permalink($ID);
				}
			} else {
				self::unsubscribeUser($ID, $user);
			}
		}
		
		return $plan;
	}
	
	/**
	 * Gets plans
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getPlans($ID) {
		global $wpdb;
		
		$categories=wp_get_post_terms($ID, 'course_category', array('fields' => 'ids'));
		$plans=ThemexCore::getPostRelations(0, $categories, 'plan_category');
		
		$results=$wpdb->get_results("
			SELECT post_id FROM ".$wpdb->postmeta." as meta 
			INNER JOIN ".$wpdb->posts." AS posts ON (posts.ID=meta.post_id) 
			WHERE meta_key = '_plan_category' 
			AND meta_value IN(".themex_implode($categories).") 
			ORDER BY posts.menu_order 
		");

		$results=wp_list_pluck($results, 'post_id');
		return $results;
	}
	
	/**
	 * Gets plan price
     *
     * @access public
	 * @param int $ID
     * @return string
     */
	public static function getPlanPrice($ID) {
		$out='';
		if(ThemexWoo::isActive()) {
			$product=ThemexCore::getPostRelations($ID, 0, 'plan_product', true);
			$period=intval(ThemexCore::getPostMeta($ID, 'plan_period', 7));
			$converted=themex_time($period*86400, true);
			
			if($product!=0) {
				$price=ThemexWoo::getPrice($product);
				$out='<span>'.$price['text'].'</span>';
				
				if($period!=0 && strpos($price['type'], 'subscription')===false) {
					$out.=' / '.$converted;
				}
			}
		}
		
		return $out;
	}
	
	/**
	 * Subscribes course user
     *
     * @access public
	 * @param int $ID
	 * @param int $user
	 * @param bool $private
     * @return void
     */
	public static function subscribeUser($ID, $user, $private=false) {
		$product=ThemexCore::getPostRelations($ID, 0, 'plan_product', true);
		$plan=ThemexCore::getUserRelations($user, 0, 'plan', true);
		
		$redirect=get_term_link(ThemexCore::getPostRelations($ID, 0, 'plan_category', true), 'course_category');
		if(is_wp_error($redirect)) {
			$redirect=get_permalink($ID);
		}
		
		if($ID!=$plan) {
			if($private || $product==0) {
				$period=intval(ThemexCore::getPostMeta($ID, 'plan_period', '7'));
				$time=time()+86400*$period;
				
				ThemexCore::removeUserRelation($user, 0, 'plan');
				ThemexCore::addUserRelation($user, $ID, 'plan', $time);

				$message=ThemexCore::getOption('email_plan');
				if(!empty($message)) {
					$data=get_userdata($user);
					$category=ThemexCore::getPostRelations($ID, 0, 'plan_category', true);
					
					$keywords=array(
						'username' => $data->user_login,
						'title' => get_the_title($ID),
						'link' => $redirect,
					);
					
					themex_mail($data->user_email, __('Plan Subscription', 'academy'), themex_keywords($message, $keywords));
				}
				
				if(!$private) {
					wp_redirect($redirect);
					exit();
				}
			} else {
				ThemexWoo::addProduct($product);
			}
		} else {
			wp_redirect($redirect);
			exit();
		}
	}
	
	/**
	 * Unsubscribes course user
     *
     * @access public
	 * @param int $ID
	 * @param int $user
     * @return void
     */
	public static function unsubscribeUser($ID, $user) {
		ThemexCore::removeUserRelation($user, $ID, 'plan');
	}
	
	/**
	 * Checks course subscriber
     *
     * @access public
     * @return bool
     */
	public static function isSubscriber() {
		if(empty(self::$data['plans']) || in_array(themex_value(self::$data['plan'], 'ID'), self::$data['plans'])) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Gets course author
     *
     * @access public	
	 * @param int $ID
     * @return array
     */
	public static function getAuthor($ID) {
		global $wpdb;
		
		$results=$wpdb->get_results("
			SELECT post_author FROM ".$wpdb->posts." 
			WHERE ID = ".intval($ID)."
		");
		
		$results=wp_list_pluck($results, 'post_author');
		$user=intval(reset($results));
		$author=ThemexUser::getUser($user);
		
		return $author;
	}
	
	/**
	 * Gets course authors
     *
     * @access public	
	 * @param array $args
     * @return array
     */
	public static function getAuthors($args) {
		if($args['orderby']=='post_count') {
			add_action('pre_user_query', array( __CLASS__, 'filterAuthors'));
		}
		
		$authors=get_users($args);
		
		return $authors;
	}
	
	/**
	 * Filters course authors
     *
     * @access public	
	 * @param array $args
     * @return array
     */
	public static function filterAuthors($args) {		
		$args->query_from = str_replace("post_type = 'post'", "post_type = 'course'", $args->query_from);	
		remove_action('pre_user_query', array( __CLASS__, 'filterAuthors'));
		
		return $args;
	}
	
	/**
	 * Checks course author
     *
     * @access public
	 * @param int $user
     * @return bool
     */
	public static function isAuthor($user=0) {
		if($user==0) {
			$user=self::$data['author']['ID'];
		}
		
		if($user==get_current_user_id() || current_user_can('manage_options')) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Updates course rating
     *
     * @access public	 
	 * @param int $rating
     * @return void
     */
	public static function updateRating($rating) {
		$rating=intval($rating);
		
		if(!self::isRated() && $rating>0 && $rating<6) {
			ThemexCore::addUserRelation(get_current_user_id(), self::$data['ID'], 'rating', $rating);
			
			$rating=round((count(self::$data['ratings'])*self::$data['rating']+$rating)/(count(self::$data['ratings'])+1), 2);			
			ThemexCore::updatePostMeta(self::$data['ID'], 'course_rating', $rating);			
		}
		
		die();
	}
	
	/**
	 * Checks course rating
     *
     * @access public	 
     * @return bool
     */
	public static function isRated() {
		if(!self::isMember() || in_array(get_current_user_id(), self::$data['ratings'])) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Gets course progress
     *
     * @access public
	 * @param int $ID
	 * @param int $user
     * @return int
     */
	public static function getProgress($ID, $user) {
		$lessons=ThemexCore::getPostRelations(0, $ID, 'lesson_course');

		$progress=0;
		if(!empty($lessons)) {
			$users=ThemexCore::getUserRelations($user, $lessons, 'lesson');
			$progress=round(100*(count($users)/count($lessons)));
		}
		
		return $progress;
	}
	
	/**
	 * Gets average grade
     *
     * @access public
	 * @param int $ID
	 * @param int $user
     * @return int
     */
	public static function getGrade($ID, $user) {
		global $wpdb;
		
		$results=$wpdb->get_results("
			SELECT ROUND(AVG(comment_content)) AS grade FROM ".$wpdb->comments." 
			WHERE user_id = ".intval($user)." 
			AND comment_type = 'user_lesson' 
			LIMIT 1 
		");

		$results=wp_list_pluck($results, 'grade');
		$grade=reset($results);
	
		return $grade;
	}
	
	/**
	 * Counts average grade
     *
     * @access public
     * @return int
     */
	public static function countGrade() {
		global $wpdb;
		
		$results=$wpdb->get_results("
			SELECT ROUND(AVG(comment_content)) AS grade FROM ".$wpdb->comments." 
			WHERE comment_type = 'user_lesson' 
			LIMIT 1 
		");

		$results=wp_list_pluck($results, 'grade');
		$grade=round(reset($results));
	
		return $grade;
	}
	
	/**
	 * Gets course members
     *
     * @access public
     * @return array
     */
	public static function getMembers() {
		$users=self::$data['users'];
		shuffle($users);
		$users=array_slice($users, 0, intval(ThemexCore::getOption('course_users_number', '9')));		
		
		return $users;
	}
	
	/**
	 * Counts course members
     *
     * @access public
     * @return int
     */
	public static function countMembers() {
		global $wpdb;
		
		$results=$wpdb->get_results("
			SELECT user_id AS users FROM ".$wpdb->comments." 
			WHERE comment_type = 'user_course' 
			GROUP BY user_id 
		");

		$results=wp_list_pluck($results, 'users');
		$users=count($results);
	
		return $users;
	}
	
	/**
	 * Checks course members
     *
     * @access public
     * @return bool
     */
	public static function hasMembers() {
		$number=intval(ThemexCore::getOption('course_users_number', '9'));
		if(!empty(self::$data['users']) && $number!=0) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Checks course member
     *
     * @access public
	 * @param int $user
     * @return bool
     */
	public static function isMember($user=0) {
		if($user==0) {
			$user=get_current_user_id();
		}
		
		if(is_user_logged_in() && in_array($user, self::$data['users'])) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Adds course member
     *
     * @access public
	 * @param int $ID
	 * @param bool $private
     * @return void
     */
	public static function addUser($ID, $user, $private=false) {
		self::refresh($ID);
		$redirect=get_permalink($ID);

		if(!self::isMember($user)) {
			if($private || self::$data['status']=='free' || (self::$data['status']=='premium' && self::$data['product']==0)) {
				ThemexCore::addUserRelation($user, $ID, 'course', current_time('timestamp'));
				self::$data['users'][]=$user;
			
				$number=count(self::$data['users']);
				ThemexCore::updatePostMeta($ID, 'course_popularity', $number);
				
				$message=ThemexCore::getOption('email_course');
				if(!empty($message)) {
					$data=get_userdata($user);
					$keywords=array(
						'username' => $data->user_login,
						'title' => get_the_title($ID),
						'link' => $redirect,
					);
					
					themex_mail($data->user_email, __('Course Membership', 'academy'), themex_keywords($message, $keywords));
				}
				
				if(!$private) {
					wp_redirect($redirect);
					exit();
				}
			} else if(self::$data['status']=='premium') {
				ThemexWoo::addProduct(self::$data['product']);
			}
		} else if(!$private) {
			wp_redirect($redirect);
			exit();
		}
	}
	
	/**
	 * Removes course member
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function removeUser($ID, $user) {
		self::refresh($ID);
		
		if(self::isMember($user)) {
			ThemexCore::removeUserRelation($user, self::$data['ID'], 'course');
			
			$number=count(self::$data['users'])-1;
			ThemexCore::updatePostMeta(self::$data['ID'], 'course_popularity', $number);
		}
	}
	
	/**
	 * Deletes course member
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function deleteUser($ID) {
		ThemexCore::removeUserRelation($ID, 0, 'plan');
		ThemexCore::removeUserRelation($ID, 0, 'course');
		ThemexCore::removeUserRelation($ID, 0, 'certificate');
		ThemexCore::removeUserRelation($ID, 0, 'lesson');
		ThemexCore::removeUserRelation($ID, 0, 'quiz');
	}
	
	/**
	 * Checks course certificate
     *
     * @access public
     * @return bool
     */
	public static function hasCertificate() {
		if(self::$data['progress']==100) {
			$content=ThemexCore::getPostMeta(self::$data['ID'], 'course_certificate_content');
			
			if(self::isMember() && !empty($content)) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Gets course certificate
     *
     * @access public
	 * @param int $ID
	 * @param int $user
     * @return array
     */
	public static function getCertificate($ID, $user) {
		$certificate['content']=ThemexCore::getPostMeta($ID, 'course_certificate_content');
		$certificate['progress']=self::getProgress($ID, $user);

		if(!empty($certificate['content']) && $certificate['progress']==100) {
			$username=trim(get_user_meta($user, 'first_name', true).' '.get_user_meta($user, 'last_name', true));
			$title=get_the_title($ID);
			$grade=self::getGrade($ID, $user).'%';
			$time=ThemexCore::getUserRelations($user, $ID, 'certificate', true);
			
			$date=date_i18n(get_option('date_format'), $time);			
			if($time==0) {
				$date=date_i18n(get_option('date_format'));
			}
			
			$keywords=array(
				'username' => $username,
				'title' => $title,
				'grade' => $grade,
				'date' => $date,				
			);
			
			$certificate['user']=$user;
			$certificate['background']=ThemexCore::getPostMeta($ID, 'course_certificate_background');
			$certificate['content']=wpautop(themex_keywords($certificate['content'], $keywords));
		}
		
		return $certificate;
	}
	
	/**
	 * Gets course lessons
     *
     * @access public
	 * @param int $ID
	 * @param int $author
     * @return array
     */
	public static function getLessons($ID, $author) {
		$relations=ThemexCore::getPostRelations(0, $ID, 'lesson_course');
		$relations=array_merge(array(0), $relations);
		
		$args=array(
			'post_type' => 'lesson',
			'post__in' => $relations,
			'numberposts' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);
		
		$user=get_current_user_id();
		if($user!=0) {
			$time=ThemexCore::getUserRelations($user, $ID, 'course', true);
			if($time!=0 && !self::isAuthor($author)) {
				$delay=floor((current_time('timestamp')-$time)/86400);
				$args['meta_query']=array(
					'relation' => 'OR',
					array(
						'key' => '_lesson_delay',
						'value' => $delay,
						'compare' => '<=',
					),
					array(
						'key' => '_lesson_delay',
						'compare' => 'NOT EXISTS',
					),
				);
			}
		}
		
		$lessons=get_posts($args);	
		$sorted=array();
		
		foreach($lessons as $key => $parent) {
			if($parent->post_parent==0) {
				$sorted[]=$parent;
				unset($lessons[$key]);
				
				foreach($lessons as $index => $child) {
					if($child->post_parent==$parent->ID) {
						$sorted[]=$child;
						unset($lessons[$index]);
					}
				}
			}
		}
		
		return $sorted;
	}
	
	/**
	 * Gets adjacent lesson
     *
     * @access public
	 * @param int $ID
	 * @param bool $next
     * @return mixed
     */
	public static function getAdjacentLesson($ID, $next=true) {
		$adjacent=0;
		$current=0;
		
		foreach(self::$data['lessons'] as $index => $lesson) {
			if($lesson->ID==$ID) {
				$current=$index;
				break;
			}
		}
		
		if($next && isset(self::$data['lessons'][$current+1])) {
			$adjacent=self::$data['lessons'][$current+1];
		} else if(!$next && isset(self::$data['lessons'][$current-1])) {
			$adjacent=self::$data['lessons'][$current-1];
		}

		return $adjacent;
	}
	
	/**
	 * Gets course questions
     *
     * @access public	 
     * @return array
     */
	public static function getQuestions($ID) {
		global $lessons;
		
		$lessons=ThemexCore::getPostRelations(0, $ID, 'lesson_course');
		$number=intval(ThemexCore::getOption('course_questions_number', '7'));
		$questions=array();
		
		if($number!=0) {
			add_filter('comments_clauses', array( __CLASS__, 'filterQuestions'));		
			$questions=get_comments(array(
				'parent' => 0,
				'number' => ThemexCore::getOption('course_questions_number', '7'),
				'type' => 'comment',
			));
		}
		
		return $questions;
	}
	
	/**
	 * Filters course questions
     *
     * @access public
	 * @param string $query
     * @return string
     */
	public static function filterQuestions($query) {
		global $lessons;
		$lessons=implode(',', array_merge(array(0), $lessons));	
		
        $filter=" AND comment_post_ID IN ($lessons)";
        if (strpos($query['where'], ' AND comment_post_ID =')!==false) {
            $query['where']=preg_replace('~ AND comment_post_ID = \d+~', $filter, $query['where']);
        } else {
            $query['where'].=$filter;
        }

        remove_filter('comments_clauses', array( __CLASS__, 'filterQuestions'));
        return $query;
	}
	
	/**
	 * Adds statistics page
     *
     * @access public
     * @return void
     */
	public static function addStatistics() {
		add_submenu_page( 'edit.php?post_type=course', __('Statistics', 'academy'), __('Statistics', 'academy'), 'edit_posts', 'statistics', array(__CLASS__, 'renderStatistics')); 
		
		if(themex_value($_GET, 'page')=='statistics' && themex_value($_GET, 'export') && current_user_can('edit_posts')) {
			self::$data['statistics']=self::getStatistics();
			self::saveStatistics();
		}
	}
	
	/**
	 * Gets statistics
     *
     * @access public
     * @return array
     */
	public static function getStatistics() {
		$users=count_users();
		$statistics['user']['total']=$users['total_users'];
		$statistics['user']['active']=self::countMembers();
		$statistics['user']['grade']=self::countGrade();

		$courses=wp_count_posts('course');
		$statistics['course']['total']=$courses->publish;
		$statistics['course']['completed']=self::countCourses();
		$statistics['course']['average']=self::countMemberCourses();
		
		$statistics['user']['ID']=intval(themex_value($_GET, 'user'));
		$statistics['course']['ID']=intval(themex_value($_GET, 'course'));
		$statistics['lessons']=self::getLessonStatistics($statistics['course']['ID'], $statistics['user']['ID']);
		$statistics['users']=self::getMemberStatistics();
		
		return $statistics;
	}
	
	/**
	 * Renders statistics page
     *
     * @access public
     * @return void
     */
	public static function renderStatistics() {
		self::$data['statistics']=self::getStatistics();		
		get_template_part('template', 'statistics');
	}
	
	/**
	 * Saves statistics
     *
     * @access public
     * @return void
     */
	public static function saveStatistics() {
		$file='statistics.'.date('Y-m-d', current_time('timestamp')).'.csv';
		
		header('Content-Type: application/octet-stream');
		header('Content-Transfer-Encoding: Binary');
		header('Content-disposition: attachment; filename="'.$file.'"');
		ob_end_clean();
		
		$fields=ThemexCourse::$data['statistics']['users'];
		$columns=array(
			'username' => __('Username', 'academy'),
			'active' => __('Active Courses', 'academy'),
			'completed' => __('Completed Courses', 'academy'),
			'grade' => __('Average Grade', 'academy'),
		);
		
		if(ThemexCourse::$data['statistics']['user']['ID']!=0 || ThemexCourse::$data['statistics']['course']['ID']!=0) {
			$fields=ThemexCourse::$data['statistics']['lessons'];
			$columns=array();			
			
			if(ThemexCourse::$data['statistics']['user']['ID']==0) {
				$columns['username']=__('Username', 'academy');
			}
			
			if(ThemexCourse::$data['statistics']['course']['ID']==0) {
				$columns['course']=__('Course', 'academy');
			}
			
			$columns['lesson']=__('Lesson', 'academy');
			$columns['grade']=__('Grade', 'academy');
		}
		
		echo themex_csv($fields, $columns);
		exit();
	}
	
	/**
	 * Gets lessons statistics
     *
     * @access public
	 * @param int $ID
	 * @param int $user
     * @return array
     */
	public static function getLessonStatistics($ID, $user) {
		global $wpdb;
		
		$query="
			SELECT users.display_name AS username, 			
			courses.post_title AS course, 
			lessons.post_title AS lesson, 
			comments.comment_content AS grade 
			FROM ".$wpdb->comments." AS comments 
			INNER JOIN ".$wpdb->posts." AS lessons 
			ON (lessons.ID=comments.comment_post_ID AND comments.comment_type = 'user_lesson') 
			INNER JOIN ".$wpdb->users." AS users 
			ON (users.ID = comments.user_id) 
			INNER JOIN (
				SELECT courses.ID, meta.post_id, courses.post_title 
				FROM ".$wpdb->posts." AS courses 
				INNER JOIN ".$wpdb->postmeta." AS meta 
				ON (meta.meta_value = courses.ID AND meta.meta_key = '_lesson_course')
			) AS courses ON (courses.post_id = lessons.ID) 
			WHERE 1=1 
		";
		
		if($ID!=0) {
			$query.="AND courses.ID = ".$ID." ";
		}
		
		if($user!=0) {
			$query.="AND users.ID = ".$user." ";
		}
		
		$query.="ORDER BY users.display_name, courses.post_title, lessons.menu_order";
		$results=$wpdb->get_results($query, ARRAY_A);

		return $results;
	}
	
	/**
	 * Gets members statistics
     *
     * @access public
     * @return array
     */
	public static function getMemberStatistics() {
		global $wpdb;
		
		$results=$wpdb->get_results("
			SELECT users.display_name AS username, 
			COUNT(comments.comment_post_ID) AS active, 
			COALESCE(completed.number, 0) AS completed, 
			COALESCE(lessons.grade, 0) AS grade 
			FROM ".$wpdb->comments." AS comments 
			INNER JOIN ".$wpdb->users." AS users 
			ON (users.ID = comments.user_id AND comments.comment_type = 'user_course') 
			LEFT JOIN (
				SELECT COUNT(comment_post_ID) as number, user_id 
				FROM ".$wpdb->comments." 
				WHERE comment_type = 'user_certificate' 
				GROUP BY user_id
			) AS completed ON (completed.user_id = comments.user_id) 
			LEFT JOIN (
				SELECT ROUND(AVG(comment_content)) as grade, user_id 
				FROM ".$wpdb->comments." 
				WHERE comment_type = 'user_lesson' 
				GROUP BY user_id
			) AS lessons ON (lessons.user_id = comments.user_id) 
			GROUP BY users.ID 
			ORDER BY users.display_name
		", ARRAY_A);

		return $results;
	}
}