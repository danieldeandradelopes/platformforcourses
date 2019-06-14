<?php
/**
 * Themex User
 *
 * Handles users data
 *
 * @class ThemexUser
 * @author Themex
 */
 
class ThemexUser {

	/** @var array Contains module data. */
	public static $data;

	/**
	 * Adds actions and filters
     *
     * @access public
     * @return void
     */
	public static function init() {
	
		//refresh module data
		add_action('wp', array(__CLASS__, 'refresh'), 1);
		
		//update user actions
		add_action('wp', array(__CLASS__, 'updateUser'), 99);
		add_action('wp_ajax_themex_update_user', array(__CLASS__, 'updateUser'));
		add_action('wp_ajax_nopriv_themex_update_user', array(__CLASS__, 'updateUser'));
		add_action('template_redirect', array(__CLASS__, 'activateUser'));
		
		//filter user action
		add_action('template_redirect', array(__CLASS__, 'filterUser'));
		
		//add avatar filter
		add_filter('get_avatar', array(__CLASS__, 'getAvatar'), 10, 5);
		
		//render admin profile
		add_filter('show_user_profile', array(__CLASS__,'renderAdminProfile'));
		add_filter('edit_user_profile', array(__CLASS__,'renderAdminProfile'));
		
		//update admin profile
		add_action('edit_user_profile_update', array(__CLASS__,'updateAdminProfile'));
		add_action('personal_options_update', array(__CLASS__,'updateAdminProfile'));
		
		//render user toolbar
		add_filter('show_admin_bar', array(__CLASS__,'renderToolbar'));
	}
	
	/**
	 * Refreshes module data
     *
     * @access public	 
     * @return void
     */
	public static function refresh($ID=0, $extended=false) {
		if(!isset(self::$data['user'])) {
			self::$data['user']=self::getUser(get_current_user_id(), true);
		}

		if(!is_numeric($ID) && get_query_var('author')) {
			$ID=intval(get_query_var('author'));
			$extended=true;
		}
		
		if(is_numeric($ID)) {
			self::$data['active_user']=self::getUser($ID, $extended);
		} else {
			self::$data['active_user']=self::$data['user'];
		}
	}
	
	/**
	 * Gets user data
     *
     * @access public
	 * @param int $ID
     * @return array
     */
	public static function getUser($ID, $extended=false) {
		$user=array();
		$data=get_userdata($ID);
		
		if($data!==false) {
			$user['login']=$data->user_login;
			$user['email']=$data->user_email;
			
			$user['ID']=$ID;
			$user['profile_url']=get_author_posts_url($ID);	
			$user['profile']=self::getProfile($ID, $extended);
		}		

		return $user;
	}
	
	/**
	 * Updates user data
     *
     * @access public	 
     * @return void
     */
	public static function updateUser() {
		$data=$_POST;
		if(isset($_POST['data'])) {
			parse_str($_POST['data'], $data);
			$data['nonce']=$_POST['nonce'];
			check_ajax_referer(THEMEX_PREFIX.'nonce', 'nonce');
			self::refresh();
		}
				
		if(isset($data['user_action']) && wp_verify_nonce($data['nonce'], THEMEX_PREFIX.'nonce')) {
			switch(sanitize_title($data['user_action'])) {
				case 'register_user':
					self::registerUser($data);
				break;
				
				case 'login_user':
					self::loginUser($data);
				break;
				
				case 'reset_password':
					self::resetPassword($data);
				break;
			
				case 'update_profile':
					self::updateProfile(self::$data['user']['ID'], $data);
					wp_redirect(themex_url());
					exit();
				break;
				
				case 'update_avatar':
					self::updateAvatar(self::$data['user']['ID'], $_FILES['avatar']);
				break;
			}
		}
	}
	
	/**
	 * Filters user
     *
     * @access public
	 * @param int $ID
     * @return bool
     */
	public static function filterUser($ID=0) {
		if(ThemexCore::checkOption('user_filter') && ($ID!=0 || is_user_logged_in())) {
			if($ID==0) {
				$ID=get_current_user_id();
			}
			
			$filter=ThemexCore::getUserMeta($ID, 'filter', array());
			$time=current_time('timestamp');
			
			if(!isset($filter['time']) || $filter['time']<$time) {
				$time=$time+86400;
				$filter=array(
					'time' => $time,
					'users' => array(
						$_SERVER['REMOTE_ADDR'],
					),					
				);
				
				ThemexCore::updateUserMeta($ID, 'filter', $filter);
			} else if(!in_array($_SERVER['REMOTE_ADDR'], $filter['users'])) {			
				if(count($filter['users'])<5) {
					$filter['users'][]=$_SERVER['REMOTE_ADDR'];
					ThemexCore::updateUserMeta($ID, 'filter', $filter);
				} else {
					wp_logout();
					return true;
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Registers user
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function registerUser($data) {
		if(ThemexCore::checkOption('user_captcha')) {
			session_start();
			if(isset($data['captcha']) && isset($_SESSION['captcha'])) {
				$posted_code=md5($data['captcha']);
				$session_code=$_SESSION['captcha'];
				
				if($session_code!=$posted_code) {
					ThemexInterface::$messages[]=__('Verification code is incorrect', 'academy');
				}
			} else {
				ThemexInterface::$messages[]=__('Verification code is empty', 'academy');
			}
		}
		
		if(empty($data['user_email']) || empty($data['user_login']) || empty($data['user_password']) || empty($data['user_password_repeat'])) {
			ThemexInterface::$messages[]=__('Please fill in all fields', 'academy');
		} else {
			if(!is_email($data['user_email'])) {
				ThemexInterface::$messages[]=__('Invalid email address', 'academy');
			} else if(email_exists($data['user_email'])) {
				ThemexInterface::$messages[]=__('This email is already in use', 'academy');
			}
			
			if(!validate_username($data['user_login']) || is_email($data['user_login']) || preg_match('/\s/', $data['user_login'])) {
				ThemexInterface::$messages[]=__('Invalid character used in username', 'academy');
			} else	if(username_exists($data['user_login'])) {
				ThemexInterface::$messages[]=__('This username is already taken', 'academy');
			}
			
			if(strlen($data['user_password'])<4) {
				ThemexInterface::$messages[]=__('Password must be at least 4 characters long', 'academy');
			} else if(strlen($data['user_password'])>16) {
				ThemexInterface::$messages[]=__('Password must be not more than 16 characters long', 'academy');
			} else if(preg_match("/^([a-zA-Z0-9]{1,20})$/", $data['user_password'])==0) {
				ThemexInterface::$messages[]=__('Password contains invalid characters', 'academy');
			} else if($data['user_password']!=$data['user_password_repeat']) {
				ThemexInterface::$messages[]=__('Passwords do not match', 'academy');
			}
		}
		
		if(empty(ThemexInterface::$messages)){
			$user=wp_create_user($data['user_login'], $data['user_password'], $data['user_email']);			
			$message=ThemexCore::getOption('email_registration', 'Hi, %username%! Welcome to '.get_bloginfo('name').'. ');
			wp_new_user_notification($user);
			
			$keywords=array(
				'username' => $data['user_login'],
				'password' => $data['user_password'],
			);
			
			if(ThemexCore::checkOption('user_activation')) {
				ThemexInterface::$messages[]=__('Registration complete! Check your mailbox to activate the account', 'academy');
				$subject=__('Account Confirmation', 'academy');
				$activation_key=md5(uniqid(rand(), 1));
				
				$user=new WP_User($user);
				$user->remove_role(get_option('default_role'));
				$user->add_role('inactive');
				ThemexCore::updateUserMeta($user->ID, 'activation_key', $activation_key);
				
				if(isset($data['user_redirect']) && !empty($data['user_redirect'])) {
					ThemexCore::updateUserMeta($user->ID, 'redirect', intval($data['user_redirect']));
				}
				
				if(strpos($message, '%link%')===false) {
					$message.='Click this link to activate your account %link%';
				}

				$link=ThemexCore::getURL('register');
				if(intval(substr($link, -1))==1) {
					$link.='&';
				} else {
					$link.='?';
				}
				
				$keywords['link']=$link.'activate='.urlencode($activation_key).'&user='.$user->ID;
			} else {
				wp_signon($data, false);
				$subject=__('Registration Complete', 'academy');
				
				if(isset($data['user_redirect']) && !empty($data['user_redirect'])) {
					$redirect=ThemexCore::getURL('redirect', intval($data['user_redirect']));
				} else {
					$redirect=get_author_posts_url($user);
				}
			
				ThemexInterface::$messages[]='<a href="'.$redirect.'" class="redirect"></a>';
			}
			
			themex_mail($data['user_email'], $subject, themex_keywords($message, $keywords));
			ThemexInterface::renderMessages(true);
		} else {
			ThemexInterface::renderMessages();
		}
					
		die();
	}
	
	/**
	 * Activates user
     *
     * @access public
     * @return void
     */
	public static function activateUser() {
		if(isset($_GET['activate']) && isset($_GET['user']) && intval($_GET['user'])!=0) {
			$users=get_users(array(
				'meta_key' => '_'.THEMEX_PREFIX.'activation_key',
				'meta_value' => sanitize_text_field($_GET['activate']),
				'include' => intval($_GET['user']),
			));
			
			if(!empty($users)) {
				$user=reset($users);
				$user=new WP_User($user->ID);
				$user->remove_role('inactive');
				$user->add_role(get_option('default_role'));
				wp_set_auth_cookie($user->ID, true);
				ThemexCore::updateUserMeta($user->ID, 'activation_key', '');				
				
				$redirect=ThemexCore::getUserMeta($user->ID, 'redirect');
				if(!empty($redirect)) {
					$redirect=ThemexCore::getURL('redirect', intval($redirect));
					ThemexCore::updateUserMeta($user->ID, 'redirect', '');					
				} else {
					$redirect=get_author_posts_url($user->ID);
				}
				
				wp_redirect($redirect);
				exit();
			}
		}
	}
	
	/**
	 * Logins user
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function loginUser($data) {
		$data['remember']=true;		
		$user=wp_signon($data, false);
		
		if(is_wp_error($user) || empty($data['user_login']) || empty($data['user_password'])){
			ThemexInterface::$messages[]=__('Invalid username or password', 'academy');
		} else {
			$role=array_shift($user->roles);
			if($role=='inactive') {
				ThemexInterface::$messages[]=__('This account is not activated', 'academy');
			} else if(self::filterUser($user->ID)) {
				ThemexInterface::$messages[]=__('This account is already in use', 'academy');
			}
		}
		
		if(empty(ThemexInterface::$messages)) {
			if(isset($data['user_redirect']) && !empty($data['user_redirect'])) {
				$redirect=ThemexCore::getURL('redirect', intval($data['user_redirect']));
			} else {
				$redirect=get_author_posts_url($user->ID);
			}
			
			ThemexInterface::$messages[]='<a href="'.$redirect.'" class="redirect"></a>';
		} else {
			wp_logout();
		}
			
		ThemexInterface::renderMessages();
		die();
	}
	
	/**
	 * Resets password
     *
     * @access public
	 * @param array $data
     * @return void
     */
	public static function resetPassword($data) {
		global $wpdb, $wp_hasher;
		
		if(email_exists(sanitize_email($data['user_email']))) {
			$user=get_user_by('email', sanitize_email($data['user_email']));
			do_action('lostpassword_post');
			
			$login=$user->user_login;
			$email=$user->user_email;			
			
			do_action('retrieve_password', $login);
			$allow=apply_filters('allow_password_reset', true, $user->ID);
			
			if(!$allow || is_wp_error($allow)) {
				ThemexInterface::$messages[]=__('Password recovery not allowed', 'academy');
			} else {
				$key=wp_generate_password(20, false);
				do_action('retrieve_password_key', $login, $key);
				
				if(empty($wp_hasher)){
					require_once ABSPATH.'wp-includes/class-phpass.php';
					$wp_hasher=new PasswordHash(8, true);
				}

				$hashed=$wp_hasher->HashPassword($key);
				$wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $login));
				
				$link=network_site_url('wp-login.php?action=rp&key='.$key.'&login='.rawurlencode($login), 'login');
				$message=ThemexCore::getOption('email_password', 'Hi, %username%! Click this link to reset your password %link%');
				$keywords=array(
					'username' => $user->user_login,
					'link' => $link,
				);
				
				if(themex_mail($email, __('Password Recovery', 'academy'), themex_keywords($message, $keywords))) {
					ThemexInterface::$messages[]=__('Password reset link is sent', 'academy');
				} else {
					ThemexInterface::$messages[]=__('Error sending email', 'academy');
				}
			}			
		} else {
			ThemexInterface::$messages[]=__('Invalid email address', 'academy');		
		}
		
		ThemexInterface::renderMessages();		
		die();
	}
	
	/**
	 * Gets user profile
     *
     * @access public
	 * @param int $ID
	 * @param bool $extended
     * @return array
     */
	public static function getProfile($ID, $extended=false) {
		$meta=get_user_meta($ID);
		
		$profile['first_name']=themex_value($meta, 'first_name');
		$profile['last_name']=themex_value($meta, 'last_name');
		$profile['full_name']=trim($profile['first_name'].' '.$profile['last_name']);
		
		$profile['signature']=themex_value($meta, '_'.THEMEX_PREFIX.'signature');
		$profile['description']=wpautop(themex_value($meta, 'description'));
		
		$profile['facebook']=themex_value($meta, '_'.THEMEX_PREFIX.'facebook');
		$profile['twitter']=themex_value($meta, '_'.THEMEX_PREFIX.'twitter');
		$profile['google']=themex_value($meta, '_'.THEMEX_PREFIX.'google');
		$profile['tumblr']=themex_value($meta, '_'.THEMEX_PREFIX.'tumblr');
		$profile['linkedin']=themex_value($meta, '_'.THEMEX_PREFIX.'linkedin');
		$profile['flickr']=themex_value($meta, '_'.THEMEX_PREFIX.'flickr');
		$profile['youtube']=themex_value($meta, '_'.THEMEX_PREFIX.'youtube');
		$profile['vimeo']=themex_value($meta, '_'.THEMEX_PREFIX.'vimeo');
		
		if($extended && ThemexForm::isActive('profile')) {
			foreach(ThemexForm::$data['profile']['fields'] as $field) {
				$name=themex_sanitize_key($field['name']);
				if(!isset($profile[$name])) {
					$profile[$name]='';					
					if(isset($meta['_'.THEMEX_PREFIX.$name][0])) {
						$profile[$name]=$meta['_'.THEMEX_PREFIX.$name][0];
					}
				}
			}
		}
		
		return $profile;
	}
	
	/**
	 * Updates user profile
     *
     * @access public
	 * @param int $ID
	 * @param array $data
     * @return void
     */
	public static function updateProfile($ID, $data) {

		$fields=array(
			array(
				'name' => 'signature',
				'type' => 'text',
			),
			
			array(
				'name' => 'facebook',
				'type' => 'text',
			),
			
			array(
				'name' => 'twitter',
				'type' => 'text',
			),
			
			array(
				'name' => 'google',
				'type' => 'text',
			),
			
			array(
				'name' => 'tumblr',
				'type' => 'text',
			),
			
			array(
				'name' => 'linkedin',
				'type' => 'text',
			),
			
			array(
				'name' => 'flickr',
				'type' => 'text',
			),
			
			array(
				'name' => 'youtube',
				'type' => 'text',
			),
			
			array(
				'name' => 'vimeo',
				'type' => 'text',
			),
		);
		
		if(ThemexForm::isActive('profile')) {
			$fields=array_merge($fields, ThemexForm::$data['profile']['fields']);
		}

		foreach($fields as $field) {
			$name=themex_sanitize_key($field['name']);
			if(isset($data[$name])) {
				if($field['type']=='number') {
					$data[$name]=intval($data[$name]);
				} else if($field['type']=='name') {
					$data[$name]=ucwords(strtolower($data[$name]));
				} else {
					$data[$name]=sanitize_text_field($data[$name]);
				}
				
				ThemexCore::updateUserMeta($ID, $name, $data[$name]);
			}
		}
		
		//first name
		if(isset($data['first_name'])) {
			update_user_meta($ID, 'first_name', sanitize_text_field($data['first_name']));
		}
		
		//last name
		if(isset($data['last_name'])) {	
			update_user_meta($ID, 'last_name', sanitize_text_field($data['last_name']));
		}
		
		//description
		if(isset($data['description'])) {
			$data['description']=wp_kses($data['description'], array(
				'strong' => array(),
				'em' => array(),
				'a' => array(
					'href' => array(),
					'title' => array(),
					'target' => array(),
				),
				'p' => array(),
				'br' => array(),
			));

			update_user_meta($ID, 'description', $data['description']);
		}
	}
	
	/**
	 * Filters default avatar
     *
     * @access public
	 * @param string $avatar
	 * @param int $user_id
	 * @param int $size
	 * @param string $default
	 * @param string $alt
     * @return string
     */
	public static function getAvatar($avatar, $user, $size, $default, $alt) {
		if(isset($user->user_id)) {
			$user=$user->user_id;
		}
		
		$avatar_id=ThemexCore::getUserMeta($user, 'avatar');
		$default=wp_get_attachment_image_src( $avatar_id, 'preview');
		$image=THEME_URI.'images/avatar.png';
		
		if(isset($default[0])) {
			$image=themex_resize($default[0], $size, $size, true, true);
		}
		
		return '<img src="'.$image.'" class="avatar" width="'.$size.'" alt="'.$alt.'" />';
	}
	
	/**
	 * Updates user avatar
     *
     * @access public
	 * @param int $ID
	 * @param array $file
     * @return void
     */
	public static function updateAvatar($ID, $file) {
		wp_delete_attachment(ThemexCore::getUserMeta($ID, 'avatar'));
		$attachment=ThemexCore::uploadImage($file);

		if(isset($attachment['ID']) && $attachment['ID']!=0) {
			ThemexCore::updateUserMeta($ID, 'avatar', $attachment['ID']);
		}
	}
	
	/**
	 * Renders admin profile
     *
     * @access public
	 * @param mixed $user
     * @return void
     */
	public static function renderAdminProfile($user) {
		$profile=self::getProfile($user->ID);
		$out='<table class="form-table themex-profile"><tbody>';
		
		if(current_user_can('edit_users')) {
			$out.='<tr><th><label for="avatar">'.__('Profile Photo', 'academy').'</label></th>';
			$out.='<td><div class="themex-image-uploader">';
			$out.=get_avatar($user->ID);
			$out.=ThemexInterface::renderOption(array(
				'id' => 'avatar',
				'type' => 'uploader',
				'value' => '',
				'wrap' => false,				
			));
			$out.='</div></td></tr>';
		}
		
		if(!ThemexCore::checkOption('profile_signature')) {
			$out.='<tr><th><label>'.__('Signature', 'academy').'</label></th><td>';
			$out.='<input type="text" name="signature" value="'.$profile['signature'].'" />';
			$out.='</td></tr>';
		}
		
		ob_start();
		ThemexForm::renderData('profile', array(
			'edit' =>  true,
			'before_title' => '<tr><th><label>',
			'after_title' => '</th></label>',
			'before_content' => '<td>',
			'after_content' => '</td></tr>',
		), $profile);
		$out.=ob_get_contents();
		ob_end_clean();
		
		$out.='<tr><th><label>'.__('Profile Text', 'academy').'</label></th><td>';		
		ob_start();
		ThemexInterface::renderEditor('description', wpautop(get_user_meta($user->ID, 'description', true)));
		$out.=ob_get_contents();
		ob_end_clean();
		$out.='</td></tr>';
		
		if(!ThemexCore::checkOption('profile_links')) {
			$out.='<tr><th><label>'.__('Facebook', 'academy').'</label></th><td>';
			$out.='<input type="text" name="facebook" value="'.$profile['facebook'].'" />';
			$out.='</td></tr>';
			
			$out.='<tr><th><label>'.__('Twitter', 'academy').'</label></th><td>';
			$out.='<input type="text" name="twitter" value="'.$profile['twitter'].'" />';
			$out.='</td></tr>';
			
			$out.='<tr><th><label>'.__('Google', 'academy').'</label></th><td>';
			$out.='<input type="text" name="google" value="'.$profile['google'].'" />';
			$out.='</td></tr>';
			
			$out.='<tr><th><label>'.__('Tumblr', 'academy').'</label></th><td>';
			$out.='<input type="text" name="tumblr" value="'.$profile['tumblr'].'" />';
			$out.='</td></tr>';
			
			$out.='<tr><th><label>'.__('LinkedIn', 'academy').'</label></th><td>';
			$out.='<input type="text" name="linkedin" value="'.$profile['linkedin'].'" />';
			$out.='</td></tr>';
			
			$out.='<tr><th><label>'.__('Flickr', 'academy').'</label></th><td>';
			$out.='<input type="text" name="flickr" value="'.$profile['flickr'].'" />';
			$out.='</td></tr>';
			
			$out.='<tr><th><label>'.__('YouTube', 'academy').'</label></th><td>';
			$out.='<input type="text" name="youtube" value="'.$profile['youtube'].'" />';
			$out.='</td></tr>';
			
			$out.='<tr><th><label>'.__('Vimeo', 'academy').'</label></th><td>';
			$out.='<input type="text" name="vimeo" value="'.$profile['vimeo'].'" />';
			$out.='</td></tr>';
		}
		
		$out.='</tbody></table>';		
		echo $out;
	}
	
	/**
	 * Updates admin profile
     *
     * @access public
	 * @param mixed $user
     * @return void
     */
	public static function updateAdminProfile($user) {
		global $wpdb;
		self::updateProfile($user, $_POST);
		
		if(current_user_can('edit_users') && isset($_POST['avatar']) && !empty($_POST['avatar'])) {
			$query="SELECT ID FROM ".$wpdb->posts." WHERE guid = '".esc_url($_POST['avatar'])."'";
			$avatar=$wpdb->get_var($query);
			
			if(!empty($avatar)) {
				ThemexCore::updateUserMeta($user, 'avatar', $avatar);
			}
		}
	}
	
	/**
	 * Renders user toolbar
     *
     * @access public
     * @return bool
     */
	public static function renderToolbar() {
		if(current_user_can('edit_posts') && get_user_option('show_admin_bar_front', get_current_user_id())!='false') {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Checks profile page
     *
     * @access public
	 * @param int $ID
     * @return bool
     */
	public static function isProfile() {
		if(is_user_logged_in() && self::$data['active_user']['ID']==self::$data['user']['ID']) {
			return true;
		}
		
		return false;
	}
}