<?php
/**
 * Themex Woo
 *
 * Handles WooCommerce data
 *
 * @class ThemexWoo
 * @author Themex
 */
 
class ThemexWoo {

	/** @var array Contains module data. */
	public static $data;
	
	/** @var mixed Contains plugin instance. */
	public static $woocommerce;
	
	/**
	 * Adds actions and filters
     *
     * @access public
     * @return void
     */
	public static function init() {	
		//refresh data
		self::refresh();
		
		if(self::isActive()) {
		
			//get plugin instance
			self::$woocommerce=$GLOBALS['woocommerce'];
			
			//order actions
			add_action('woocommerce_order_status_completed', array(__CLASS__, 'completeOrder'));
			add_action('subscriptions_activated_for_order', array(__CLASS__, 'completeOrder'));	
			
			add_action('woocommerce_order_status_processing', array(__CLASS__, 'uncompleteOrder'));
			add_action('woocommerce_order_status_refunded', array(__CLASS__, 'uncompleteOrder'));
			add_action('subscriptions_cancelled_for_order', array(__CLASS__, 'uncompleteOrder'));
			
			//filters
			add_filter('woocommerce_checkout_fields', array(__CLASS__, 'filterFields'), 10, 1);
			
			//filter classes
			add_filter('body_class', array(__CLASS__, 'filterClasses'), 99);
		}
	}
	
	/**
	 * Refreshes module data
     *
     * @access public
     * @return void
     */
	public static function refresh() {
		self::$data=(array)ThemexCore::getOption(__CLASS__);
	}
	
	/**
	 * Renders module settings
     *
     * @access public
     * @return string
     */
	public static function renderSettings() {
		$out='<input type="hidden" name="'.__CLASS__.'[]" value="" />';
	
		$out.=ThemexInterface::renderOption(array(	
			'name' => __('Show Country', 'academy'),
			'id' => __CLASS__.'[billing_country]',
			'type' => 'checkbox',
			'default' => themex_value(self::$data, 'billing_country'),
		));
		
		$out.=ThemexInterface::renderOption(array(	
			'name' => __('Show City', 'academy'),
			'id' => __CLASS__.'[billing_city]',
			'type' => 'checkbox',
			'default' => themex_value(self::$data, 'billing_city'),
		));
			
		$out.=ThemexInterface::renderOption(array(
			'name' => __('Show State', 'academy'),
			'id' => __CLASS__.'[billing_state]',
			'type' => 'checkbox',
			'default' => themex_value(self::$data, 'billing_state'),
		));
			
		$out.=ThemexInterface::renderOption(array(
			'name' => __('Show Address', 'academy'),
			'id' => __CLASS__.'[billing_address]',
			'type' => 'checkbox',
			'default' => themex_value(self::$data, 'billing_address'),
		));
			
		$out.=ThemexInterface::renderOption(array(	
			'name' => __('Show Postcode', 'academy'),
			'id' => __CLASS__.'[billing_postcode]',
			'type' => 'checkbox',
			'default' => themex_value(self::$data, 'billing_postcode'),
		));
			
		$out.=ThemexInterface::renderOption(array(	
			'name' => __('Show Company', 'academy'),
			'id' => __CLASS__.'[billing_company]',
			'type' => 'checkbox',
			'default' => themex_value(self::$data, 'billing_company'),
		));
			
		$out.=ThemexInterface::renderOption(array(
			'name' => __('Show Phone', 'academy'),
			'id' => __CLASS__.'[billing_phone]',
			'type' => 'checkbox',
			'default' => themex_value(self::$data, 'billing_phone'),
		));
	
		return $out;
	}
	
	/**
	 * Gets product price
     *
     * @access public
	 * @param int $ID
	 * @param bool $numeric
     * @return string
     */
	public static function getPrice($ID) {
		$price['text']=__('Free', 'academy');
		$price['number']=0;
		$price['type']='simple';
		
		if(self::isActive() && $ID!=0) {
			$product=get_product($ID);
			
			if($product!==false) {
				$price['type']=$product->product_type;
				$price['text']=$product->get_price_html();
				$price['number']=$product->get_price();
			}
		}

		return $price;
	}
	
	/**
	 * Adds product to cart
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function addProduct($ID) {
		if(self::isActive()) {
			self::$woocommerce->cart->empty_cart();
			self::$woocommerce->cart->add_to_cart($ID, 1);
			wp_redirect(self::$woocommerce->cart->get_checkout_url());
			exit();
		}
	}
	
	/**
	 * Completes order
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function completeOrder($ID) {
		$relations=ThemexWoo::getRelatedPost($ID, array('course_product', 'plan_product'));
		
		if(!empty($relations)) {
			foreach($relations as $related) {
				if($related->post_type=='course') {
					ThemexCourse::addUser($related->ID, $related->post_author, true);
				} else if($related->post_type=='plan') {
					ThemexCourse::subscribeUser($related->ID, $related->post_author, true);
				}
			}
		}		
	}
	
	/**
	 * Uncompletes order
     *
     * @access public
	 * @param int $ID
     * @return void
     */
	public static function uncompleteOrder($ID) {
		$relations=ThemexWoo::getRelatedPost($ID, array('course_product', 'plan_product'));
		
		if(!empty($relations)) {
			foreach($relations as $related) {
				if($related->post_type=='course') {
					ThemexCourse::removeUser($related->ID, $related->post_author, true);
				} else if($related->post_type=='plan') {
					ThemexCourse::unsubscribeUser($related->ID, $related->post_author, true);
				}
			}
		}	
	}
	
	/**
	 * Gets related post
     *
     * @access public
	 * @param int $ID
	 * @param mixed $type
	 * @param bool $single
     * @return array
     */
	public static function getRelatedPost($ID, $type, $single=false) {
		$order=new WC_Order($ID);
		$products=$order->get_items();
		
		if(!empty($products)) {
			$ID=wp_list_pluck($products, 'product_id');
		}

		$relations=array_merge(array(0), ThemexCore::getPostRelations(0, $ID, $type));
		$posts=get_posts(array(
			'numberposts' => -1,
			'post_type' => array('course', 'plan'),
			'post__in' => $relations,
		));
		
		if(!empty($posts)) {
			if($order->user_id) {
				foreach($posts as &$post) {
					$post->post_author=$order->user_id;
				}
			}			
			
			if($single) {
				$posts=reset($posts);
			}
		}
		
		return $posts;
	}
	
	/**
	 * Filters checkouts
     *
     * @access public
	 * @param array $fields
     * @return array
     */
	public static function filterFields($fields) {
		self::$data['billing_first_name']=true;
		self::$data['billing_last_name']=true;
		self::$data['billing_email']=true;
		self::$data['shipping_first_name']=true;
		self::$data['shipping_last_name']=true;
		self::$data['order_comments']=true;
		self::$data['account_password']=true;
		
		foreach($fields as $form_key => $form) {
			foreach($form as $field_key => $field) {
				$short_key=str_replace(array('shipping_', 'billing_', '_1', '_2'), '', $field_key);				
				if(isset(self::$data[$field_key]) || isset(self::$data['billing_'.$short_key]) || isset(self::$data['shipping_'.$short_key])) {
					if(isset($fields[$form_key][$field_key]['label'])) {
						$fields[$form_key][$field_key]['placeholder']=$fields[$form_key][$field_key]['label'];
					}
				} else {
					unset($fields[$form_key][$field_key]);
				}
			}
		}
		
		return $fields;
	}
	
	/**
	 * Filters body classes
     *
     * @access public
	 * @param array $classes
     * @return array
     */
	public static function filterClasses($classes) {
		$classes=array_diff($classes, array('woocommerce-page', 'woocommerce'));	
		return $classes;
	}
	
	/**
	 * Checks checkout page
     *
     * @access public
     * @return bool
     */
	public static function isCheckout() {
		if(self::isActive() && (is_checkout() || isset($_GET['order']))) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Checks plugin activity
     *
     * @access public
     * @return bool
     */
	public static function isActive() {
		if(class_exists('Woocommerce')) {
			return true;
		}
		
		return false;
	}
}